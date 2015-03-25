<?php

class EncountersMonsters extends AppModel
{

    var $name = 'EncountersMonsters';
    var $belongsTo = array(
        'Gods' => array(
            'className' => 'Encounters',
            'foreignKey' => 'dnd_encounters_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );

}

?>