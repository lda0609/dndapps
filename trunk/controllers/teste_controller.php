<?php

class TesteController extends AppController
{

    var $uses = array('Monsters', 'xpThresholds', 'Adventure', 'AdventurersPerAdventure', 'Encounters', 'EncountersMonsters', 'MonsterFavorites', 'MonsterTypes');
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
        Configure::write('debug', 2);
        $this->autoRender = false;
        $types = $this->MonsterTypes->find('all', array());
        $group = array();
        foreach ($types as $key => $value) {
            if (array_key_exists($value['MonsterTypes']['dnd_monsters_id'], $group)) {
                $group[$value['MonsterTypes']['dnd_monsters_id']] = $group[$value['MonsterTypes']['dnd_monsters_id']] + 1;
            } else {
                $group[$value['MonsterTypes']['dnd_monsters_id']] = 1;
            }
        }
//        $monsterall = $this->Monsters->find('all');
//        debug($monsterall);
//        die;
        debug(count($group));
        $count = 0;
        foreach ($group as $key => $value) {
            if ($value >= 1) {
//                debug($key);
                $count ++;
                $monster = $this->Monsters->findByid($key);
                debug($monster);
                foreach ($monster['MonsterTypes'] as $monstertype) {
                    if ($monstertype['dnd_type_id'] >= 1 && $monstertype['dnd_type_id'] <= 14) {
                        if (!isset($monster['Monsters']['type'])) {
                            $monster['Monsters']['type'] = $monstertype['dnd_type_id'];
                        } else {
                           debug($monster);
                           debug($monstertype['dnd_type_id']);
                        }
                    } elseif ($monstertype['dnd_type_id'] >= 15 && $monstertype['dnd_type_id'] != 22) {
                        if (!isset($monster['Monsters']['race'])) {
                            $monster['Monsters']['tag'] = $monstertype['dnd_type_id'];
                        } else {
                           debug($monster);
                           debug($monstertype['dnd_type_id']);
                        }
                    } elseif ($monstertype['dnd_type_id'] == 22) {
                        $monster['Monsters']['shapechanger'] = 1;
                    }
                } 
//                debug($monster);
//                debug($this->Monsters->save($monster));
            }
        }
        debug($count);
    }

    private function atualizaEncontro($encounterId, $dados)
    {

        $sumMonsters = $this->EncountersMonsters->find('first', array(
            'conditions' => array(
                'dnd_encounters_id' => $encounterId,
            ),
            'fields' => array('sum(EncountersMonsters.quantidade) AS encounter__total')
        ));

        $EncountersMonsters = $this->EncountersMonsters->find('all', array(
            'conditions' => array(
                'dnd_encounters_id' => $encounterId,
            ),
        ));
        $xp = 0;
        foreach ($EncountersMonsters as $key => $value) {
            $monster = $this->Monsters->find('first', array(
                'conditions' => array(
                    'id' => $value['EncountersMonsters']['dnd_monsters_id'],
                ),
                'fields' => array('cr')
            ));
            $xp += $value['EncountersMonsters']['quantidade'] * $this->viewVars['dnd_xp'][$monster['Monsters']['cr']];
        }

        $sumMonsters = $sumMonsters['encounter']['total'];
        if ($sumMonsters == '1') {
            $qtde = '1';
        } elseif ($sumMonsters == '2') {
            $qtde = '2';
        } elseif ($sumMonsters <= '6') {
            $qtde = '3-6';
        } elseif ($sumMonsters <= '10') {
            $qtde = '7-10';
        } elseif ($sumMonsters <= '14') {
            $qtde = '11-14';
        } else {
            $qtde = '15+';
        }

        if ($xp < $dados['xpMultiplier']['easy'][$qtde]) {
            $new_difficulty = 'Too Easy';
        } elseif ($xp < $dados['xpMultiplier']['medium'][$qtde]) {
            $new_difficulty = 'Easy';
        } elseif ($xp < $dados['xpMultiplier']['hard'][$qtde]) {
            $new_difficulty = 'Medium';
        } elseif ($xp < $dados['xpMultiplier']['deadly'][$qtde]) {
            $new_difficulty = 'Hard';
        } else {
            $new_difficulty = 'Deadly';
        }

        $this->Encounters->id = $encounterId;
        $updated_encounter['Encounters']['difficulty'] = $new_difficulty;
        $updated_encounter['Encounters']['xp'] = $xp;
        $updated_encounter['Encounters']['adjusted_xp'] = $xp * $this->multiplier[$qtde];
        $this->Encounters->save($updated_encounter);
        return true;
    }

    function getAdventurers($idAventura, $atualizaAventura = null)
    {
        $this->autoRender = false;
        debug($idAventura);
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
            'conditions' => array('AdventurersPerAdventure.dnd_adventure_id' => $this->params['url']['idAventura']),
            'fields' => array('dnd_adventure_id', 'dnd_adventurers_id', 'lvl_inicial', 'xp_final', 'ausente', 'Adventurers.id', 'Adventurers.name', 'Adventurers.race', 'Adventurers.class', 'Adventurers.player'),
            'order' => 'AdventurersPerAdventure.id',
        ));
        //getAdventurersPresentes($idAventura){}
        foreach ($AdventurersPerAdventure as $key => $value) {
            if ($value['AdventurersPerAdventure']['ausente'] == '0') {
                $AdventurersPerAdventurePresentes[] = $AdventurersPerAdventure[$key];
            }
        }

        $groupLvl = 0;
        $adjustedXpDay = $xpThreshold['deadly'] = $xpThreshold['hard'] = $xpThreshold['medium'] = $xpThreshold['easy'] = 0;

        $xpTable = $this->xpThresholds->find('all');
        foreach ($AdventurersPerAdventurePresentes as $key => $adventurer) {
            $groupLvl += $adventurer['AdventurersPerAdventure']['lvl_inicial'];
            $xp = $xpTable[$adventurer['AdventurersPerAdventure']['lvl_inicial'] - 1]['xpThresholds'];
            $xpThreshold['easy'] += $xp['easy'];
            $xpThreshold['medium'] += $xp['medium'];
            $xpThreshold['hard'] += $xp['hard'];
            $xpThreshold['deadly'] += $xp['deadly'];

            $adjustedXpDay += $this->xpDay[$adventurer['AdventurersPerAdventure']['lvl_inicial']];
        }

        if (count($AdventurersPerAdventurePresentes) <= 5) {
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

}
