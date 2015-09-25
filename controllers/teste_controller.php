<?php

class TesteController extends AppController
{

    var $uses = array('Monsters', 'xpThresholds', 'Adventure', 'AdventurersPerAdventure', 'Encounters', 'EncountersMonsters', 'MonsterFavorites');

    function index()
    {
        $this->autoRender = false;
        $encontros = $this->Encounters->find('first', array('conditions' => array('id' => '51')));

        $cont = 0;
        foreach ($encontros['EncountersMonsters'] as $key => $encounterMonster) {
            debug($encounterMonster);
            $monster = $this->Monsters->find('first', array('conditions' => array('id' => $encounterMonster['dnd_monsters_id'])));
            for ($i = 1; $i <= $encounterMonster['quantidade']; $i++) {
                $monsterList[$cont]['name'] = $monster['Monsters']['name'] . ' (' . $i . ')';
                $monsterList[$cont]['hp'] = $monster['Monsters']['hp'];
                $cont++;
            }
        }
        debug($monsterList);
    }

}
