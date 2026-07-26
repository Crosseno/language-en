<?php

declare(strict_types=1);

/*
 * Independently planned Crosseno vocabulary. This is authoring input, not a
 * third-party word list. Ordering is deliberate and determines which entries
 * enter a milestone when the candidate inventory exceeds its target.
 */
return [
    'household' => ['part_of_speech' => 'noun', 'words' => '
        attic, balcony, basement, basket, bathroom, bedroom, blanket, bottle, bowl, broom,
        bucket, cabinet, candle, carpet, ceiling, cellar, chair, closet, couch, curtain,
        cushion, desk, doorway, drawer, faucet, fireplace, floor, freezer, furnace, garage,
        garden, handle, hanger, kettle, kitchen, ladder, laundry, mattress, mirror, napkin,
        oven, pantry, pillow, plate, porch, radiator, roof, shelf, shower, sink,
        sofa, spoon, staircase, stool, table, towel, tray, wall, window, yard
    '],
    'food' => ['part_of_speech' => 'noun', 'words' => '
        almond, apple, bacon, bagel, banana, bean, berry, biscuit, bread, broccoli,
        butter, cabbage, cake, candy, carrot, cereal, cheese, cherry, chicken, chili,
        cinnamon, cocoa, coconut, coffee, cookie, corn, cream, cucumber, dinner, dough,
        drink, eggplant, flour, garlic, grape, gravy, honey, jam, juice, lemon,
        lettuce, lunch, mango, meal, melon, milk, muffin, mushroom, noodle, olive,
        onion, orange, pasta, peach, peanut, pear, pepper, pickle, pizza, plum,
        potato, pudding, pumpkin, rice, salad, sandwich, sauce, sausage, snack, soup,
        spice, spinach, sugar, syrup, toast, tomato, turkey, vanilla, vinegar, yogurt
    '],
    'cooking' => ['part_of_speech' => 'verb', 'words' => '
        bake, blend, boil, carve, chill, chop, cook, cool, dice, drain,
        fry, grate, grill, knead, mash, measure, melt, mix, peel, pour,
        roast, roll, season, serve, slice, soak, spread, steam, stir, taste,
        whisk
    '],
    'animals' => ['part_of_speech' => 'noun', 'words' => '
        antelope, badger, bat, bear, beaver, bee, beetle, bird, bison, butterfly,
        camel, cat, cattle, cheetah, chicken, crab, cricket, crocodile, crow, deer,
        dolphin, donkey, dove, duck, eagle, elephant, falcon, ferret, fish, flamingo,
        fox, frog, giraffe, goat, goose, gorilla, hamster, hawk, hedgehog, horse,
        insect, jaguar, kangaroo, kitten, leopard, lion, lizard, lobster, monkey, moose,
        mouse, otter, owl, panda, parrot, penguin, pigeon, pony, rabbit, raccoon,
        raven, robin, salmon, seal, shark, sheep, snail, snake, spider, squirrel,
        swan, tiger, turtle, whale, wolf, zebra
    '],
    'plants' => ['part_of_speech' => 'noun', 'words' => '
        acorn, bamboo, bark, blossom, branch, bush, cactus, cedar, clover, daisy,
        elm, fern, flower, forest, grass, herb, ivy, leaf, lily, maple,
        moss, oak, orchid, palm, petal, pine, plant, poppy, root, rose,
        seed, shrub, stem, sunflower, thorn, tree, tulip, vine, weed, willow,
        wood
    '],
    'nature' => ['part_of_speech' => 'noun', 'words' => '
        beach, cave, cliff, coast, desert, earth, field, hill, island, jungle,
        lake, land, meadow, moon, mountain, ocean, pebble, planet, pond, prairie,
        reef, river, rock, sand, sea, shore, sky, soil, star, stone,
        stream, sun, trail, valley, volcano, water, waterfall, wave, wilderness, world
    '],
    'weather' => ['part_of_speech' => 'noun', 'words' => '
        breeze, cloud, cold, cyclone, drizzle, drought, fog, frost, hail, heat,
        hurricane, ice, lightning, mist, rain, rainbow, season, shadow, sleet, snow,
        storm, thunder, tornado, weather, wind
    '],
    'body' => ['part_of_speech' => 'noun', 'words' => '
        ankle, arm, back, beard, blood, bone, brain, cheek, chest, chin,
        ear, elbow, eye, face, finger, foot, hair, hand, head, heart,
        heel, hip, knee, leg, lip, lung, mouth, muscle, nail, neck,
        nerve, nose, palm, shoulder, skin, spine, stomach, throat, thumb, toe,
        tongue, tooth, waist, wrist
    '],
    'health' => ['part_of_speech' => 'noun', 'words' => '
        allergy, bandage, bruise, clinic, cough, cure, doctor, exercise, fever, health,
        injury, medicine, nurse, patient, pulse, rest, sleep, sneeze, therapy, vitamin,
        wound
    '],
    'clothing' => ['part_of_speech' => 'noun', 'words' => '
        belt, blouse, boot, button, cap, coat, collar, costume, dress, fabric,
        glove, gown, hat, helmet, jacket, jeans, pocket, scarf, shirt, shoe,
        shorts, skirt, sleeve, slipper, sock, suit, sweater, tie, trousers, uniform,
        vest, zipper
    '],
    'transport' => ['part_of_speech' => 'noun', 'words' => '
        airplane, bicycle, boat, bridge, bus, cabin, canoe, carriage, cart, engine,
        ferry, flight, highway, journey, lane, motorcycle, paddle, passenger, path, railway,
        road, route, sail, scooter, ship, station, street, subway, taxi, ticket,
        track, tractor, traffic, train, travel, truck, tunnel, vehicle, wheel
    '],
    'places' => ['part_of_speech' => 'noun', 'words' => '
        airport, avenue, bakery, bank, barn, cafe, castle, church, cinema, city,
        college, country, courthouse, farm, harbor, hospital, hotel, library, market, museum,
        office, palace, park, playground, plaza, prison, restaurant, school, shop, square,
        stadium, store, studio, suburb, temple, theater, theatre, tower, town, village,
        warehouse, zoo
    '],
    'education' => ['part_of_speech' => 'noun', 'words' => '
        answer, book, chalk, class, course, degree, diploma, essay, exam, grade,
        homework, lesson, letter, map, notebook, paper, pencil, poem, question, reader,
        ruler, science, student, subject, teacher, test, textbook, word, writing
    '],
    'work' => ['part_of_speech' => 'noun', 'words' => '
        artist, baker, barber, builder, career, clerk, dentist, driver, farmer, guard,
        judge, lawyer, manager, mechanic, painter, pilot, plumber, police, reporter, sailor,
        salary, singer, soldier, tailor, worker, writer
    '],
    'tools' => ['part_of_speech' => 'noun', 'words' => '
        axe, brush, chisel, cord, drill, file, glue, hammer, hook, knife,
        needle, pliers, rope, saw, screw, shovel, spade, tape, tool, wrench
    '],
    'arts' => ['part_of_speech' => 'noun', 'words' => '
        album, art, ballet, camera, canvas, chorus, comedy, concert, dance, drama,
        drawing, film, gallery, guitar, melody, music, opera, painting, photo, piano,
        picture, portrait, rhythm, song, stage, story, trumpet, violin
    '],
    'science' => ['part_of_speech' => 'noun', 'words' => '
        atom, biology, carbon, cell, chemical, energy, force, fossil, gravity, laboratory,
        magnet, matter, metal, mineral, oxygen, physics, protein, sample, space, species,
        theory
    '],
    'technology' => ['part_of_speech' => 'noun', 'words' => '
        battery, cable, camera, computer, cursor, device, email, internet, keyboard, laptop,
        machine, message, mobile, monitor, network, phone, printer, robot, screen, signal,
        software, tablet, video, website
    '],
    'time' => ['part_of_speech' => 'noun', 'words' => '
        afternoon, age, autumn, century, dawn, day, decade, evening, future, hour,
        minute, moment, month, morning, night, noon, past, second, spring, summer,
        time, today, tomorrow, week, winter, year, yesterday
    '],
    'family' => ['part_of_speech' => 'noun', 'words' => '
        aunt, baby, brother, child, cousin, daughter, family, father, friend, guest,
        husband, mother, neighbor, parent, partner, sister, son, uncle, wife
    '],
    'society' => ['part_of_speech' => 'noun', 'words' => '
        adult, army, crowd, culture, election, government, group, history, law, leader,
        meeting, nation, people, person, public, team, community, citizen, council, custom
    '],
    'commerce' => ['part_of_speech' => 'noun', 'words' => '
        bill, business, cash, coin, cost, customer, dollar, money, price, product,
        sale, service, trade, value, wallet
    '],
    'sports' => ['part_of_speech' => 'noun', 'words' => '
        ball, baseball, basketball, coach, cricket, football, game, golf, hockey, player,
        race, score, soccer, sport, tennis, winner
    '],
    'actions' => ['part_of_speech' => 'verb', 'words' => '
        accept, add, agree, answer, arrive, ask, avoid, begin, believe, borrow,
        break, bring, build, buy, call, carry, catch, change, choose, clean,
        climb, close, collect, come, compare, continue, copy, count, cover, create,
        cross, cry, cut, decide, deliver, describe, discover, draw, dream, drink,
        drive, drop, eat, enter, explain, fall, feel, fill, find, finish,
        fix, fly, follow, forget, forgive, get, give, grow, guess, happen,
        hear, help, hide, hold, hope, imagine, improve, include, invite, join,
        jump, keep, kick, know, laugh, learn, leave, lift, listen, live,
        look, lose, love, make, meet, move, need, notice, offer, open,
        order, pack, pass, pay, pick, plan, play, point, practice, prepare,
        pull, push, reach, remember, repeat, return, ride, ring, rise, run,
        save, say, search, see, sell, send, share, show, sing, sit,
        smile, speak, spend, stand, start, stay, stop, study, swim, take,
        talk, teach, tell, think, throw, touch, try, turn, understand, use,
        visit, wait, wake, walk, want, wash, watch, wear, win, wish,
        work, write
    '],
    'adjectives' => ['part_of_speech' => 'adjective', 'words' => '
        able, afraid, alive, alone, angry, awake, bad, basic, beautiful, big,
        bitter, black, blind, blue, brave, bright, broken, brown, busy, calm,
        careful, cheap, clean, clear, clever, close, cold, common, cool, correct,
        dark, dead, deep, different, difficult, dirty, dry, early, easy, empty,
        equal, fair, false, famous, far, fast, fat, fine, flat,
        fresh, friendly, full, funny, gentle, glad, good, gray, great, green,
        happy, hard, healthy, heavy, high, honest, hot, huge, hungry, important,
        kind, large, late, light, little, local, lonely, long, loud, low,
        lucky, modern, narrow, natural, near, neat, new, nice, normal, old,
        orange, perfect, pink, plain, poor, popular, possible, proud, purple, quick,
        quiet, ready, real, red, rich, right, rough, round, safe, salty,
        same, serious, sharp, short, sick, simple, slow, small, smart, smooth,
        soft, special, strange, strong, sweet, tall, thick, thin, tired, true,
        warm, weak, wet, white, wide, wild, wise, wrong, yellow, young
    '],
    'abstract' => ['part_of_speech' => 'noun', 'words' => '
        ability, action, advice, beauty, chance, choice, danger, death, effort, example,
        fact, fear, freedom, idea, knowledge, life, luck, memory, mind, peace,
        problem, reason, safety, success, surprise, truth, voice
    '],
];
