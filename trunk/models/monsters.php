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
//        var $hasOne = array(
//        'Xp' => array(
//            'className' => 'Xp',
//            'foreignKey' => 'xp_id',
//            'conditions' => '',
//            'order' => '',
//            'limit' => '',
//            'dependent' => ''
//        ),
//    );


    function getLista()
    {
        return $this->find('list', array('fields' => 'name', 'order' => 'name'));
    }

}

?>