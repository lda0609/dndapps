<?php

class SpellsController extends AppController {

    var $uses = array('Spells', 'SpellsClasses');

    function index() {
        
    }

    function cadastro($classe = null) {
        if (is_null($classe)) {
            if (!empty($this->data)) {
                if ($this->Spells->save($this->data)) {
                    $this->flash($this->data['Spells']['name'] . ' - salvo com sucesso', 'cadastro');
                } else {
                    $this->flash($this->data['Spells']['name'] . ' - erro ao gravar');
                }
//                $this->render('cadastro');
            }
        } else {
            if (empty($this->data)) {
                switch ($classe) {
                    case 'cleric':
                    case '3':
                        $spellList = $this->Spells->find('list', array(
                            'fields' => array('Spells.id', 'Spells.name'),
                            'order' => array('lvl', 'name'),
                        ));
                        $classSpells = $this->SpellsClasses->find('list', array(
                            'fields' => array('dnd_spells_id'),
                            'conditions' => array('dnd_classes_id' => 3)
                        ));
                        $this->set('classSpells', $classSpells);
                        $this->set('classId', '3');
                        break;
                    case 'ranger':
                    case '8':
                        $spellList = $this->Spells->find('list', array(
                            'fields' => array('Spells.id', 'Spells.name'),
                            'order' => array('lvl', 'name'),
                        ));

                        $classSpells = $this->SpellsClasses->find('list', array(
                            'fields' => array('dnd_spells_id'),
                            'conditions' => array('dnd_classes_id' => 8)
                        ));

                        $this->set('classSpells', $classSpells);
                        $this->set('classId', '8');
                        break;
                    case 'sorcerer':
                    case '10':
                        $spellList = $this->Spells->find('list', array(
                            'fields' => array('Spells.id', 'Spells.name'),
                            'order' => array('lvl', 'name'),
                        ));
                        $classSpells = $this->SpellsClasses->find('list', array(
                            'fields' => array('dnd_spells_id'),
                            'conditions' => array('dnd_classes_id' => 10),
                        ));
                        $this->set('classSpells', $classSpells);
                        $this->set('classId', '10');
                        break;
                    case 'wizard':
                    case '12':
                        $spellList = $this->Spells->find('list', array(
                            'fields' => array('Spells.id', 'Spells.name'),
                            'order' => array('lvl', 'name'),
                        ));
                        $classSpells = $this->SpellsClasses->find('list', array(
                            'fields' => array('dnd_spells_id'),
                            'conditions' => array('dnd_classes_id' => 12)
                        ));
                        $this->set('classSpells', $classSpells);
                        $this->set('classId', '12');
                        break;


                    default:
                        break;
                }
                $this->set('spellList', $spellList);
                $this->render('spells_classe');
            } else {
                switch ($classe) {
                    case 'cleric':
                        $spellList = $this->Spells->find('list', array(
                            'fields' => array('Spells.id', 'Spells.name'),
                            'order' => 'name',
                        ));
                        break;

                    default:
                        break;
                }
            }
        }
    }

    function consulta($classe = 'null', $shortlist = 0) {
        $this->set('shortlist', $shortlist);
        switch ($classe) {
            case 'cleric':
            case '3':
                $class_id = '3';
                $lvl_ini = 0;
                $lvl_fim = 3;
                $conditions = array(
                    'SpellsClasses.dnd_classes_id' => $class_id,
                );
                $this->set('class', 'Cleric');
                break;
            case 'ranger':
            case '8':
                $class_id = '8';
                $lvl_ini = 1;
                $lvl_fim = 2;
                $conditions = array(
                    'SpellsClasses.dnd_classes_id' => $class_id,
                );
                $this->set('class', 'Ranger');
                break;
            case 'sorcerer':
            case '10':
                $class_id = '10';
                $lvl_ini = 0;
                $lvl_fim = 3;
                if ($shortlist) {
                    $conditions = array(
                        'SpellsClasses.dnd_classes_id' => $class_id,
                        'Spells.id' => array('65', '71', '73', '74', '67', '93', '92', '76', '119', '111'));
                } else {
                    $conditions = array(
                        'SpellsClasses.dnd_classes_id' => $class_id,
                    );
                }
                $this->set('class', 'Sorcerer');
                break;
            case 'wizard':
            case '12':
                $class_id = '12';
                $lvl_ini = 0;
                $lvl_fim = 3;
                $conditions = array(
                    'SpellsClasses.dnd_classes_id' => $class_id,
                );
                $this->set('class', 'Wizard');
                break;

            default:
                $spellList = $this->Spells->find('all');
                break;
        }

        for ($lvl = $lvl_ini; $lvl <= $lvl_fim; $lvl++) {
            $this->SpellsClasses->recursive = -1;
            $spellList[$lvl] = $this->SpellsClasses->find('all', array(
                'joins' => array(
                    array('table' => 'spells',
                        'alias' => 'Spells',
                        'type' => 'RIGHT',
                        'conditions' => array(
                            'Spells.id = SpellsClasses.dnd_spells_id',
                            'Spells.lvl' => $lvl,
                        )
                    )
                ),
                'conditions' => $conditions,
                'fields' => array(
                    'SpellsClasses.*',
                    'Spells.name',
                    'Spells.school',
                    'Spells.lvl',
                    'Spells.casting_time',
                    'Spells.range',
                    'Spells.components',
                    'Spells.duration',
                    'Spells.description',
                    'Spells.ritual',
                    'Spells.cleric_domain',
                ),
                'order' => array('Spells.name'),
            ));
        }
        $this->set('spellList', $spellList);
    }

    function saveClassSpells() {
        $spellsClasses = $this->SpellsClasses->find('list', array(
            'conditions' => array('dnd_classes_id' => $this->data['spells']['classId']),
            'fields' => array('dnd_spells_id'),
        ));
        $addDomain = array_diff($this->data['spells']['spells'], $spellsClasses);
        $removeDomain = array_diff($spellsClasses, $this->data['spells']['spells']);
        foreach ($addDomain as $key => $domainId) {
            $newDomains['SpellsClasses']['id'] = '';
            $newDomains['SpellsClasses']['dnd_classes_id'] = $this->data['spells']['classId'];
            $newDomains['SpellsClasses']['dnd_spells_id'] = $domainId;
            $this->SpellsClasses->save($newDomains);
        }
        foreach ($removeDomain as $key => $domainId) {
            $deleteDomains = $this->SpellsClasses->find('first', array(
                'conditions' => array(
                    'dnd_classes_id' => $this->data['spells']['classId'],
                    'dnd_spells_id' => $domainId),
                'fields' => array('id'),
            ));
            $this->SpellsClasses->delete($deleteDomains['SpellsClasses']['id']);
        }
        $this->redirect('cadastro/' . $this->data['spells']['classId']);
    }

}
