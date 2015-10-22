<?php

class MonsterTypes extends AppModel
{

    var $name = 'MonsterTypes';
    var $belongsTo = array(
        'Monsters' => array(
            'className' => 'Monsters',
            'foreignKey' => 'dnd_monsters_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );
}

?>