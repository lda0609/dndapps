<?php

class Skills extends AppModel
{

    var $name = 'Skills';
    var $hasMany = array(
        'AdventurersSkills' => array(
            'className' => 'AdventurersSkills',
            'foreignKey' => 'dnd_skills_id',
            'conditions' => '',
            'order' => '',
            'limit' => '',
            'dependent' => ''
        ),
    );

    function getLista()
    {
        return $this->find('list', array('fields' => 'name', 'order' => 'id'));
    }

}

?>