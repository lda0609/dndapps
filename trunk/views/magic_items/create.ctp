<script type="text/javascript" src="/<?php echo APP_DIR ?>/js/jquery-1.7.2.js"></script>
<script type="text/javascript" src="/<?php echo APP_DIR ?>/js/jquery.meio.mask.min.js"></script>
<script type="text/javascript" src="/<?php echo APP_DIR ?>/js/jquery-ui-1.10.4.min.js"></script>

<?php
echo $this->Form->create('MagicItems', array('action' => 'create'));
?>
<table>
    <?php
    echo '<tr><td>' . $this->Form->input('name', array('id' => 'name', 'type' => 'text')) . '</td>';

    echo '<td>' . $this->Form->input('Attunement', array(
        'type' => 'select',
        'multiple' => 'checkbox',
        'label' => false,
        'options' => array('0' => 'attunement'),
    )) . '</td>';

        echo '<td>' . $this->Form->input('cursed', array(
        'type' => 'select',
        'multiple' => 'checkbox',
        'label' => false,
        'options' => array('0' => 'cursed'),
    )) . '</td>';

    echo '<td>' .$this->Form->button('Gravar', array('type' => 'submit')). '</td></tr>';
        
    echo '<tr><td>' . $this->Form->input('type', array('type' => 'radio', 'options' => $dnd_item_type, 'id' => 'type')) . '</td>';
    echo '<td>' . $this->Form->input('rarity', array('type' => 'radio', 'options' => $dnd_rarity, 'id' => 'rarity')) . '</td>';
    echo '<td>' . $this->Form->input('armor', array(
        'type' => 'select',
        'multiple' => 'checkbox',
        'options' => $dnd_armors,
    )) . '</td>';


    echo '<td><button type="button" id="Todas">Todas</button><br><br>';
    echo '<button type="button" id="Light">Light</button><br><br>';
    echo '<button type="button" id="Medium">Medium</button><br><br>';
    echo '<button type="button" id="Heavy">Heavy</button><br><br>';
    echo '<button type="button" id="Limpar">Limpar</button></td>';


    echo '</tr>';
    ?>
</table>
<div align="center">
    <?php
//    echo $this->Form->end;
    ?>
</div>
<script language="javascript">


    $("#Todas").click(function () {
        $("#MagicItemsArmor1").attr('checked', true);
        $("#MagicItemsArmor2").attr('checked', true);
        $("#MagicItemsArmor3").attr('checked', true);
        $("#MagicItemsArmor4").attr('checked', true);
        $("#MagicItemsArmor5").attr('checked', true);
        $("#MagicItemsArmor6").attr('checked', true);
        $("#MagicItemsArmor7").attr('checked', true);
        $("#MagicItemsArmor8").attr('checked', true);
        $("#MagicItemsArmor9").attr('checked', true);
        $("#MagicItemsArmor10").attr('checked', true);
        $("#MagicItemsArmor11").attr('checked', true);
        $("#MagicItemsArmor12").attr('checked', true);
        $("#MagicItemsArmor13").attr('checked', true);
    });

    $("#Light").click(function () {
        $("#MagicItemsArmor1").attr('checked', true);
        $("#MagicItemsArmor2").attr('checked', true);
        $("#MagicItemsArmor3").attr('checked', true);
    });

    $("#Medium").click(function () {
        $("#MagicItemsArmor4").attr('checked', true);
        $("#MagicItemsArmor5").attr('checked', true);
        $("#MagicItemsArmor6").attr('checked', true);
        $("#MagicItemsArmor7").attr('checked', true);
        $("#MagicItemsArmor8").attr('checked', true);
    });

    $("#Heavy").click(function () {
        $("#MagicItemsArmor9").attr('checked', true);
        $("#MagicItemsArmor10").attr('checked', true);
        $("#MagicItemsArmor11").attr('checked', true);
        $("#MagicItemsArmor12").attr('checked', true);
    });

    $("#Limpar").click(function () {
        $("#MagicItemsArmor1").attr('checked', false);
        $("#MagicItemsArmor2").attr('checked', false);
        $("#MagicItemsArmor3").attr('checked', false);
        $("#MagicItemsArmor4").attr('checked', false);
        $("#MagicItemsArmor5").attr('checked', false);
        $("#MagicItemsArmor6").attr('checked', false);
        $("#MagicItemsArmor7").attr('checked', false);
        $("#MagicItemsArmor8").attr('checked', false);
        $("#MagicItemsArmor9").attr('checked', false);
        $("#MagicItemsArmor10").attr('checked', false);
        $("#MagicItemsArmor11").attr('checked', false);
        $("#MagicItemsArmor12").attr('checked', false);
        $("#MagicItemsArmor13").attr('checked', false);
    });





</script>