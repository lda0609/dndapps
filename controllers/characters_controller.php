<?php

class CharactersController extends AppController
{

    var $uses = array('Alignment', 'CharacterProgression', 'Adventurers', 'AdventurersPerAdventure', 'Adventure');
    var $xp_threshold = [0, 300, 900, 2700, 6500, 14000, 23000, 34000, 48000, 64000, 85000, 100000, 120000, 140000, 165000, 195000, 225000, 265000, 305000, 355000];

    function index()
    {

//        Configure::write('debug', 2);
//        $Alignment = $this->Alignment->find('list', array('fields'=>'shortname'));
//        debug($Alignment);
//        $characterProgression = $this->CharacterProgression->find('all', array(
//            'conditions' => array(
//                'dnd_adventurers_id' => '1',
//                'lvl' => '5',
//        )));
//
//        $AdventurersPerAdventure = $this->AdventurersPerAdventure->find('all', array(
//            'joins' => array(
//                array('table' => 'adventurers',
//                    'alias' => 'Adventurers',
//                    'type' => 'LEFT',
//                    'conditions' => array(
//                        'Adventurers.id = AdventurersPerAdventure.dnd_adventurers_id',
//                    )
//                )
//            ),
//            'conditions' => array('AdventurersPerAdventure.dnd_adventure_id' => 9),
//            'fields' => array('dnd_adventure_id', 'dnd_adventurers_id', 'lvl_inicial', 'xp_final', 'ausente', 'Adventurers.id', 'Adventurers.name', 'Adventurers.race', 'Adventurers.class', 'Adventurers.player', 'Adventurers.alignment'),
//            'order' => 'AdventurersPerAdventure.id',
//        ));
//        debug($AdventurersPerAdventure);
//
//        $this->set('characterProgression', $characterProgression);
//
//        debug($characterProgression);
    }

    function getAdventurersFromDate()
    {
        $this->autoRender = false;
        $adventurers = $this->AdventurersPerAdventure->find('all', array(
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
            'fields' => array('dnd_adventure_id', 'dnd_adventurers_id', 'lvl_inicial', 'xp_final', 'ausente', 'Adventurers.id', 'Adventurers.name', 'Adventurers.race', 'Adventurers.class', 'Adventurers.player', 'Adventurers.alignment', 'Adventurers.background'),
            'order' => 'AdventurersPerAdventure.id',
        ));

        foreach ($adventurers as $key => $value) {
            $characterProgression = $this->CharacterProgression->find('first', array(
                'conditions' => array(
                    'dnd_adventurers_id' => $value['AdventurersPerAdventure']['dnd_adventurers_id'],
                    'lvl' => $value['AdventurersPerAdventure']['lvl_inicial'],
            )));

            $adventurers[$key]['CharacterProgression'] = $characterProgression['CharacterProgression'];
        }
        return json_encode($adventurers);
    }

    function getListCharacters()
    {
        $this->autoRender = false;

        $aventuraAnterior = $this->Adventure->find('list', array('fields' => 'id'));
        $aventuraAnteriorId = max($aventuraAnterior);
        $AdventurersPerAdventureAnterior = $this->AdventurersPerAdventure->find('all', array(
            'conditions' => array(
                'dnd_adventure_id' => $aventuraAnteriorId,
            ),
            'fields' => array('xp_final', 'dnd_adventurers_id')
        ));
        foreach ($AdventurersPerAdventureAnterior as $key => $value) {
            $xpAnterior[$value['AdventurersPerAdventure']['dnd_adventurers_id']] = $value['AdventurersPerAdventure']['xp_final'];
        }

        $adventurers = $this->Adventurers->find("all");
        foreach ($adventurers as $key => $value) {
            $lvl = 0;
            while ($xpAnterior[$value['Adventurers']['id']] >= $this->xp_threshold[$lvl]) {
                $lvl++;
            }
            $adventurers[$key]['Adventurers']['lvl_inicial'] = $lvl;
        }

        return json_encode($adventurers);
    }

