<?php

class TesteController extends AppController
{

    var $uses = array('Monsters', 'xpThresholds', 'Adventure', 'AdventurersPerAdventure', 'Encounters', 'EncountersMonsters', 'MonsterFavorites');

    function index()
    {
        Configure::write('debug', 2);
        $this->Encounters->unBindModel(array('hasMany' => array('EncountersMonsters')));
        $encounters = $this->Encounters->find('all', array(
            'conditions' => array(
                'dnd_adventure_id' => '9',
            ),
            'fields' => array('Encounters.id', 'Encounters.xp', 'Encounters.difficulty')
        ));

        $sumMonsters = $this->EncountersMonsters->find('first', array(
            'conditions' => array(
                'dnd_encounters_id' => '51',
            ),
            'fields' => array('sum(EncountersMonsters.quantidade) AS encounter__total')
        ));

        debug($encounters);
        debug($sumMonsters);
    }

}
