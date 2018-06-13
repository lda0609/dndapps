<?php

class Environments extends AppModel
{

    var $name = 'Environments';

    function getLista()
    {
        return $this->find('list', array('fields' => 'name', 'order' => 'id'));
    }

}
