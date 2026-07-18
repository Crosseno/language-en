<?php

declare(strict_types=1);

use Crosseno\Core\ResourceLimits;
use Crosseno\LanguageEn\EnglishLanguagePack;

require \dirname(__DIR__) . '/vendor/autoload.php';

$pack = EnglishLanguagePack::load(ResourceLimits::standard());
$expectedInventory = json_decode(
    file_get_contents(\dirname(__DIR__) . '/resources/source-inventory.json') ?: '',
    true,
    16,
    JSON_THROW_ON_ERROR,
);
if (!\is_array($expectedInventory) || !\is_array($expectedInventory['sources'] ?? null)) {
    throw new RuntimeException('Source inventory is invalid.');
}
$manifestSources = [];
foreach ($pack->manifest()->sources() as $source) {
    $manifestSources[$source->id] = [
        'url' => $source->url,
        'versionOrDate' => $source->versionOrDate,
        'sha256' => $source->sha256,
        'licenseExpression' => $source->licenseExpression,
        'attribution' => $source->attribution,
        'transformation' => $source->transformation,
        'redistributionStatus' => $source->redistributionStatus,
    ];
}
$inventorySources = [];
foreach ($expectedInventory['sources'] as $source) {
    if (!\is_array($source) || !\is_string($source['id'] ?? null)) {
        throw new RuntimeException('Source inventory contains an invalid record.');
    }
    $inventorySources[$source['id']] = array_intersect_key($source, [
        'url' => true,
        'versionOrDate' => true,
        'sha256' => true,
        'licenseExpression' => true,
        'attribution' => true,
        'transformation' => true,
        'redistributionStatus' => true,
    ]);
}
ksort($manifestSources, SORT_STRING);
ksort($inventorySources, SORT_STRING);
if ($manifestSources !== $inventorySources) {
    throw new RuntimeException('Manifest and source inventory disagree.');
}
$sourceHash = hash_file('sha256', \dirname(__DIR__) . '/resources/source/curated-en.json');
if (!\is_string($sourceHash) || !hash_equals($inventorySources['crosseno-curated-en-2026-07']['sha256'], $sourceHash)) {
    throw new RuntimeException('Curated source checksum does not agree with its inventory.');
}

fwrite(STDOUT, \sprintf("Verified %d English answers and all artifact hashes.\n", $pack->manifest()->recordCount));
