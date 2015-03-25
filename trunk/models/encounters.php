<?php

class Encounters extends AppModel
{

    var $name = 'Encounters';
    var $hasMany = array(
        'EncountersMonsters' => array(
            'className' => 'EncountersMonsters',
            'foreignKey' => 'dnd_encounters_id',
            'conditions' => '',
            'order' => '',
            'limit' => '',
            'dependent' => ''
        ),
    );
}

?>