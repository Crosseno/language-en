<?php

declare(strict_types=1);

use Crosseno\Compiler\Application\CompilationService;
use Crosseno\Compiler\Artifact\ArtifactValidator;
use Crosseno\Compiler\Artifact\AtomicPublisher;
use Crosseno\Compiler\Configuration\ConfigurationLoader;
use Crosseno\Compiler\Import\ImporterRegistry;
use Crosseno\Compiler\Language\IdentityLanguageHook;
use Crosseno\Compiler\Pipeline\LexicalPipeline;
use Crosseno\Compiler\Pipeline\LogFrequencyScorePolicy;
use Crosseno\LanguageEn\Build\EnglishEligibilityPolicy;
use Crosseno\LanguageEn\Normalization\EnglishAnswerNormalizer;
use Crosseno\LanguageEn\Tokenization\EnglishCellTokenizer;
use Crosseno\LexiconIndex\Compilation\CompilerIndexArtifactWriter;

require \dirname(__DIR__) . '/vendor/autoload.php';

$root = \dirname(__DIR__);
$configuration = (new ConfigurationLoader())->load($root . '/build/compiler.json');
$output = $root . '/build/output';
if (is_dir($output)) {
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($output, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST,
    );
    foreach ($iterator as $entry) {
        if (!$entry instanceof SplFileInfo) {
            throw new RuntimeException('Artifact staging directory returned an invalid entry.');
        }
        $entry->isDir() ? rmdir($entry->getPathname()) : unlink($entry->getPathname());
    }
    rmdir($output);
}
$compiler = new CompilationService(
    new ImporterRegistry(),
    new LexicalPipeline(
        new EnglishAnswerNormalizer(),
        new EnglishCellTokenizer(),
        new IdentityLanguageHook(),
        new EnglishEligibilityPolicy(),
        new LogFrequencyScorePolicy(),
    ),
    new AtomicPublisher(),
    new ArtifactValidator(),
    [new CompilerIndexArtifactWriter()],
);
$manifest = $compiler->compile($configuration, $output);
foreach (['catalog.sqlite', 'compilation-report.json', 'manifest.json', 'solver.idx'] as $artifact) {
    $destination = $root . '/resources/' . $artifact;
    if (is_file($destination) && !unlink($destination)) {
        throw new RuntimeException('Could not replace artifact: ' . $artifact);
    }
    if (!rename($output . '/' . $artifact, $destination)) {
        throw new RuntimeException('Could not publish artifact: ' . $artifact);
    }
}
rmdir($output);

fwrite(STDOUT, \sprintf("Built %d English answers (%s).\n", $manifest->recordCount, $manifest->stableKeyDigest));
