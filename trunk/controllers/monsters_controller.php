<?php

class MonstersController extends AppController
{

    var $uses = array('Monsters', 'MonsterTypes', 'MonsterFavorites', 'MonsterEnvironments');

    //****************************************//
    //**** FUNÇÕES COM VIEWS RELACIONADAS ****//
    //****************************************//
    function index()
    {
        $monsterList = $this->Monsters->getListaPorPage();
        $this->set('monsterList', $monsterList);

        if (!empty($this->data)) {
            $id = $this->data['Monsters']['id'];
            $monster = $this->Monsters->getMonster($id);

            $monsterType = $this->MonsterTypes->find('list', array(
                'conditions' => array('dnd_monsters_id' => $id),
                'fields' => array('dnd_type_id'),
            ));

            debug($this->MonsterEnvironments->find('all'));
            $monsterEnv = $this->MonsterEnvironments->find('list', array(
                'conditions' => array('dnd_monsters_id' => $id),
                'fields' => array('dnd_environments_id'),
            ));

            $this->set('monster', $monster);
            $this->set('monsterType', $monsterType);
            $this->set('monsterEnv', $monsterEnv);
        }
    }

    function update()
    {
        $this->autoRender = false;
        debug($this->data);
        $monsterType = $this->MonsterTypes->find('list', array(
            'conditions' => array('dnd_monsters_id' => $this->data['Monsters']['id']),
            'fields' => array('dnd_type_id')
        ));

        //Update Monster Types
        if (is_array($this->data['MonsterTypes']['dnd_type_id'])) {
            $addType = array_diff($this->data['MonsterTypes']['dnd_type_id'], $monsterType);
            debug($addType);
            $removeType = array_diff($monsterType, $this->data['MonsterTypes']['dnd_type_id']);
            debug($removeType);

            foreach ($addType as $key => $type) {
                $newType['MonsterTypes']['dnd_monsters_id'] = $this->data['Monsters']['id'];
                $newType['MonsterTypes']['dnd_type_id'] = $type;
                debug($newType);
                $this->MonsterTypes->create();
                $this->MonsterTypes->save($newType);
            }

            foreach ($removeType as $key => $type) {
                $deleteType = $this->MonsterTypes->find('first', array(
                    'conditions' => array(
                        'dnd_monsters_id' => $this->data['Monsters']['id'],
                        'dnd_type_id' => $type),
                    'fields' => array('id'),
                ));
                debug($deleteType);
                $this->MonsterTypes->delete($deleteType['MonsterTypes']['id']);
            }
        }

        //Update Monster Environments
        $monsterEnv = $this->MonsterEnvironments->find('list', array(
            'conditions' => array('dnd_monsters_id' => $this->data['Monsters']['id']),
            'fields' => array('dnd_environments_id')
        ));

        if (is_array($this->data['MonsterEnvironments']['dnd_environments_id'])) {

            $addEnv = array_diff($this->data['MonsterEnvironments']['dnd_environments_id'], $monsterEnv);
            debug($addEnv);
            $removeEnv = array_diff($monsterEnv, $this->data['MonsterEnvironments']['dnd_environments_id']);
            debug($removeEnv);
            foreach ($addEnv as $key => $env) {
                $newEnv['MonsterEnvironments']['dnd_monsters_id'] = $this->data['Monsters']['id'];
                $newEnv['MonsterEnvironments']['dnd_environments_id'] = $env;
                debug($newEnv);
                $this->MonsterEnvironments->create();
                $this->MonsterEnvironments->save($newEnv);
            }

            foreach ($removeEnv as $key => $env) {
                $deleteEnv = $this->MonsterEnvironments->find('first', array(
                    'conditions' => array(
                        'dnd_monsters_id' => $this->data['Monsters']['id'],
                        'dnd_environments_id' => $env),
                    'fields' => array('id'),
                ));
                debug($deleteEnv);
                $this->MonsterEnvironments->delete($deleteEnv['MonsterEnvironments']['id']);
            }
        }

        $this->redirect('index');
    }

    function lista()
    {
        $monsterList = $this->Monsters->find('all', array(
            'order' => 'cr'));
        $this->set('monsterList', $monsterList);
    }

    function cadastro()
    {
        if (!empty($this->data)) {
            foreach ($this->data['MonsterTypes']['dnd_type_id'] as $key => $value) {
                $temp[$key]['dnd_type_id'] = $value;
            }
            $this->data['MonsterTypes'] = $temp;
            if ($this->Monsters->saveAll($this->data)) {
                $this->redirect('cadastro');
            }
        }
    }

    //***********************************//
    //**** FUNÇÕES QUE RETORNAM JSON ****//
    //***********************************//

