<?php

class Adventure extends AppModel
{

    var $name = 'Adventure';
    var $hasMany = array(
        'AdventurersPerAdventure' => array(
            'className' => 'AdventurersPerAdventure',
            'foreignKey' => 'dnd_adventure_id',
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
