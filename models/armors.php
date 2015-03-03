<?php

class Armors extends AppModel
{
    var $name = 'Armors';

    function getLista()
    {
        return $this->find('list', array('order' => 'id'));
    }

}

?>