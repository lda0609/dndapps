<?php

class characterProgression extends AppModel
{

    var $name = 'CharacterProgression';
    public $hasOne = array(
        'Adventurers' => array(
            'className' => 'Adventurers',
            'dependent' => true
        )
    );

}
