<?php

class EncountersController extends AppController
{

    var $uses = array('Monsters', 'xpThresholds', 'Adventure', 'AdventurersPerAdventure', 'Encounters', 'EncountersMonsters');
    var $multiplier = array(
        '1' => 0.5,
        '2' => 1,
        '3-6' => 1.5,
        '7-10' => 2,
        '11-14' => 2.5,
        '15+' => 3
    );
    var $xpDay = array(
        1 => 300,
        2 => 600,
        3 => 1200,
        4 => 1700,
        5 => 3500,
        6 => 4000,
        7 => 5000,
        8 => 6000,
        9 => 7500,
        10 => 9000,
        11 => 10500,
        12 => 11500,
        13 => 13500,
        14 => 15000,
        15 => 18000,
        16 => 20000,
        17 => 25000,
        18 => 27000,
        19 => 30000,
        20 => 40000,
    );

    function index()
    {
        $data_adventures = $this->Adventure->find('list', array('fields' => 'date'));
        foreach ($data_adventures as $key => $value) {
            $arr = sscanf($value, '%04d-%02d-%02d');
            $data_adventures[$key] = $this->sprintf_array('%3$02d/%2$02d/%1$04d', $arr);
        }
        $data_adventures = array_reverse($data_adventures, true);
        $this->set('data_adventures', $data_adventures);
        $this->set('dnd_xp', $this->viewVars['dnd_xp']);
        $this->set('dnd_classes', $this->viewVars['dnd_classes']);
    }

    function getAdventurers()
    {
        $this->autoRender = false;
        $xpTable = $this->xpThresholds->find('all');
        $AdventurersPerAdventure = $this->AdventurersPerAdventure->find('all', array(
            'joins' => array(
                array('table' => 'adventurers',
                    'alias' => 'Adventurers',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Adventurers.id = AdventurersPerAdventure.dnd_adventurers_id',
                    )
                )
            ),
            'conditions' => array('AdventurersPerAdventure.dnd_adventure_id' => $this->params['url']['idAventura'], 'AdventurersPerAdventure.ausente' => '0'),
            'fields' => array('dnd_adventure_id', 'dnd_adventurers_id', 'lvl_inicial', 'xp_final', 'Adventurers.name', 'Adventurers.race', 'Adventurers.class', 'Adventurers.player'),
            'order' => 'AdventurersPerAdventure.id',
        ));
        $groupLvl = 0;
        $adjustedXpDay = $xpThreshold['deadly'] = $xpThreshold['hard'] = $xpThreshold['medium'] = $xpThreshold['easy'] = 0;

        foreach ($AdventurersPerAdventure as $key => $adventurer) {
            $groupLvl += $adventurer['AdventurersPerAdventure']['lvl_inicial'];
            $xp = $xpTable[$adventurer['AdventurersPerAdventure']['lvl_inicial'] - 1]['xpThresholds'];
            $xpThreshold['easy'] += $xp['easy'];
            $xpThreshold['medium'] += $xp['medium'];
            $xpThreshold['hard'] += $xp['hard'];
            $xpThreshold['deadly'] += $xp['deadly'];

            $adjustedXpDay += $this->xpDay[$adventurer['AdventurersPerAdventure']['lvl_inicial']];
        }

        if (count($AdventurersPerAdventure) <= 5) {
            $this->multiplier['1'] = 1;
            $this->multiplier['2'] = 1.5;
            $this->multiplier['3-6'] = 2;
            $this->multiplier['7-10'] = 2.5;
            $this->multiplier['11-14'] = 3;
            $this->multiplier['15+'] = 4;
        }

        foreach ($xpThreshold as $difficulty => $XPvalue) {
            $xpMultiplier[$difficulty]['1'] = round($XPvalue / $this->multiplier['1'], 0);
            $xpMultiplier[$difficulty]['2'] = round($XPvalue / $this->multiplier['2'], 0);
            $xpMultiplier[$difficulty]['3-6'] = round($XPvalue / $this->multiplier['3-6'], 0);
            $xpMultiplier[$difficulty]['7-10'] = round($XPvalue / $this->multiplier['7-10'], 0);
            $xpMultiplier[$difficulty]['11-14'] = round($XPvalue / $this->multiplier['11-14'], 0);
            $xpMultiplier[$difficulty]['15+'] = round($XPvalue / $this->multiplier['15+'], 0);
        }

