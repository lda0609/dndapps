<?php

class Monsters extends AppModel
{

    var $name = 'Monsters';
    var $hasMany = array(
        'MonsterTypes' => array(
            'className' => 'MonsterTypes',
            'foreignKey' => 'dnd_monsters_id',
            'conditions' => '',
            'order' => '',
            'limit' => '',
            'dependent' => ''
        ),
    );

    function getLista()
    {
        return $this->find('list', array('fields' => 'name', 'order' => 'name'));
    }

    function getListaPorPage()
    {
        return $this->find('list', array('fields' => 'name', 'order' => array('book', 'page')));
    }

    function getMonster($id = null)
    {
        if (!is_null($id)) {
            return $this->find('first', array('conditions' => array('id' => $id)));
        }
        return false;
    }

    function teste($id = null)
    {
        return true;
    }

}

?>