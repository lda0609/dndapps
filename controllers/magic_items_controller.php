<?php

class MagicItemsController extends AppController
{

    var $uses = array('MagicItems', 'MagicArmorType');

    function consulta()
    {
//        debug($this->data);

        if (!empty($this->data)) {
            $conditions = array();
            if (!empty($this->data['MagicItems']['name'])) {
                $conditions['MagicItems.name'] = $this->data['MagicItems']['name'];
            }
            if (!empty($this->data['MagicItems']['rarity'])) {
                $conditions['MagicItems.rarity'] = $this->data['MagicItems']['rarity'];
            }
            if (!empty($this->data['MagicItems']['type'])) {
                $conditions['MagicItems.type'] = $this->data['MagicItems']['type'];
            }
            if (!empty($this->data['MagicItems']['attunement'])) {
                $conditions['MagicItems.attunement'] = $this->data['MagicItems']['attunement'];
            }
            if (!empty($this->data['MagicItems']['cursed'])) {
                $conditions['MagicItems.cursed'] = $this->data['MagicItems']['cursed'];
            }
            $items = $this->MagicItems->find('all', array(
//                'joins' => array(
//                    array('table' => 'magic_armor_type',
//                        'alias' => 'MagicArmorType',
//                        'type' => 'LEFT',
//                        'conditions' => array(
//                            'MagicItems.id = MagicArmorType.dnd_magic_item_id',
//                        )
//                    )
//                ),
                'conditions' => $conditions,
                'fields' => array(
                    'id',
                    'name',
                    'rarity',
                    'type',
                    'attunement',
                    'cursed',
//                    'MagicArmorType.dnd_armors_id'
                    ),
                'order' => 'MagicItems.id'));

            $this->set('items', $items);
        }

        function create()
        {
            if (!empty($this->data) && !empty($this->data['MagicItems']['name'])) {
                $armor = $this->data['MagicItems']['armor'];
                unset($this->data['MagicItems']['armor']);
                if (isset($this->data['MagicItems']['Attunement'][0])) {
                    $this->data['MagicItems']['attunement'] = 1;
                }
                if (isset($this->data['MagicItems']['cursed'][0])) {
                    $this->data['MagicItems']['cursed'] = 1;
                }

                $this->MagicItems->save($this->data);

                if ($this->data['MagicItems']['type'] == 'armor') {
                    foreach ($armor as $key => $value) {
                        $magicArmorType['MagicArmorType'][$key]['dnd_armors_id'] = $value;
                        $magicArmorType['MagicArmorType'][$key]['dnd_magic_item_id'] = $this->MagicItems->id;
                    }
                    ($this->MagicArmorType->saveAll($magicArmorType['MagicArmorType']));
                }
            }
        }

    }

}
