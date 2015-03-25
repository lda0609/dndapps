<?php

class MonstersController extends AppController
{

    var $uses = array('Monsters', 'MonsterType');
//    var $helpers = array('Paginator');
    var $paginate = array(
        'limit' => 30,
        'order' => array('Monsters.cr' => 'asc'),
    );

    function index()
    {
        $monsterList = $this->Monsters->find('all', array(
            'order' => 'cr'));
        $this->set('monsterList', $monsterList);
    }

    function monsters()
    {

        if (!empty($this->data)) {

            $monsterType = $this->MonsterType->find('list', array(
                'conditions' => array('dnd_monsters_id' => $this->data['monsters']['monster']),
                'fields' => array('dnd_type_id'),
                'order' => 'dnd_type_id'));
            $this->set('monsterType', $monsterType);

            $this->set('monster', $this->data['monsters']['monster']);
            $this->render('monster_type');
        }
    }

    function update($monsterId = null)
    {
        $monster = $this->Monsters->find('first', array(
            'conditions' => array('size' => null),
            'order' => 'page'));

        $monsterType = $this->MonsterType->find('list', array(
            'conditions' => array('dnd_monsters_id' => $monster['Monsters']['id']),
            'fields' => array('dnd_type_id'),
            'order' => 'dnd_type_id'));
        $this->set('monsterType', $monsterType);
        $this->set('monster', $monster);
    }

    function save()
    {
        $monsterType = $this->MonsterType->find('list', array(
            'conditions' => array('dnd_monsters_id' => $this->data['monster']['monster']),
            'fields' => array('dnd_type_id'),
            'order' => 'dnd_type_id'));

        $addType = array_diff($this->data['monster']['monsterType'], $monsterType);
        $removeType = array_diff($monsterType, $this->data['monster']['monsterType']);
        foreach ($addType as $key => $typeId) {
            $newDomains['MonsterType']['id'] = '';
            $newDomains['MonsterType']['dnd_monsters_id'] = $this->data['monster']['monster'];
            $newDomains['MonsterType']['dnd_type_id'] = $typeId;
            $this->MonsterType->save($newDomains);
        }

        foreach ($removeType as $key => $typeId) {
            $deleteDomains = $this->MonsterType->find('first', array(
                'conditions' => array(
                    'dnd_monsters_id' => $this->data['monster']['monster'],
                    'dnd_type_id' => $typeId),
                'fields' => array('id'),
            ));
            $this->MonsterType->delete($deleteDomains['MonsterType']['id']);
        }

        $this->Monsters->id = $this->data['monster']['monster'];
        $this->Monsters->save(array(
            'size' => $this->data['monster']['size'],
            'alignment' => $this->data['monster']['alignment'],
                )
        );
        $this->redirect('update');
    }

    function consulta()
    {
        if (!empty($this->data)) {
            $conditions = array();
            if (!empty($this->data['Monsters']['name'])) {
                $conditions['Monsters.name LIKE'] = '%' . $this->data['Monsters']['name'] . '%';
            }
            if (!empty($this->data['Monsters']['CRMin'])) {
                $conditions['Monsters.cr >='] = $this->data['Monsters']['CRMin'];
            }
            if (!empty($this->data['Monsters']['CRMax'])) {
                $conditions['Monsters.cr <='] = $this->data['Monsters']['CRMax'];
            }
            if (!empty($this->data['Monsters']['Type'])) {
                $conditions['MonsterType.dnd_type_id'] = $this->data['Monsters']['Type'];
            }
            if (!empty($this->data['Monsters']['Alignment'])) {
                $conditions['Monsters.alignment'] = $this->data['Monsters']['alignment'];
            }
            $monsters = $this->Monsters->find('all', array(
                'joins' => array(
                    array('table' => 'monster_types',
                        'alias' => 'MonsterType',
                        'type' => 'LEFT',
                        'conditions' => array(
                            'Monsters.id = MonsterType.dnd_monsters_id',
                        )
                    )
                ),
                'conditions' => $conditions,
                'order' => 'cr'));

            $this->set('monsters', $monsters);
        }
    }

    function search()
    {
        $this->autoRender = false;
        $params = $this->params['url'];

        $conditions = array();
        if (!empty($params['monsterName'])) {
            $conditions['Monsters.name LIKE'] = '%' . $params['monsterName'] . '%';
        }
        if (!empty($params['crMin'])) {
            $conditions['Monsters.cr >='] = $params['crMin'];
        }
        if (!empty($params['crMax'])) {
            $conditions['Monsters.cr <='] = $params['crMax'];
        }
        if (!empty($params['type'])) {
            $conditions['MonsterType.dnd_type_id'] = $params['type'];
        }
        if (!empty($params['alignment'])) {
            $conditions['Monsters.alignment'] = $params['alignment'];
        }

        $monsters = $this->Monsters->find('all', array(
            'joins' => array(
                array('table' => 'monster_types',
                    'alias' => 'MonsterType',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Monsters.id = MonsterType.dnd_monsters_id',
                    ),
                    'order' => 'id'
                )
            ),
            'conditions' => $conditions,
            'order' => 'cr'));
        foreach ($monsters as $key => $monster) {
            $monsters[$key]['MonsterTypes'][0]['dnd_type_id'] = $this->viewVars['dnd_monster_type'][$monster['MonsterTypes'][0]['dnd_type_id']];
        }

        return json_encode($monsters);
    }

}