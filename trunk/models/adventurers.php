<?php

class Adventurers extends AppModel
{

    var $name = 'Adventurers';
    var $hasMany = array(
        'AdventurersPerAdventure' => array(
            'className' => 'AdventurersPerAdventure',
            'foreignKey' => 'dnd_adventurers_id',
            'conditions' => '',
            'order' => '',
            'limit' => '',
            'dependent' => ''
        ),
        'AdventurersSkills' => array(
            'className' => 'AdventurersSkills',
            'foreignKey' => 'dnd_adventurers_id',
            'conditions' => '',
            'order' => '',
            'limit' => '',
            'dependent' => ''
        ),
    );

    function getGroupLvl()
    {
        
    }

}
