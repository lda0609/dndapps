<style type="text/css">
    .btn {
        width: 50px;  height: 30px;
    }
    .casting {
        width: 70px;  height: 30px;
    }
    .conc {
        width: 70px;  height: 30px;
    }

</style>

<script src="http://code.jquery.com/jquery-2.0.0.js"></script>
<?php
echo $this->Form->create('Spells');
?>
<div align="center">
    <a href="cadastro">Limpar</a>
</div>

<table class="form_tabela" cellspacing="2">
    <tr>
        <?php
        echo '<td>';
        echo $this->Form->input('name', array('type' => 'text'));
        echo '</td>';
        echo '<td>';
        echo $this->Form->input('ritual', array(
            'type' => 'checkbox',
            'options' => array(1 => '1')
        ));
        echo '</td>';
        echo '</tr><tr>';
        $school = array('Abjuration' => 'Abjuration', 'Conjuration' => 'Conjuration', 'Divination' => 'Divination', 'Enchantment' => 'Enchantment', 'Evocation' => 'Evocation', 'Illusion' => 'Illusion', 'Necromancy' => 'Necromancy', 'Transmutation' => 'Transmutation');
        echo '<td>';
        echo $this->Form->input('school', array(
            'type' => 'radio',
            'options' => $school,
        ));
        echo '</td><td>';
        echo $this->Form->input('lvl', array(
            'type' => 'radio',
            'options' => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9),
            'value' => 3,
        ));
        echo '</td></tr><tr><td>';
        echo $this->Form->input('casting_time', array('type' => 'text', 'width' => '500'));
        ?>
        <td>
            <button class="casting" type="button" id="1Action">1 Action</button>
            <button class="casting" type="button" id="1Bonus">1 Bonus</button>
            <button class="casting" type="button" id="1Minute">1 Minute</button>
        </td>
        <?php
        echo '</td></tr><tr><td>';
        echo $this->Form->input('range', array('type' => 'text'));
        ?>
        <td>
            <button class="btn" type="button" id="self">Self</button>
            <button class="btn" type="button" id="touch">Touch</button>
        </td>
        <?php
        echo '</td></tr><tr><td>';
        echo $this->Form->input('components', array('type' => 'text'));
        ?>
        <td>
            <button class="btn"  type="button" id="V">V</button>
            <button class="btn"  type="button" id="S">S</button> 
            <button class="btn"  type="button" id="M">M</button> 
        </td> 
        <?php
        echo '</td></tr><tr><td>';
        echo $this->Form->input('duration', array('type' => 'text'));
        ?>
        <td>
            <button class="btn"  type="button" id="Inst">Inst</button> 
            <button class="btn"  type="button" id="1round">1 round</button> 
            <button class="btn"  type="button" id="1min">1 min</button> 
            <button class="btn"  type="button" id="1hour">1 hour</button> <br><br>
            <button class="btn"  type="button" id="con1">Con 1</button>
            <button class="btn"  type="button" id="con10">Con 10</button> 
            <button class="conc"  type="button" id="con1hour">Con 1 hour</button> 
        </td> 
        <?php
        echo '</td></tr><tr><td colspan="2">';
        echo $this->Form->input('description');
        echo '</td></tr>';
        ?>
</table>
<div align="center">
    <?php echo $this->Form->button('Salvar', array('type' => 'submit')); ?>
</div>

<script type="text/javascript">

    $("#1Action").click(function () {
        $('#SpellsCastingTime').val('1 Action');
    });
    $("#1Bonus").click(function () {
        $('#SpellsCastingTime').val('1 Bonus Action');
    });
    $("#1Minute").click(function () {
        $('#SpellsCastingTime').val('1 Minute');
    });
    $("#self").click(function () {
        $('#SpellsRange').val('Self');
    });
    $("#touch").click(function () {
        $('#SpellsRange').val('Touch');
    });

    $("#V").click(function () {
        var conteudo = $('#SpellsComponents').val();
        if (conteudo != '') {
            conteudo = conteudo + ', ';
        }
        $('#SpellsComponents').val(conteudo + 'V');
    });
    $("#S").click(function () {
        var conteudo = $('#SpellsComponents').val();
        if (conteudo != '') {
            conteudo = conteudo + ', ';
        }
        $('#SpellsComponents').val(conteudo + 'S');
    });
    $("#M").click(function () {
        var conteudo = $('#SpellsComponents').val();
        if (conteudo != '') {
            conteudo = conteudo + ', ';
        }
        $('#SpellsComponents').val(conteudo + 'M');
    });
    $("#Inst").click(function () {
        $('#SpellsDuration').val('Instantaneous');
    });
    $("#1round").click(function () {
        $('#SpellsDuration').val('1 Round');
    });
    $("#1min").click(function () {
        $('#SpellsDuration').val('1 Minute');
    });
    $("#1hour").click(function () {
        $('#SpellsDuration').val('1 Hour');
    });
    $("#con1").click(function () {
        $('#SpellsDuration').val('Concentration, up to 1 minute');
    });
    $("#con10").click(function () {
        $('#SpellsDuration').val('Concentration, up to 10 minutes');
    });
    $("#con1hour").click(function () {
        $('#SpellsDuration').val('Concentration, up to 1 hour');
    });

</script>