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

    function getAdventurers($idAventura, $atualizaAventura = null)
    {
        $this->autoRender = false;

        $AdventurersPerAdventure = $this->AdventurersPerAdventure->find('all', array(
            'joins' => array(
                array('table' => 'adventurers',
                    'alias' => 'Adventurers',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Adventurers.id = AdventurersPerAdventure.dnd_adventurers_id',
                    ),
                )
            ),
            'conditions' => array('AdventurersPerAdventure.dnd_adventure_id' => $idAventura),
            'fields' => array('dnd_adventure_id', 'dnd_adventurers_id', 'lvl_inicial', 'xp_inicial', 'xp_final', 'ausente', 'Adventurers.id', 'Adventurers.name', 'Adventurers.race', 'Adventurers.class', 'Adventurers.player', 'Adventurers.xp'),
            'order' => 'Adventurers.id',
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

        if ($atualizaAventura === '1') {
            $this->atualizaAventura($idAventura, $retorno);
        }
        return json_encode($retorno);
    }

    private function atualizaAventura($adventureId, $dados)
    {
        $this->autoRender = false;
        $this->Encounters->unBindModel(array('hasMany' => array('EncountersMonsters')));
        $encounters = $this->Encounters->find('all', array(
            'conditions' => array(
                'dnd_adventure_id' => $adventureId,
            ),
            'fields' => array('Encounters.id', 'Encounters.xp', 'Encounters.difficulty')
        ));

        foreach ($encounters as $key => $encounter) {
            $sumMonsters = $this->EncountersMonsters->find('first', array(
                'conditions' => array(
                    'dnd_encounters_id' => $encounter['Encounters']['id'],
                ),
                'fields' => array('sum(EncountersMonsters.quantidade) AS encounter__total')
            ));

            $xp = $encounter['Encounters']['xp'];
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

            $this->Encounters->id = $encounter['Encounters']['id'];
            $updated_encounter['Encounters']['difficulty'] = $new_difficulty;
            $updated_encounter['Encounters']['adjusted_xp'] = $encounter['Encounters']['xp'] * $this->multiplier[$qtde];
            $this->Encounters->save($updated_encounter);
        }
        return true;
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

    function getAdventureDates()
    {
        $this->autoRender = false;
        $data_adventures = $this->Adventure->find('list', array('fields' => array('Adventure.date')));
        foreach ($data_adventures as $key => $value) {
            $arr = sscanf($value, '%04d-%02d-%02d');
            $data_adventures[$key] = $this->sprintf_array('%3$02d/%2$02d/%1$04d', $arr);
        }
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
        $encontro['Encounters']['information'] = $this->params['url']['information'];

        $ordem = $this->Encounters->find('first', array(
            'conditions' => array(
                'dnd_adventure_id' => $this->params['url']['idAventura']
            ),
            'fields' => 'MAX(ordem) AS ordem_encontro'
        ));
        $encontro['Encounters']['ordem'] = $ordem[0]['ordem_encontro'] + 1;
        if ($this->Encounters->save($encontro)) {
            foreach ($this->params['url']['monsterId'] as $key => $monsterId) {
                $monstros[$key]['EncountersMonsters']['dnd_encounters_id'] = $this->Encounters->id;
                $monstros[$key]['EncountersMonsters']['dnd_monsters_id'] = $monsterId;
                $monstros[$key]['EncountersMonsters']['quantidade'] = $this->params['url']['monsterQtde'][$key];
                $monstros[$key]['EncountersMonsters']['alias'] = $this->params['url']['monsterAlias'][$key];
            }
            $this->EncountersMonsters->saveAll($monstros);
            return json_encode($this->Encounters->id);
        } else {
            return json_encode($encontro);
        }
    }

    function getAllEncounters()
    {
        $this->autoRender = false;
        $encontros = $this->Encounters->find('all', array(
            'conditions' => array('dnd_adventure_id' => $this->params['url']['idAventura']),
            'order' => 'ordem'));

        foreach ($encontros as $key => $encontro) {
            $encontros[$key]['EncountersMonsters'] = $this->ordenarEncontro($encontros[$key]['EncountersMonsters']);
        }
        foreach ($encontros as $key => $encontro) {
            foreach ($encontro['EncountersMonsters'] as $key2 => $monster) {
                $MonsterData = $this->Monsters->getMonster($monster['dnd_monsters_id']);
                $encontros[$key]['EncountersMonsters'][$key2]['name'] = $MonsterData['Monsters']['name'];
                $encontros[$key]['EncountersMonsters'][$key2]['page'] = $MonsterData['Monsters']['page'];
                $encontros[$key]['EncountersMonsters'][$key2]['book'] = $MonsterData['Monsters']['book'];
            }
        }
        return json_encode($encontros);
    }

    function getEncounter()
    {
        $this->autoRender = false;
        $encontros = $this->Encounters->find('first', array(
            'conditions' => array(
                'id' => $this->params['url']['encounterId']
            )
        ));
        $cont = 0;
        foreach ($encontros['EncountersMonsters'] as $key => $encounterMonster) {
            $monster = $this->Monsters->find('first', array('conditions' => array('id' => $encounterMonster['dnd_monsters_id'])));
            for ($i = 1; $i <= $encounterMonster['quantidade']; $i++) {
                $monsterList['monsters'][$cont]['realname'] = $monster['Monsters']['name'] . ' (' . $i . ')';
                if (empty($encounterMonster['alias'])) {
                    $monsterList['monsters'][$cont]['name'] = $monster['Monsters']['name'] . ' (' . $i . ')';
                } else {
                    $monsterList['monsters'][$cont]['name'] = $encounterMonster['alias'] . ' (' . $i . ')';
                }
                $monsterList['monsters'][$cont]['hp'] = $monster['Monsters']['hp'];
                $monsterList['monsters'][$cont]['bookpage'] = $monster['Monsters']['book'] . ', ' . $monster['Monsters']['page'];
                $cont++;
            }
        }
        $monsterList['information'] = $encontros['Encounters']['information'];
        return json_encode($monsterList);
    }

    function editEncounter()
    {
        $this->autoRender = false;

        $this->EncountersMonsters->id = $this->params['url']['encounterMonsterId'];
        $EncountersMonsters['EncountersMonsters']['quantidade'] = $this->params['url']['quantidade'];
        $this->EncountersMonsters->save($EncountersMonsters);

        $dados = json_decode($this->getAdventurers($this->params['url']['idAventura']), true);
        $this->atualizaEncontro($this->params['url']['encounterId'], $dados);
        return json_encode('ok');
    }

    function changeOrder()
    {
        $this->autoRender = false;
        if ($this->params['form']['action'] === 'up') {
            $encounter = $this->Encounters->findById($this->params['form']['encounterId']);
            $ordemOriginal = $encounter['Encounters']['ordem'];
            $encounter['Encounters']['ordem'] --;
        } elseif ($this->params['form']['action'] === 'down') {
            $encounter = $this->Encounters->findById($this->params['form']['encounterId']);
            $ordemOriginal = $encounter['Encounters']['ordem'];
            $encounter['Encounters']['ordem'] ++;
        } else {
            return json_encode('changeOrder() - unexpected action: ' . $this->params['form']['action']);
        }

        if ($encounter['Encounters']['ordem'] == 0) {
            return json_encode('changeOrder() - ordem não pode ser 0');
        } else {

            $encounterReplaced = $this->Encounters->find('first', array(
                'conditions' => array(
                    'dnd_adventure_id' => $encounter['Encounters']['dnd_adventure_id'],
                    'ordem' => $encounter['Encounters']['ordem']
                )
            ));
            if (!empty($encounterReplaced)) {
                $encounterReplaced['Encounters']['ordem'] = $ordemOriginal;
                $this->Encounters->save($encounterReplaced);
            }
            $this->Encounters->save($encounter);
            return json_encode('ok');
        }
    }

    function editTreasure()
    {
        $this->autoRender = false;
        $encounter = $this->Encounters->findById($this->params['form']['encounterId']);
        $encounter['Encounters']['treasure'] = $this->params['form']['newTreasure'];
        if ($this->Encounters->save($encounter)) {
            return json_encode('ok');
        } else {
            return json_encode('editTreasure() - nok');
        }
    }

    function editInformation()
    {
        $this->autoRender = false;

        $encounter = $this->Encounters->findById($this->params['form']['encounterId']);
        $encounter['Encounters']['information'] = $this->params['form']['newInfo'];
        if ($this->Encounters->save($encounter)) {
            return json_encode('ok');
        } else {
            return json_encode('editInformation() - nok');
        }
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

    private function sprintf_array($string, $array)
    {
        $keys = array_keys($array);
        $keysmap = array_flip($keys);
        $values = array_values($array);
        array_unshift($values, $string);
        return call_user_func_array('sprintf', $values);
    }

    private function ordenarEncontro($encontro)
    {
        $APlen = count($encontro);
        $j = 0;
        $swapped = true;
        while ($swapped) {
            $swapped = false;
            $j++;
            for ($i = 0; $i < $APlen - $j; $i++) {
                if ($encontro[$i]['id'] > $encontro[$i + 1]['id']) {
                    $temp = $encontro[$i];
                    $encontro[$i] = $encontro[$i + 1];
                    $encontro[$i + 1] = $temp;
                    $swapped = true;
                }
            }
        }
        return $encontro;
    }

}
