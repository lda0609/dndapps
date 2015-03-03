<?php

class Classes extends AppModel
{

    var $name = 'Classes';

    function getLista()
    {
        return $this->find('list', array('order' => 'id'));
    }

}
