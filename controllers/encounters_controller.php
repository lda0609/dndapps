<?php

class EncountersController extends AppController
{

    var $uses = array('Monsters', 'xpThresholds', 'Adventure', 'AdventurersPerAdventure', 'Encounters', 'EncountersMonsters', 'MonsterFavorites');
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
            'conditions' => array('AdventurersPerAdventure.dnd_adventure_id' => $this->params['url']['idAventura']),
            'fields' => array('dnd_adventure_id', 'dnd_adventurers_id', 'lvl_inicial', 'xp_final', 'ausente', 'Adventurers.id', 'Adventurers.name', 'Adventurers.race', 'Adventurers.class', 'Adventurers.player'),
            'order' => 'AdventurersPerAdventure.id',
        ));

        $AdventurersPerAdventurePresentes = $this->AdventurersPerAdventure->find('all', array(
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
            'fields' => array('dnd_adventure_id', 'dnd_adventurers_id', 'lvl_inicial', 'xp_final', 'ausente', 'Adventurers.id', 'Adventurers.name', 'Adventurers.race', 'Adventurers.class', 'Adventurers.player'),
            'order' => 'AdventurersPerAdventure.id',
        ));

        $groupLvl = 0;
        $adjustedXpDay = $xpThreshold['deadly'] = $xpThreshold['hard'] = $xpThreshold['medium'] = $xpThreshold['easy'] = 0;

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

    function getAventureDates()
    {
        $this->autoRender = false;
        $data_adventures = $this->Adventure->find('list', array('fields' => 'date'));
        foreach ($data_adventures as $key => $value) {
            $arr = sscanf($value, '%04d-%02d-%02d');
            $data_adventures[$key] = $this->sprintf_array('%3$02d/%2$02d/%1$04d', $arr);
        }
//        $data_adventures = array_reverse($data_adventures, true);;
        return json_encode($data_adventures);
    }

    function atualizaPresenca()
    {
        $this->autoRender = false;
        $data_adventures = $this->AdventurersPerAdventure->find('first', array(
            'conditions' => array(
                'dnd_adventure_id' => $this->params['url']['idAventura'],
                'dnd_adventurers_id' => $this->params['url']['adventurer']
            ),
            'fields' => array('id')
        ));

        $data_adventures['AdventurersPerAdventure']['ausente'] = $this->params['url']['ausente'];
        if ($this->AdventurersPerAdventure->save($data_adventures)) {
            return json_encode('ok');
        } else {
            return json_encode('nok');
        }
    }

    function saveEncounter()
    {
        $this->autoRender = false;
        $encontro['Encounters']['dnd_adventure_id'] = $this->params['url']['idAventura'];
        $encontro['Encounters']['title'] = $this->params['url']['tituloEncontro'];
        $encontro['Encounters']['xp'] = $this->params['url']['totalEncontro'];
        $encontro['Encounters']['treasure'] = $this->params['url']['tesouro'];
        $encontro['Encounters']['adjusted_xp'] = $this->params['url']['adjustedXP'];
        $encontro['Encounters']['difficulty'] = $this->params['url']['difficulty'];

        if ($this->Encounters->save($encontro)) {
            foreach ($this->params['url']['monsterId'] as $key => $monsterId) {
                $monstros[$key]['EncountersMonsters']['dnd_encounters_id'] = $this->Encounters->id;
                $monstros[$key]['EncountersMonsters']['dnd_monsters_id'] = $monsterId;
                $monstros[$key]['EncountersMonsters']['quantidade'] = $this->params['url']['monsterQtde'][$key];
            }
            $this->EncountersMonsters->saveAll($monstros);
            return json_encode('ok');
        } else {
            return json_encode($encontro);
        }
    }

    function getEncounters()
    {
        $this->autoRender = false;
        $encontros = $this->Encounters->find('all', array('conditions' => array('dnd_adventure_id' => $this->params['url']['idAventura'])));
        return json_encode($encontros);
    }

    function deleteEncounter()
    {
        $this->autoRender = false;
        if ($this->EncountersMonsters->deleteAll(array('dnd_encounters_id' => $this->params['url']['encounterId']))) {
            if ($this->Encounters->delete(array('id' => $this->params['url']['encounterId']))) {
                return json_encode('ok');
            }
        }
        return json_encode('nok');
    }

    function addFavorite()
    {
        $this->autoRender = false;
        $favorite['MonsterFavorites']['dnd_monsters_id'] = $this->params['url']['monsterId'];
        $this->MonsterFavorites->save($favorite);
        return json_encode('ok');
    }

    function removeFavorite()
    {
        $this->autoRender = false;
        if ($this->MonsterFavorites->deleteAll(array('dnd_monsters_id' => $this->params['url']['monsterId'])))
            return json_encode('ok');
        else {
            return json_encode('nok');
        }
    }

    function getFavorites()
    {
//        $this->autoRender = false;
        $favorites = $this->MonsterFavorites->find('list', array('fields' => 'dnd_monsters_id'));
        $monsters = $this->Monsters->find('all', array('conditions' => array('id' => $favorites)));
        debug($monsters);

//        if ()
//        return json_encode('ok'); else {
//            return json_encode('nok');
//        }
    }

    private function sprintf_array($string, $array)
    {
        $keys = array_keys($array);
        $keysmap = array_flip($keys);
        $values = array_values($array);
        array_unshift($values, $string);
        return call_user_func_array('sprintf', $values);
    }

}
