<?php

class ClassesController extends AppController
{

    var $uses = array('Classes');

    function index()
    {

        $classes = $this->Classes->find('all', array(
            'order' => 'id'));
//        debug($classes);
        $this->set('classes', $classes);
    }

}
