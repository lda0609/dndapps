<?php

class Races extends AppModel
{

    var $useTable = false;

    private function getDwarf()
    {
        $race['commonTraits'] = array(
            'race' => 'Dwarf',
            'abilityScoreIncrease' => array(0 => array('ability' => 'Constitution', 'increase' => '2')),
            'age' => array('adult' => '50', 'average' => '350'),
            'alignment' => 'Lawful',
            'height' => ('Between 4 and 5 feet'),
            'weight' => 'Average about 150 lbs',
            'size' => 'Medium',
            'speed' => '25'
        );

        $race['uniqueTraits'] = array(
            'Darkvision' => 'You can see in dim light within 60 feet of you as if it were bright light, and in darkness as if it were dim light.',
            'Dwarven Resilience' => 'You have advantage on saving throws against poison, and you have resistance against poison damage.',
            'Dwarven Combat Training' => 'You have proficiency with the battleaxe, handaxe, throwing hammer, and warhammer.',
            'Tool Proficiency' => 'You gain proficiency with the artisan\'s tools of your choice: smith\'s tools, brewer\'s supplies, or mason\'s tools',
            'Stonecunning' => 'Whenever you make an Intelligence (History) check related to the origin of stonework, you are considered proficient in the History 
            skill and add double your proficiency bonus to the check, instead of your normal proficiency bonus',
            'Languages' => 'You can speak, read, and write Common and Dwarvish. ',
            'Subraces' => 'Hill dwarves and mountain dwarves.',
        );

        return $race;
    }

    function getHillDwarf()
    {
        $race['race'] = 'Hill Dwarf';
        $race['commonTraits']['abilityScoreIncrease'][0] = array('ability' => 'Wisdom', 'increase' => '1');
        $race['uniqueTraits']['Dwarven Toughness'] = 'Your hit point maximum increases by 1, and it increases by 1 every time you gain a level.';
        return ($race);
    }

    function getMountainDwarf()
    {
        $race['race'] = 'Mountain Dwarf';
        $race['commonTraits']['abilityScoreIncrease'][0] = array('ability' => 'Strength', 'increase' => '2');
        $race['uniqueTraits']['Dwarven Armor Training'] = 'You have proficiency with light and medium armor.';
        return ($race);
    }

    private function getElf()
    {
        $race['commonTraits'] = array(
            'race' => 'Elf',
            'abilityScoreIncrease' => array(0 => array('ability' => 'Dextery', 'increase' => '2')),
            'age' => array('adult' => '100', 'average' => '750'),
            'alignment' => 'Chaotic',
            'height' => 'Under 5 to over 6 feet',
            'weight' => 'Slender build',
            'size' => 'Medium',
            'speed' => '30'
        );

        $race['uniqueTraits'] = array(
            'Darkvision' => 'You can see in dim light within 60 feet of you as if it were bright light, and in darkness as if it were dim light.',
            'Keen Senses' => 'You have proficiency in the Perception skill.',
            'Fey Ancestry' => 'You have advantage on saving throws against being charmed, and magic can\'t put you to sleep.',
            'Trance' => 'Elves don\'t need to sleep. Insrtead, they meditate deeply, remaining semiconscious, for 4 hours a day. (The Common word for such meditation
is "trance.") While meditating, you can dream after a fashion; such dreams are actually mental exercises that have become reflexive through years of practice. After
resting in this way, you gain the same benefit that a human does from 8 hours of sleep.',
            'Languages' => 'You can speak, read, and write Common and Elvish.',
            'Subraces' => 'High elves, wood elves, and dark elves.',
        );

