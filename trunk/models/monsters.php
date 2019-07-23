<?php

class Monsters extends AppModel
{

    var $name = 'Monsters';

    function getLista()
    {
        return $this->find('list', array('fields' => 'name', 'order' => 'name'));
    }

    function getListaPorPage()
    {
        return $this->find('list', array('fields' => 'name', 'order' => array('book', 'page')));
    }

    function getMonster($id = null)
    {
        if (!is_null($id)) {
            return $this->find('first', array('conditions' => array('id' => $id)));
        }
        return false;
    }

    function teste($id = null)
    {
        return true;
    }

}