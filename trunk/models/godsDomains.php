<?php

class GodsDomains extends AppModel
{

    var $name = 'GodsDomains';
    var $belongsTo = array(
        'Gods' => array(
            'className' => 'Gods',
            'foreignKey' => 'dnd_gods_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );

}

?>