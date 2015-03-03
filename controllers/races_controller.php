<?php

class RacesController extends AppController
{

    var $uses = array('Races');

    function index()
    {

        $races = $this->Races->getRacesComplete();
//        debug($race['HillDwarf']);

        $this->set('races', $races);
    }

}
