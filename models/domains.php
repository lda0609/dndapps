<?php

class Domains extends AppModel
{

    var $name = 'Domains';
    var $hasMany = array(
        'GodsDomains' => array(
            'className' => 'GodsDomains',
            'foreignKey' => 'dnd_domains_id',
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