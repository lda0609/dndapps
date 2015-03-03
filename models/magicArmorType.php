<?php

class MagicArmorType extends AppModel
{
    var $name = 'MagicArmorType';
    var $belongsTo = array(
        'MagicItems' => array(
            'className' => 'MagicItems',
            'foreignKey' => 'dnd_magic_item_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );

}

?>