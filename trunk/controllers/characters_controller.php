<?php

class CharactersController extends AppController
{

    var $uses = array('Alignment', 'CharacterProgression', 'Adventurers', 'AdventurersPerAdventure', 'AdventurersSkills', 'Adventure');
    var $xp_threshold = [0, 300, 900, 2700, 6500, 14000, 23000, 34000, 48000, 64000, 85000, 100000, 120000, 140000, 165000, 195000, 225000, 265000, 305000, 355000];

    function index()
    {
        
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

            $AdventurersSkills = $this->AdventurersSkills->find('all', array(
                'conditions' => array(
                    'dnd_adventurers_id' => $value['AdventurersPerAdventure']['dnd_adventurers_id']
            )));
            $adventurers[$key]['AdventurersSkills'] = $AdventurersSkills;
        }
        return json_encode($adventurers);
    }

    function getListCharacters()
    {
        $this->autoRender = false;

        $aventuraAnterior = $this->Adventure->find('list', array('fields' => 'id'));
        if (!empty($aventuraAnterior)) {
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
        }
        $adventurers = $this->Adventurers->find("all");
        return json_encode($adventurers);
    }

    function salvarGrupo()
    {
        $this->autoRender = false;
        $data = explode('/', $this->params['url']['dataAventura']);

        if (!empty($data) && checkdate($data[1], $data[0], $data[2])) {
            $adventure['Adventure']['date'] = $this->params['url']['dataAventura'];
            if ($this->Adventure->save($adventure)) {
                foreach ($this->params['url']['level'] as $key => $level) {
                    $adventurerId = substr($this->params['url']['aventureirosID'][$key], 11);

                    $adventurersPerAdventure = array();
                    $adventurersPerAdventure['AdventurersPerAdventure']['dnd_adventure_id'] = $this->Adventure->id;
                    $adventurersPerAdventure['AdventurersPerAdventure']['dnd_adventurers_id'] = $adventurerId;
                    $adventurersPerAdventure['AdventurersPerAdventure']['lvl_inicial'] = $level;
                    $adventurersPerAdventure['AdventurersPerAdventure']['ausente'] = 0;
                    if (!empty($this->params['url']['ausencia'])) {
                        if (in_array($this->params['url']['aventureirosID'][$key], $this->params['url']['ausencia'])) {
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
        if (!empty($aventuraAnterior)) {
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
        }
        foreach ($this->params['url']['xp'] as $key => $value) {
            $adventurerId = substr($this->params['url']['aventureirosID'][$key], 11);

            $AdventurerPerAdventure = $this->AdventurersPerAdventure->find('first', array('conditions' => array(
                    'dnd_adventure_id' => $this->params['url']['idAventura'],
                    'dnd_adventurers_id' => $adventurerId
            )));
            if (empty($value)) {
                $value = 0;
            }
            if (isset($xpAnterior)) {
                $AdventurerPerAdventure['AdventurersPerAdventure']['xp_final'] = $xpAnterior[$adventurerId] + $value;
            } else {
                $AdventurerPerAdventure['AdventurersPerAdventure']['xp_final'] = $value;
            }
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
        $adventurerSkills['AdventurersSkills'] = $character['AdventurersSkills'];

        if ($this->Adventurers->save($adventurer)) {
            $progression['CharacterProgression']['dnd_adventurers_id'] = $this->Adventurers->id;
            if ($this->CharacterProgression->save($progression)) {
                foreach ($adventurerSkills['AdventurersSkills'] as $key => $value) {
                    $skills[$key]['AdventurersSkills']['dnd_adventurers_id'] = $this->Adventurers->id;
                    $skills[$key]['AdventurersSkills']['dnd_skills_id'] = $value['dnd_skills_id'];
                    $skills[$key]['AdventurersSkills']['modifier'] = $value['modifier'];
                }
                $this->AdventurersSkills->saveAll($skills);
                return json_encode('ok');
            } else {
                return json_encode('nok 2');
            }
        } else {
            return json_encode('nok 1');
        }
    }

    function characterLevelUp()
    {
        $this->autoRender = false;
        $skillsUpdated = '';
        $character = ($this->params['url']['adventurer']);
        $progression['CharacterProgression'] = $character['CharacterProgression'];
        $adventurer['Adventurers'] = $character['Adventurers'];
        $adventurerSkillsUpdated['AdventurersSkills'] = $character['AdventurersSkills'];
        $adventurer = $this->Adventurers->find('first', array('conditions' => array('name' => $adventurer['Adventurers']['name'])));
        $progression['CharacterProgression']['dnd_adventurers_id'] = $adventurer['Adventurers']['id'];
        if ($this->CharacterProgression->save($progression)) {
            $this->saveSkills($adventurerSkillsUpdated, $adventurer['Adventurers']['id']);
            return json_encode('ok');
        } else {
            return json_encode('nok 2');
        }
    }

    function editCharacter()
    {
        $this->autoRender = false;
        $character = ($this->params['url']['adventurer']);
        $adventurer['Adventurers'] = $character['Adventurers'];
        $progression['CharacterProgression'] = $character['CharacterProgression'];
        $adventurerSkillsUpdated['AdventurersSkills'] = $character['AdventurersSkills'];
        $adventurerId = $this->Adventurers->find('first', array('conditions' => array('name' => $adventurer['Adventurers']['name'])));
        $this->Adventurers->id = $adventurerId['Adventurers']['id'];
        if ($this->Adventurers->save($adventurer)) {
            $progression['CharacterProgression']['dnd_adventurers_id'] = $this->Adventurers->id;
            $characterProgressionId = $this->CharacterProgression->find('first', array('conditions' => array('dnd_adventurers_id' => $this->Adventurers->id, 'lvl' => $progression['CharacterProgression']['lvl'])));
            $this->CharacterProgression->id = $characterProgressionId['CharacterProgression']['id'];
            if ($this->CharacterProgression->save($progression)) {
                $this->saveSkills($adventurerSkillsUpdated, $this->Adventurers->id);
                return json_encode('ok');
            } else {
                return json_encode('nok 2');
            }
        } else {
            return json_encode('nok 1');
        }
    }

    private function saveSkills($adventurerSkillsUpdated, $adventurerId)
    {
        $adventurerSkills = $this->AdventurersSkills->find('all', array('conditions' => array('dnd_adventurers_id' => $adventurerId)));
        foreach ($adventurerSkillsUpdated['AdventurersSkills'] as $key => $newSkill) {
            foreach ($adventurerSkills as $key => $skillSaved) {
                if ($newSkill['dnd_skills_id'] == $skillSaved['AdventurersSkills']['dnd_skills_id']) {
                    if ($newSkill['modifier'] != $skillSaved['AdventurersSkills']['modifier']) {
                        $skillsUpdated[$key]['AdventurersSkills']['id'] = $skillSaved['AdventurersSkills']['id'];
                        $skillsUpdated[$key]['AdventurersSkills']['modifier'] = $newSkill['modifier'];
                    }
                }
            }
        }
        $this->AdventurersSkills->saveAll($skillsUpdated);
    }

    function getCharactersAllLevels()
    {
        $this->autoRender = false;

        $adventurers = $this->Adventurers->find('all', array('order' => 'id'));
        $retorno = array();
        foreach ($adventurers as $key => $adventurer) {
            $characterProgression = $this->CharacterProgression->find('all', array(
                'conditions' => array(
                    'dnd_adventurers_id' => $adventurer['Adventurers']['id']
                ),
                'order' => 'id',
            ));
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
        $retorno['AdventurersSkills'] = $adventurers['AdventurersSkills'];

        return json_encode($retorno);
    }

}
