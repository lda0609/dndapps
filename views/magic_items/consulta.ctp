<link rel="stylesheet" href="/cdw/css/redmond/jquery-ui-1.10.4.custom.css" type="text/css"/> 
<script type="text/javascript" src="/<?php echo APP_DIR ?>/js/jquery-1.7.2.js"></script>
<script type="text/javascript" src="/<?php echo APP_DIR ?>/js/jquery.meio.mask.min.js"></script>
<script type="text/javascript" src="/<?php echo APP_DIR ?>/js/jquery-ui-1.10.4.min.js"></script>

<style type="text/css">
    input, textarea {
        font-size: 100%;
        padding: 0.5%;
    }
</style>


<div id="conteudo_corpo">
    <?php echo $this->Form->create('MagicItems', array('action' => 'consulta')); ?>
    <table class="form_tabela" cellspacing="2">
        <tbody>
            <?php
            foreach ($dnd_xp as $cr => $xp) {
                $cr_list[$cr] = $cr;
            }

            echo $this->Form->input('name', array('id' => 'monsterName', 'type' => 'text'));
            echo '<tr><td>' . $this->Form->input('rarity', array('type' => 'select', 'options' => $dnd_rarity, 'empty' => 'Any', 'id' => 'crMin')) . '</td>';
            echo '<td>' . $this->Form->input('type', array('type' => 'select', 'options' => $dnd_item_type, 'empty' => 'Any', 'id' => 'crMax')) . '</td>';
            echo '<td>' . $this->Form->input('attunement', array('type' => 'select', 'options' => array('1' => 'Yes'), 'empty' => 'No', 'id' => 'type')) . '</td>';
            echo '<td>' . $this->Form->input('cursed', array('type' => 'select', 'options' => array('1' => 'Yes'), 'empty' => 'No', 'id' => 'type')) . '</td>';
            ?>
        </tbody>
    </table>
    <div align="center">

        <?php echo $this->Form->button('Consultar', array('type' => 'submit')); ?>
        <?php echo $this->Form->button('Limpar', array('id' => 'limpar')); ?>
    </div>
    <hr>

    <?php
    if (!empty($items)) {
        ?>
        <table>
            <tr>
                <th>Item</th>
                <th>Rarity</th>
                <th>Type</th>
                <th>Attunement</th>
                <th>Cursed</th>
                <th>Armor Type</th>
            </tr>
            <?php
            foreach ($items as $key => $item) {
                echo ('<tr>');
                echo ('<td>' . $item['MagicItems']['name'] . '</td>');
                echo ('<td>' . $item['MagicItems']['rarity'] . '</td>');
                echo ('<td>' . $item['MagicItems']['type'] . '</td>');
                echo ('<td>' . $item['MagicItems']['attunement'] . '</td>');
                echo ('<td>' . $item['MagicItems']['cursed'] . '</td>');
                if (!empty($item['MagicArmorType']['dnd_armors_id']))
                    echo ('<td>' . $dnd_armors[$item['MagicArmorType']['dnd_armors_id']] . '</td>');
                else 
                    echo ('<td></td>');
                echo ('</tr>');
            }
            ?>    
        </table>
        <?php
    }
    ?>

</div>
