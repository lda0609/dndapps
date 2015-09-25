<?php

class Alignment extends AppModel
{

    var $name = 'Alignment';

    function getLista()
    {
        return $this->find('list', array('fields' => 'shortname'));
    }

}