    function search()
    {
        $this->autoRender = false;
        $params = $this->params['url'];
        $favorites = $this->MonsterFavorites->find('list', array('fields' => 'dnd_monsters_id'));

        if ($params['favoritos'] != '1') {
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
            if (!empty($params['type']) || $params['type'] == '0') {
                $conditions['MonsterType.dnd_type_id'] = $params['type'];
            }
            if (!empty($params['environment']) || $params['environment'] == '0') {
                $conditions['MonsterEnvironments.dnd_environments_id'] = $params['environment'];
            }
            if (!empty($params['alignment'])) {
                $conditions['Monsters.alignment'] = $params['alignment'];
            }
        } else {
            $conditions['Monsters.id'] = $favorites;
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
                ),
                array('table' => 'monster_environments',
                    'alias' => 'MonsterEnvironments',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Monsters.id = MonsterEnvironments.dnd_monsters_id',
                    ),
                    'order' => 'id'
                ),
            ),
            'conditions' => $conditions,
            'order' => 'cr'));


        foreach ($monsters as $key => $monster) {
            $monsters[$key]['MonsterTypes'][0]['dnd_type_id'] = $this->viewVars['dnd_monster_type'][$monster['MonsterTypes'][0]['dnd_type_id']];
            if (in_array($monster['Monsters']['id'], $favorites)) {
                $monsters[$key]['MonsterFavorites'] = '1';
            } else {
                $monsters[$key]['MonsterFavorites'] = '0';
            }
        }

        $monsters = $this->limparResultado($monsters);
        return json_encode($monsters);
    }

    function limparResultado($monsters)
    {
        $this->autoRender = false;
        $list_ids = array();
        foreach ($monsters as $key => $monster) {
            if (!in_array($monster['Monsters']['id'], $list_ids)) {
                $list_ids[] = $monster['Monsters']['id'];
            } else {
                unset($monsters[$key]);
            }
        }
        return array_values($monsters);
    }

//    function importar()
//    {
//        $this->autoRender = false;
//
//        $monsters = file('files/monsters.csv');
//        foreach ($monsters as $key => $monster) {
//            $monsters_parsed[$key] = str_getcsv($monster, ';');
//        }
////        debug($monsters_parsed);
//
//        foreach ($monsters_parsed as $key => $monster_parsed) {
//
//            $monsters_to_save = $this->Monsters->find('first', array(
//                'conditions' => array(
//                    'name' => trim($monster_parsed[0])
//                )
//            ));
////            debug($monsters_to_save);
////            break;
//            if (!empty($monsters_to_save)) {
//                $monsters_to_save['Monsters']['ac'] = trim($monster_parsed[3]);
//                $monsters_to_save['Monsters']['armor'] = trim($monster_parsed[4]);
//                $monsters_to_save['Monsters']['hp_dice'] = trim($monster_parsed[6]);
//                $monsters_to_save['Monsters']['speed'] = trim($monster_parsed[7]);
//                $monsters_to_save['Monsters']['str'] = trim($monster_parsed[8]);
//                $monsters_to_save['Monsters']['dex'] = trim($monster_parsed[9]);
//                $monsters_to_save['Monsters']['con'] = trim($monster_parsed[10]);
//                $monsters_to_save['Monsters']['int'] = trim($monster_parsed[11]);
//                $monsters_to_save['Monsters']['wis'] = trim($monster_parsed[12]);
//                $monsters_to_save['Monsters']['cha'] = trim($monster_parsed[13]);
//                $monsters_to_save['Monsters']['saves'] = trim($monster_parsed[14]);
//                $monsters_to_save['Monsters']['skills'] = trim($monster_parsed[15]);
//                $monsters_to_save['Monsters']['resistences'] = trim($monster_parsed[16]);
//                $monsters_to_save['Monsters']['damage_immunities'] = trim($monster_parsed[17]);
//                $monsters_to_save['Monsters']['conditions_immunities'] = trim($monster_parsed[18]);
//                $monsters_to_save['Monsters']['senses'] = trim($monster_parsed[19]);
//                $monsters_to_save['Monsters']['languages'] = trim($monster_parsed[20]);
//                $monsters_to_save['Monsters']['actions'] = trim($monster_parsed[23]);
//                $monsters_to_save['Monsters']['legendary_actions'] = trim($monster_parsed[24]);
//                $monsters_to_save['Monsters']['abilities'] = trim($monster_parsed[25]);
//            } else {
//                CakeLog::write('error', 'NOT FOUND: ' . $monster_parsed[0]);
//            }
//            debug($monsters_to_save);
//            if ($this->Monsters->save($monsters_to_save)) {
//                CakeLog::write('debug', 'sucesso: ' . $monster_parsed[0]);
//            } else {
//                CakeLog::write('error', 'ERRO DE GRAVAÇÃO: ' . $monster_parsed[0]);
//            }
//        }
//    }

}
