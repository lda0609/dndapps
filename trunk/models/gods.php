<?php

class Gods extends AppModel
{

    var $name = 'Gods';
    var $hasMany = array(
        'GodsDomains' => array(
            'className' => 'GodsDomains',
            'foreignKey' => 'dnd_gods_id',
            'conditions' => '',
            'order' => '',
            'limit' => '',
            'dependent' => ''
        ),
    );

    function getLista()
    {
        return $this->find('list', array('order' => 'name'));
    }

}

?>