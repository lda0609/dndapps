<?php

class MagicItems extends AppModel
{

    var $name = 'MagicItems';
    var $hasMany = array(
        'MagicArmorType' => array(
            'className' => 'MagicArmorType',
            'foreignKey' => 'dnd_magic_armor_id',
            'conditions' => '',
            'order' => '',
            'limit' => '',
            'dependent' => ''
        ),
    );

}

?>