    function salvarGrupo()
    {
        $this->autoRender = false;
        $data = explode('/', $this->params['url']['dataAventura']);

        if (!empty($data) && checkdate($data[1], $data[0], $data[2])) {
            $adventure['Adventure']['date'] = $this->params['url']['dataAventura'];
            if ($this->Adventure->save($adventure)) {
//                $adventure = $this->Adventure->find('first', array('conditions' => array('date' => $this->params['url']['dataAventura'])));

                foreach ($this->params['url']['level'] as $key => $level) {
                    $adventurersPerAdventure = array();
                    $adventurersPerAdventure['AdventurersPerAdventure']['dnd_adventure_id'] = $this->Adventure->id;
                    $adventurersPerAdventure['AdventurersPerAdventure']['dnd_adventurers_id'] = $key + 1;
                    $adventurersPerAdventure['AdventurersPerAdventure']['lvl_inicial'] = $level;
                    $adventurersPerAdventure['AdventurersPerAdventure']['ausente'] = 0;
                    if (!empty($this->params['url']['ausencia'])) {
                        if (in_array('aventureiro' . ($key + 1), $this->params['url']['ausencia'])) {
                            $adventurersPerAdventure['AdventurersPerAdventure']['ausente'] = 1;
                        }
                    }
                    $this->AdventurersPerAdventure->saveAll($adventurersPerAdventure);
                }
                return json_encode('ok');
            } else {
                return json_encode('nok');
            }
        } else {
            return json_encode('nok');
        }
    }

    function atualizarXP()
    {
        $this->autoRender = false;
        $aventuraAnterior = $this->Adventure->find('list', array('fields' => 'id'));
        unset($aventuraAnterior[$this->params['url']['idAventura']]);
        $aventuraAnteriorId = max($aventuraAnterior);
        $AdventurersPerAdventureAnterior = $this->AdventurersPerAdventure->find('all', array(
            'conditions' => array(
                'dnd_adventure_id' => $aventuraAnteriorId,
            ),
            'fields' => array('xp_final', 'dnd_adventurers_id')
        ));
        foreach ($AdventurersPerAdventureAnterior as $key => $value) {
            $xpAnterior[$value['AdventurersPerAdventure']['dnd_adventurers_id']] = $value['AdventurersPerAdventure']['xp_final'];
        }
        foreach ($this->params['url']['xp'] as $key => $value) {
            $AdventurerPerAdventure = $this->AdventurersPerAdventure->find('first', array('conditions' => array(
                    'dnd_adventure_id' => $this->params['url']['idAventura'],
                    'dnd_adventurers_id' => $key + 1
            )));
            if (empty($value)) {
                $value = 0;
            }
            $AdventurerPerAdventure['AdventurersPerAdventure']['xp_final'] = $xpAnterior[$key + 1] + $value;
            $this->AdventurersPerAdventure->save($AdventurerPerAdventure);
        }
        return json_encode('ok');
    }

    function saveCharacter()
    {
        $this->autoRender = false;

        $character = ($this->params['url']['adventurer']);
        $adventurer['Adventurers'] = $character['Adventurers'];
        $progression['CharacterProgression'] = $character['CharacterProgression'];
        if ($this->Adventurers->save($adventurer)) {
            $progression['CharacterProgression']['dnd_adventurers_id'] = $this->Adventurers->id;
            if ($this->CharacterProgression->save($progression)) {
                return json_encode('ok');
            } else {
                return json_encode('nok 2');
            }
        } else {
            return json_encode('nok 1');
        }
    }

    function getCharactersAllLevels()
    {
        $this->autoRender = false;

        $adventurers = $this->Adventurers->find('all');
        $retorno = array();
        foreach ($adventurers as $key => $adventurer) {
            $characterProgression = $this->CharacterProgression->find('all', array(
                'conditions' => array(
                    'dnd_adventurers_id' => $adventurer['Adventurers']['id']
                ),
            ));
            $adventurers[$key]['CharacterProgression'] = $characterProgression;
            foreach ($characterProgression as $key2 => $charProgression) {
                $temp['CharacterProgressionId'] = $charProgression['CharacterProgression']['id'];
                $temp['name'] = $adventurer['Adventurers']['name'];
                $temp['lvl'] = $charProgression['CharacterProgression']['lvl'];
                array_push($retorno, $temp);
            }
        }

        return json_encode($retorno);
    }

    function getCharacterDetails()
    {
        $this->autoRender = false;

        $characterProgression = $this->CharacterProgression->find('first', array(
            'conditions' => array(
                'id' => $this->params['url']['CharacterProgressionId'],
            ),
        ));
        $adventurers = $this->Adventurers->find('first', array(
            'conditions' => array(
                'id' => $characterProgression['CharacterProgression']['dnd_adventurers_id'],
        )));

        $retorno['CharacterProgression'] = $characterProgression['CharacterProgression'];
        $retorno['Adventurers'] = $adventurers['Adventurers'];
        return json_encode($retorno);
    }

}