        $retorno['multiplierIndex'] = $this->multiplier;
        $retorno['xpMultiplier'] = $xpMultiplier;
        $retorno['xpThreshold'] = $xpThreshold;
        $retorno['groupLvl'] = $groupLvl;
        $retorno['adventurers'] = $AdventurersPerAdventure;
        $retorno['adjustedXpDay'] = $adjustedXpDay;

        return json_encode($retorno);
    }

    private function sprintf_array($string, $array)
    {
        $keys = array_keys($array);
        $keysmap = array_flip($keys);
        $values = array_values($array);
        array_unshift($values, $string);
        return call_user_func_array('sprintf', $values);
    }

//    function xpThreshold()
//    {
//
//        $data_adventures = $this->Adventure->find('list', array('fields' => 'date'));
//        $this->set('data_adventures', $data_adventures);
//
//        if (!empty($this->data)) {
//            $xpTable = $this->xpThresholds->find('all');
////            $this->adventurersPerAdventure->recursive = -1;
//            $AdventurersPerAdventure = $this->AdventurersPerAdventure->find('all', array(
//                'joins' => array(
//                    array('table' => 'adventurers',
//                        'alias' => 'Adventurers',
//                        'type' => 'LEFT',
//                        'conditions' => array(
//                            'Adventurers.id = AdventurersPerAdventure.dnd_adventurers_id',
//                        )
//                    )
//                ),
//                'conditions' => array('AdventurersPerAdventure.dnd_adventure_id' => $this->data['Encounter']['data'], 'AdventurersPerAdventure.ausente' => '0'),
//                'fields' => array('dnd_adventure_id', 'dnd_adventurers_id', 'lvl_inicial', 'Adventurers.name', 'Adventurers.race', 'Adventurers.class', 'Adventurers.player'),
//                'order' => 'AdventurersPerAdventure.id',
//            ));
//            $groupLvl = 0;
//            $adjustedXpDay = $xpThreshold['deadly'] = $xpThreshold['hard'] = $xpThreshold['medium'] = $xpThreshold['easy'] = 0;
//
//            foreach ($AdventurersPerAdventure as $key => $adventurer) {
//                $groupLvl += $adventurer['AdventurersPerAdventure']['lvl_inicial'];
//                $xp = $xpTable[$adventurer['AdventurersPerAdventure']['lvl_inicial'] - 1]['xpThresholds'];
//                $xpThreshold['easy'] += $xp['easy'];
//                $xpThreshold['medium'] += $xp['medium'];
//                $xpThreshold['hard'] += $xp['hard'];
//                $xpThreshold['deadly'] += $xp['deadly'];
//
//                $adjustedXpDay += $this->xpDay[$adventurer['AdventurersPerAdventure']['lvl_inicial']];
//            }
//
//            if (count($AdventurersPerAdventure) <= 5) {
//                $this->multiplier['1'] = 1;
//                $this->multiplier['2'] = 1.5;
//                $this->multiplier['3'] = 2;
//                $this->multiplier['7'] = 2.5;
//                $this->multiplier['11'] = 3;
//                $this->multiplier['15'] = 4;
//            }
//
//            foreach ($xpThreshold as $difficulty => $XPvalue) {
//                $xpMultiplier[$difficulty]['1'] = round($XPvalue / $this->multiplier['1'], 0);
//                $xpMultiplier[$difficulty]['2'] = round($XPvalue / $this->multiplier['2'], 0);
//                $xpMultiplier[$difficulty]['3-6'] = round($XPvalue / $this->multiplier['3'], 0);
//                $xpMultiplier[$difficulty]['7-10'] = round($XPvalue / $this->multiplier['7'], 0);
//                $xpMultiplier[$difficulty]['11-14'] = round($XPvalue / $this->multiplier['11'], 0);
//                $xpMultiplier[$difficulty]['15+'] = round($XPvalue / $this->multiplier['15'], 0);
//            }
//            $this->set('multiplierIndex', $this->multiplier);
//            $this->set('dnd_xp', $this->viewVars['dnd_xp']);
//            $this->set('xpMultiplier', $xpMultiplier);
//            $this->set('xpThreshold', $xpThreshold);
//            $this->set('groupLvl', $groupLvl);
//            $this->set('adventurers', $AdventurersPerAdventure);
//            $this->set('adjustedXpDay', $adjustedXpDay);
//        }
//    }
}
