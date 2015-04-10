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

}

?>