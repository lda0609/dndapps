<?php

class Xp extends AppModel
{

    var $name = 'Xp';
    var $primaryKey = 'cr_id';
    
    function getLista()
    {
        return $this->find('list', array('fields'=>'xp'));
    }

}

?>