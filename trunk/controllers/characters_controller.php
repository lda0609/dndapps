<?php

class CharactersController extends AppController
{

    var $uses = array('CharacterProgression', 'Adventurers', 'AdventurersPerAdventure', 'Adventure', 'xpThresholds');

    function index()
    {
        $data_adventures = $this->Adventure->find('list', array('fields' => 'date'));
        $this->set('data_adventures', $data_adventures);

        debug($this->data);


        if (!empty($this->data)) {

            if (isset($this->data['Characters']['valueXP1'])) {
                if (!empty($this->data['Characters']['valueXP1'])) {
                    $AdvAtualizado = $this->AdventurersPerAdventure->find('first', array(
                        'conditions' => array(
                            'dnd_adventure_id' => $this->data['Characters']['data'],
                            'dnd_adventurers_id' => 1,
                        ),
                        'fields' => array('id')
                    ));
                    $AdvAtualizado['AdventurersPerAdventure']['xp_final'] = $this->data['Characters']['valueXP1'];
                    $this->AdventurersPerAdventure->save($AdvAtualizado);
                }
                if (!empty($this->data['Characters']['valueXP2'])) {
                    $AdvAtualizado = $this->AdventurersPerAdventure->find('first', array(
                        'conditions' => array(
                            'dnd_adventure_id' => $this->data['Characters']['data'],
                            'dnd_adventurers_id' => 2,
                        ),
                        'fields' => array('id')
                    ));
                    $AdvAtualizado['AdventurersPerAdventure']['xp_final'] = $this->data['Characters']['valueXP2'];
                    $this->AdventurersPerAdventure->save($AdvAtualizado);
                }
                if (!empty($this->data['Characters']['valueXP3'])) {
                    $AdvAtualizado = $this->AdventurersPerAdventure->find('first', array(
                        'conditions' => array(
                            'dnd_adventure_id' => $this->data['Characters']['data'],
                            'dnd_adventurers_id' => 3,
                        ),
                        'fields' => array('id')
                    ));
                    $AdvAtualizado['AdventurersPerAdventure']['xp_final'] = $this->data['Characters']['valueXP3'];
                    $this->AdventurersPerAdventure->save($AdvAtualizado);
                }
                if (!empty($this->data['Characters']['valueXP4'])) {
                    $AdvAtualizado = $this->AdventurersPerAdventure->find('first', array(
                        'conditions' => array(
                            'dnd_adventure_id' => $this->data['Characters']['data'],
                            'dnd_adventurers_id' => 4,
                        ),
                        'fields' => array('id')
                    ));
                    $AdvAtualizado['AdventurersPerAdventure']['xp_final'] = $this->data['Characters']['valueXP4'];
                    $this->AdventurersPerAdventure->save($AdvAtualizado);
                }
                if (!empty($this->data['Characters']['valueXP5'])) {
                    $AdvAtualizado = $this->AdventurersPerAdventure->find('first', array(
                        'conditions' => array(
                            'dnd_adventure_id' => $this->data['Characters']['data'],
                            'dnd_adventurers_id' => 5,
                        ),
                        'fields' => array('id')
                    ));
                    $AdvAtualizado['AdventurersPerAdventure']['xp_final'] = $this->data['Characters']['valueXP5'];
                    $this->AdventurersPerAdventure->save($AdvAtualizado);
                }
                if (!empty($this->data['Characters']['valueXP6'])) {
                    $AdvAtualizado = $this->AdventurersPerAdventure->find('first', array(
                        'conditions' => array(
                            'dnd_adventure_id' => $this->data['Characters']['data'],
                            'dnd_adventurers_id' => 6,
                        ),
                        'fields' => array('id')
                    ));
                    $AdvAtualizado['AdventurersPerAdventure']['xp_final'] = $this->data['Characters']['valueXP6'];
                    $this->AdventurersPerAdventure->save($AdvAtualizado);
                }
                if (!empty($this->data['Characters']['valueXP7'])) {
                    $AdvAtualizado = $this->AdventurersPerAdventure->find('first', array(
                        'conditions' => array(
                            'dnd_adventure_id' => $this->data['Characters']['data'],
                            'dnd_adventurers_id' => 7,
                        ),
                        'fields' => array('id')
                    ));
                    $AdvAtualizado['AdventurersPerAdventure']['xp_final'] = $this->data['Characters']['valueXP7'];
                    $this->AdventurersPerAdventure->save($AdvAtualizado);
                }
            }




            for ($char = 1; $char <= 7; $char++) {
                $AdventurersPerAdventure[$char] = $this->AdventurersPerAdventure->find('first', array(
                    'conditions' => array(
                        'dnd_adventure_id' => $this->data['Characters']['data'],
                        'dnd_adventurers_id' => $char,
                    ),
                    'fields' => array('lvl_inicial', 'xp_final')
                ));

                if ($this->data['Characters']['data'] > 1) {
                    $AdventurersPerAdventureAnterior[$char] = $this->AdventurersPerAdventure->find('first', array(
                        'conditions' => array(
                            'dnd_adventure_id' => $this->data['Characters']['data'] - 1,
                            'dnd_adventurers_id' => $char,
                        ),
                        'fields' => array('lvl_inicial', 'xp_final')
                    ));
                    $AdventurersPerAdventure[$char]['AdventurersPerAdventure']['xp_inicial'] = $AdventurersPerAdventureAnterior[$char]['AdventurersPerAdventure']['xp_final'];
                } else {
                    $AdventurersPerAdventure[$char]['AdventurersPerAdventure']['xp_inicial'] = 0;
                }


//                $pc[$char]['xp'] = $this->AdventurersPerAdventure->find('first', array(
//                    'conditions' => array(
//                        'dnd_adventure_id' => $this->data['Characters']['data'],
//                        'dnd_adventurers_id' => $char,
//                    ),
//                    'fields' => array('xp_final')
//                ));

                $pc[$char] = $this->CharacterProgression->find('first', array(
                    'joins' => array(
                        array('table' => 'adventurers',
                            'alias' => 'Adventurers',
                            'type' => 'LEFT',
                            'conditions' => array(
                                'Adventurers.id = CharacterProgression.dnd_adventurers_id',
                            )
                        )
                    ),
                    'conditions' => array(
                        'dnd_adventurers_id' => $char,
                        'lvl' => $AdventurersPerAdventure[$char]['AdventurersPerAdventure']['lvl_inicial'],
                    ),
                    'fields' => array(
                        'CharacterProgression.*',
                        'Adventurers.name',
                        'Adventurers.race',
                        'Adventurers.class',
                        'Adventurers.player',
                        'Adventurers.background',
                    ),
                ));
                $pc[$char] = array_merge($pc[$char], $AdventurersPerAdventure[$char]);
            }

            $adventurers = $this->Adventurers->find('all', array('order' => 'id'));

//        debug($adventurers);
            $this->set('pc', $pc);
            $this->set(
                    'adventurers', $adventurers);
        }
    }

 

    function getListCharacters()
    {
        $this->autoRender = false;
        return json_encode($this->Adventurers->find("all"));
    }

    function salvarGrupo()
    {
        $this->autoRender = false;
        $data = explode('/', $this->params['url']['dataAventura']);

        if (!empty($data) && checkdate($data[1], $data[0], $data[2])) {
            $adventure['Adventure']['date'] = $this->params['url']['dataAventura'];
            if ($this->Adventure->save($adventure)) {
                $adventure = $this->Adventure->find('first', array('conditions' => array('date' => $this->params['url']['dataAventura'])));

                foreach ($this->params['url']['level'] as $key => $level) {
                    $adventurersPerAdventure = array();
                    $adventurersPerAdventure['AdventurersPerAdventure']['dnd_adventure_id'] = $adventure['Adventure']['id'];
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
        
    }

    function notes()
    {
        
    }

    function atualizacao()
    {
        
    }

}