        return $race;
    }

    function getHighElf()
    {
        $race['race'] = 'High Elf';
        $race['commonTraits']['abilityScoreIncrease'][0] = array('ability' => 'Intelligence', 'increase' => '1');
        $race['uniqueTraits']['Elf Weapon Training'] = 'You have proficiency with the longsword, shortsword, shortbow, and longbow.';
        $race['uniqueTraits']['Cantrip'] = 'You know one Cantrip of your choice from the wizard spell list. Inlelligence is your spellcasting abilily for it.';
        $race['uniqueTraits']['Extra Language'] = 'You can speak, read, and write one extra language of your choice.';
        return ($race);
    }

    function getWoodElf()
    {
        $race['race'] = 'Wood Elf';
        $race['commonTraits']['abilityScoreIncrease'][0] = array('ability' => 'Wisdom', 'increase' => '1');
        $race['uniqueTraits']['Elf Weapon Training'] = 'You have proficiency with the longsword, shortsword, shortbow, and longbow.';
        $race['uniqueTraits']['Fleet of Foot'] = 'Your base walking speed increases to 35 feet.';
        $race['uniqueTraits']['Mask of the Wild'] = 'You can attempt to hide even when you are only lighlly obscured by foliage, heavy rain, '
                . 'falling snow, mist, and olher natural phenomena.';
        return ($race);
    }

    function getWildElf()
    {
        $race['race'] = 'Wild Elf (Wood Elf Variant)';
        $race['commonTraits']['abilityScoreIncrease'][0] = array('ability' => 'Constitution', 'increase' => '1');
        return ($race);
    }

    function getDarkElf()
    {
        $race['race'] = 'Dark Elf (Drow)';
        $race['commonTraits']['abilityScoreIncrease'][0] = array('ability' => 'Charisma', 'increase' => '1');
        $race['uniqueTraits']['Superior Darkvision'] = 'Your darkvision has a radius of 120 feet.';
        $race['uniqueTraits']['Sunlight Sensitivity'] = 'You have disadvanlage on attack rolls and on Wisdom (Perception) checks that rely on
sight when you, the target of your altack, or whatever you are trying to perceive is in direct sunlight';
        $race['uniqueTraits']['Drow Magic'] = 'You know the dancing lights cantrip. When you reach 3rd level, you can cast the faerie fire
spell once per day. When you reach 5th level, you can also cast the darkness spell once per day. Charisma is your spellcasling abilily for these spells.';
        $race['uniqueTraits']['Drow Weapon Training'] = 'You have proficiency with rapiers, shortswords, and hand crossbows.';
        return ($race);
    }

    private function getHalfling()
    {
        $race['commonTraits'] = array(
            'race' => 'Halfling',
            'abilityScoreIncrease' => array(0 => array('ability' => 'Dextery', 'increase' => '2')),
            'age' => array('adult' => '20', 'average' => '150'),
            'alignment' => 'Lawful good',
            'height' => 'Average 3 feet tall',
            'weight' => 'About 40 pounds',
            'size' => 'Small',
            'speed' => '25'
        );

        $race['uniqueTraits'] = array(
            'Lucky' => 'When you roll a 1 on an attack roll, ability check, or saving throw, you can reroll the die and must use the new roll.',
            'Brave' => 'You have advantage on saving throws against being frightened.',
            'Halfling Nimbleness' => 'You can move through the space of any creature that is of a size larger than yours.',
            'Languages' => 'You can speak, read, and write Common and Halfling.',
            'Subraces' => 'Lightfoot and stout.',
        );

        return $race;
    }

    function getLightfoot()
    {
        $race['race'] = 'Lightfoot';
        $race['commonTraits']['abilityScoreIncrease'][0] = array('ability' => 'Charisma', 'increase' => '1');
        $race['uniqueTraits']['Naturally Stealthy'] = 'You can attempt to hide even when you are obscured only by a creature that is at least one size larger than you';
        return ($race);
    }

    function getStout()
    {
        $race['race'] = 'Stout';
        $race['commonTraits']['abilityScoreIncrease'][0] = array('ability' => 'Constitution', 'increase' => '1');
        $race['uniqueTraits']['Stout Resilience'] = 'You have advantage on saving throws against poison, and you have resistance against poison damage.';
        return ($race);
    }

    private function getHuman()
    {
        $race['commonTraits'] = array(
            'race' => 'Human',
            'abilityScoreIncrease' => array(0 => array('ability' => 'All Abilities', 'increase' => '1')),
            'age' => array('adult' => '16', 'average' => '90'),
            'alignment' => 'No tendencies',
            'height' => 'Under 5 to over 6 feet',
            'weight' => 'Vary Widely',
            'size' => 'Medium',
            'speed' => '30'
        );

        $race['uniqueTraits'] = array(
            'Languages' => 'You can speak, read, and write Common and one extra language of your choice.',
            'Variant Human Traits' => 'Two different ability scores of your choice increase by l. Proficiency in one skill. You gain one feat of your choice.',
        );

        return $race;
    }

    private function getDragonborn()
    {
        $race['commonTraits'] = array(
            'race' => 'Dragonborn',
            'abilityScoreIncrease' => array(0 => array('ability' => 'Strength', 'increase' => '2'), 1 => array('ability' => 'Charisma', 'increase' => '1')),
            'age' => array('adult' => '15', 'average' => '80'),
            'alignment' => 'Good or evil',
            'height' => 'Over 6 feet',
            'weight' => 'Average almost 250 lbs',
            'size' => 'Medium',
            'speed' => '30'
        );

        $race['uniqueTraits'] = array(
            'Draconic Ancestry' => 'Choose one type of dragon from the Draconic Ancestry table. Your breath weapon and damage resistance are determined by the dragon type as shown in the table.',
            'Breath Weapon' => 'Vou can use your action to exhale destructive energy. Your draconic ancestry determines the size, shape, and damage type of the exhalation.' .
            'After you use your breath weapon, you can\'t use it again until you complete a short or long rest.',
            'Damage Resistance' => 'You have resistance to the damage type associated with your draconic ancestry.',
            'Languages' => 'You can speak, read, and write Common and Draconic.',
        );

        return $race;
    }

    private function getGnome()
    {
        $race['commonTraits'] = array(
            'race' => 'Gnome',
            'abilityScoreIncrease' => array(0 => array('ability' => 'Intelligence', 'increase' => '2')),
            'age' => array('adult' => '40', 'average' => '500'),
            'alignment' => 'Good',
            'height' => 'Between 3 and 4 feet tall',
            'weight' => 'About 40 pounds',
            'size' => 'Small',
            'speed' => '25'
        );

        $race['uniqueTraits'] = array(
            'Darkvision' => 'You can see in dim light within 60 feet of you as if it were bright light, and in darkness as if it were dim light.',
            'Gnome Cunning' => 'You have advantage on all Intelligence, Wisdom, and Charisma saving throws against magic.',
            'Languages' => 'You can speak, read, and write Common and Gnomish.',
            'Subraces' => 'Forest Gnomes and Rock Gnomes.',
        );

        return $race;
    }

    function getForestGnomes()
    {
        $race['race'] = 'Forest Gnome';
        $race['commonTraits']['abilityScoreIncrease'][0] = array('ability' => 'Dextery', 'increase' => '1');
        $race['uniqueTraits']['Naturally Illusionist'] = 'You know the minor illusion cantrip. Intelligence is your spellcasting ability for it.';
        $race['uniqueTraits']['Speak with Small Beasts'] = 'Through sounds and gestures, you can communicate simple ideas with Small or smaller beasts. 
            Forest gnomes love animals and often keep squirrels, badgers, rabbits, moles, woodpeckers, and other creatures as beloved pets.';
        return ($race);
    }

    function getRockGnomes()
    {
        $race['race'] = 'Rock Gnome';
        $race['commonTraits']['abilityScoreIncrease'][0] = array('ability' => 'Constitution', 'increase' => '1');
        $race['uniqueTraits']['Artificer\'s Lore'] = 'Whenever you make an Intelligence (History) check related to magic items, alchemical objects, or technological 
            devices, you can add twice your proficiency bonus, instead of any proficiency bonus you normally apply.';
        $race['uniqueTraits']['Tinker'] = 'You have proficiency with artisan\'s tools (tinker\'s tools). Using those tools, you can spend 1 hour and 10 gp worth of 
            materials to construct a Tiny c1ockwork device (AC 5, 1 hp)';
        return ($race);
    }

    private function getHalfElf()
    {
        $race['commonTraits'] = array(
            'race' => 'Half-Elf',
            'abilityScoreIncrease' => array(0 => array('ability' => 'Charisma', 'increase' => '2'), 1 => array('ability' => '2 others', 'increase' => '1')),
            'age' => array('adult' => '20', 'average' => '180'),
            'alignment' => 'Chaotic',
            'height' => 'From 5 to 6 feet',
            'weight' => 'Vary Widely',
            'size' => 'Medium',
            'speed' => '30'
        );

        $race['uniqueTraits'] = array(
            'Darkvision' => 'You can see in dim light within 60 feet of you as if it were bright light, and in darkness as if it were dim light.',
            'Fey Ancestry' => 'You have advantage on saving throws against being charmed, and magic can\'t put you to sleep.',
            'Skill Versatility' => 'You gain proficiency in two skills of your choice.',
            'Languages' => 'You can speak, read, and write Common, Elvish and one extra language of your choice.',
        );

        return $race;
    }
    
        function getHalfWildElf()
    {
        $race['race'] = 'Half-Elf (Half-Elf Variant: Wild Elf ancestry)';
        $race['commonTraits']['abilityScoreIncrease'][0] = array('ability' => 'Constitution', 'increase' => '1');
        $race['commonTraits']['abilityScoreIncrease'][1] = array('ability' => 'Any Other', 'increase' => '2');
        return ($race);
    }


    private function getHalfOrc()
    {
        $race['commonTraits'] = array(
            'race' => 'Half-Orc',
            'abilityScoreIncrease' => array(0 => array('ability' => 'Strength', 'increase' => '2'), 1 => array('ability' => 'Constitution', 'increase' => '1')),
            'age' => array('adult' => '14', 'average' => '75'),
            'alignment' => 'Chaotic and not good',
            'height' => 'From 5 to over 6 feet',
            'weight' => 'Bulkier than humans',
            'size' => 'Medium',
            'speed' => '30'
        );

        $race['uniqueTraits'] = array(
            'Darkvision' => 'You can see in dim light within 60 feet of you as if it were bright light, and in darkness as if it were dim light.',
            'Menacing' => 'You gain proficieney in the Intimidation skill.',
            'Relentless Endurance' => 'When you are reduced to O hit points but not killed outright. you can drop to 1 hit point instead. 
                You can\'t use this feature again until you finish a long rest.',
            'Savage Attacks' => 'When you score a critical hit with a melee weapon attack, you can roll one of thc weapon\'s damage dice one additional 
                time and add it to the extra damage of the critical hit.',
            'Languages' => 'You can speak, read, and write Common and Orc.',
        );

        return $race;
    }

    private function getTiefling()
    {
        $race['commonTraits'] = array(
            'race' => 'Tiefling',
            'abilityScoreIncrease' => array(0 => array('ability' => 'Charisma', 'increase' => '2'), 1 => array('ability' => 'Intelligence', 'increase' => '1')),
            'age' => array('adult' => '16', 'average' => '100'),
            'alignment' => 'Chaotic',
            'height' => 'Same as human',
            'weight' => 'Same as human',
            'size' => 'Medium',
            'speed' => '30'
        );

        $race['uniqueTraits'] = array(
            'Darkvision' => 'You can see in dim light within 60 feet of you as if it were bright light, and in darkness as if it were dim light.',
            'Hellish Resistance' => 'You have resistance to fire damage.',
            'Infernal Legacy' => 'You know the thaumaturgy cantrip. Once you reach 3rd level, you can cast the hellish rebuke spell once per day as a 2nd-levei spell. 
                Once you reach 5th level, you can also cast the darkness spell once per day. Charisma is your spellcasting ability for these spells.',
            'Languages' => 'You can speak, read, and write Common and Infernal.',
        );

        return $race;
    }

    function getRacesComplete()
    {
        $races['Dwarf'] = $this->getDwarf();
        $races['Dwarf']['subrace'][] = $this->getHillDwarf();
        $races['Dwarf']['subrace'][] = $this->getMountainDwarf();
        $races['Elf'] = $this->getElf();
        $races['Elf']['subrace'][] = $this->getHighElf();
        $races['Elf']['subrace'][] = $this->getWoodElf();
        $races['Elf']['subrace'][] = $this->getWildElf();
        $races['Elf']['subrace'][] = $this->getDarkElf();
        $races['Halfling'] = $this->getHalfling();
        $races['Halfling']['subrace'][] = $this->getLightfoot();
        $races['Halfling']['subrace'][] = $this->getStout();

        $races['Gnome'] = $this->getGnome();
        $races['Gnome']['subrace'][] = $this->getForestGnomes();
        $races['Gnome']['subrace'][] = $this->getRockGnomes();

        $races['Human'] = $this->getHuman();
        $races['Dragonborn'] = $this->getDragonborn();
        $races['Half-Elf'] = $this->getHalfElf();
        $races['Half-Elf']['subrace'][] = $this->getHalfWildElf();
        $races['Half-Orc'] = $this->getHalfOrc();
        $races['Tiefling'] = $this->getTiefling();

        return $races;
    }

}
