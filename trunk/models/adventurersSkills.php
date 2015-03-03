<?php

class AdventurersSkills extends AppModel
{

    var $name = 'AdventurersSkills';
    var $belongsTo = array(
        'Adventurers' => array(
            'className' => 'Adventurers',
            'foreignKey' => 'dnd_adventurers_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );

}

?>