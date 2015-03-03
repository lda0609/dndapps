<?php

class Type extends AppModel
{

    var $name = 'Type';
    var $hasMany = array(
        'MonsterTypes' => array(
            'className' => 'MonsterTypes',
            'foreignKey' => 'dnd_type_id',
            'conditions' => '',
            'order' => '',
            'limit' => '',
            'dependent' => ''
        ),
    );

    function getLista()
    {
        return $this->find('list', array('fields'=>'name', 'order' => 'id'));
    }

}

?>