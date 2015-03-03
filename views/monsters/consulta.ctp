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
    <?php echo $this->Form->create('Monsters', array('action' => 'consulta')); ?>
    <table class="form_tabela" cellspacing="2">
        <tbody>
            <?php
            foreach ($dnd_xp as $cr => $xp) {
                $cr_list[$cr] = $cr;
            }

            echo $this->Form->input('name', array('id' => 'monsterName', 'type' => 'text'));
            echo '<tr><td>'.$this->Form->input('CRMin', array('type' => 'select', 'options' => $cr_list, 'empty' => 'Any', 'id' => 'crMin')).'</td>';
            echo '<td>'.$this->Form->input('CRMax', array('type' => 'select', 'options' => $cr_list, 'empty' => 'Any', 'id' => 'crMax')).'</td>';
            echo '<td>'.$this->Form->input('Type', array('type' => 'select', 'options' => $dnd_monster_type, 'empty' => 'Any', 'id' => 'type')).'</td>';
            echo '<td>'.$this->Form->input('alignment', array('type' => 'select', 'options' => $dnd_alignment, 'empty' => 'Any', 'id' => 'Alignment')).'</td></tr>';
            ?>
        </tbody>
    </table>
    <div align="center">

        <?php echo $this->Form->button('Consultar', array('type' => 'submit')); ?>
        <?php echo $this->Form->button('Limpar', array('id' => 'limpar')); ?>
    </div>
    <hr>

    <?php
    if (!empty($monsters)) {
        ?>
        <table>
            <tr>
                <th>Monster</th>
                <th>Type</th>
                <th>CR</th>
                <th>Size</th>
                <th>Alignment</th>
                <th>Page</th>
            </tr>
            <?php
            foreach ($monsters as $key => $monster) {
                echo ('<tr>');
                echo ('<td>' . $monster['Monsters']['name'] . '</td>');
                echo ('<td>' . $dnd_monster_type[$monster['MonsterTypes'][0]['dnd_type_id']] . '</td>');
                echo ('<td>' . $monster['Monsters']['cr'] . '</td>');
                echo ('<td>' . $monster['Monsters']['size'] . '</td>');
                echo ('<td>' . $monster['Monsters']['alignment'] . '</td>');
                echo ('<td>' . $monster['Monsters']['page'] . '</td>');
                echo ('</tr>');
            }
            ?>    
        </table>
        <?php
    }
    ?>

</div>

<script language="javascript">

    // Script chamado ao se clicar no botão Limpar para resetar os valores do form
    $("#limpar").click(function () {
        $('#tipoCertidao option[value=""]').attr('selected', 'selected');
        $("#numCertidao").val('');
        $("#cpfEmissor").val('');
        $("#cpfRequerente").val('');
        $("#cnpjRequerente").val('');
        $("#drr").val('');
        $("#lotacao").val('');
        $("#dataInicial").val('');
        $("#dataFinal").val('');
        $('#numCertidao').change();
    });

</script>