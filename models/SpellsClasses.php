<?php

class SpellClasses extends AppModel
{
    var $name = 'SpellClasses';
    var $belongsTo = array(
        'Classes' => array(
            'className' => 'Classes',
            'foreignKey' => 'dnd_classes_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );

}

?>