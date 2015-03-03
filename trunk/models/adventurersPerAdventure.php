<?php

class adventurersPerAdventure extends AppModel
{

    var $name = 'AdventurersPerAdventure';
    var $belongsTo = array(
        'Adventure' => array(
            'className' => 'Adventure',
            'foreignKey' => 'dnd_adventure_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );

}
