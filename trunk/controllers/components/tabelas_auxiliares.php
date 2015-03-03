<?php

/**
 * Classe que carrega e controla todas as tabelas auxiliares no cache.
 * 
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *  
 * @filesource
 * @author 			Allan May <allanmay@celepar.pr.gov.br>
 * @license       	http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class TabelasAuxiliaresComponent extends Object
{

    function startup(&$controller)
    {
        //Domains
        if (($domains = Cache::read('dnd_domains')) === false) {
            $controller->loadModel('Domains');
            $domains = $controller->Domains->getLista();
            Cache::write('dnd_domains', $domains);
        }
        $controller->set('dnd_domains', $domains);

        //Gods
        if (($gods = Cache::read('dnd_gods')) === false) {
            $controller->loadModel('Gods');
            $gods = $controller->Gods->getLista();
            Cache::write('dnd_gods', $gods);
        }
        $controller->set('dnd_gods', $gods);

        //Monsters
        if (($monsters = Cache::read('dnd_monsters')) === false) {
            $controller->loadModel('Monsters');
            $monsters = $controller->Monsters->getLista();
            Cache::write('dnd_monsters', $monsters);
        }
        $controller->set('dnd_monsters', $monsters);

        //Monsters type
        if (($monsterType = Cache::read('dnd_monster_type')) === false) {
            $controller->loadModel('Type');
            $monsterType = $controller->Type->getLista();
            Cache::write('dnd_monster_type', $monsterType);
        }
        $controller->set('dnd_monster_type', $monsterType);


        //xp
        if (($xp = Cache::read('dnd_xp')) === false) {
            $controller->loadModel('Xp');
            $xp = $controller->Xp->getLista();
            Cache::write('dnd_xp', $xp);
        }
        $controller->set('dnd_xp', $xp);

        if (($dnd_alignment = Cache::read('dnd_alignment')) === false) {
            $dnd_alignment = array('CN' => 'CN', 'CG' => 'CG', 'CE' => 'CE', 'LN' => 'LN', 'LG' => 'LG', 'LE' => 'LE', 'NN' => 'NN', 'NE' => 'NE', 'NG' => 'NG',
                'Unaligned' => 'Unaligned', 'Any' => 'Any', 'Non-good' => 'Non-good', 'Non-lawful' => 'Non-lawful', 'Non-evil' => 'Non-evil', 'Non-chaotic' => 'Non-chaotic',
                'Any chaotic' => 'Any chaotic', 'Any lawful' => 'Any lawful', 'Any good' => 'Any good', 'Any evil' => 'Any evil');
            Cache::write('dnd_alignment', $dnd_alignment);
        }
        $controller->set('dnd_alignment', $dnd_alignment);

        if (($dnd_rarity = Cache::read('dnd_rarity')) === false) {
            $dnd_rarity = array('common' => 'common', 'uncommon' => 'uncommon', 'rare' => 'rare', 'very rare' => 'very rare', 'legendary' => 'legendary');
            Cache::write('dnd_rarity', $dnd_rarity);
        }
        $controller->set('dnd_rarity', $dnd_rarity);

        if (($dnd_item_type = Cache::read('dnd_item_type')) === false) {
            $dnd_item_type = array('armor' => 'armor', 'potion' => 'potion', 'ring' => 'ring', 'rod' => 'rod', 'scroll' => 'scroll', 'staff' => 'staff', 'wand' => 'wand', 'weapon' => 'weapon', 'woundros item' => 'woundrous item');
            Cache::write('dnd_item_type', $dnd_item_type);
        }
        $controller->set('dnd_item_type', $dnd_item_type);

        if (($dnd_armors = Cache::read('dnd_armors')) === false) {
            $controller->loadModel('Armors');
            $dnd_armors = $controller->Armors->getLista();
            Cache::write('dnd_armors', $dnd_armors);
        }
        $controller->set('dnd_armors', $dnd_armors);

        if (($dnd_classes = Cache::read('dnd_classes')) === false) {
            $controller->loadModel('Classes');
            $dnd_classes = $controller->Classes->getLista();
            Cache::write('dnd_classes', $dnd_classes);
        }
        $controller->set('dnd_classes', $dnd_classes);

        if (($dnd_skills = Cache::read('dnd_skills')) === false) {
            $controller->loadModel('Skills');
            $dnd_skills = $controller->Skills->getLista();
            Cache::write('dnd_skills', $dnd_skills);
        }
        $controller->set('dnd_skills', $dnd_skills);
    }

}

?>