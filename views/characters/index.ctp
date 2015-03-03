<style>
    @media print {
        .pageBreak { display: block; page-break-after: always; }
    }
</style>
<script src="http://code.jquery.com/jquery-2.0.0.js"></script>

<?php echo $this->Form->create('Characters', array('action' => 'index')); ?>
<table class="form_tabela" cellspacing="2">
    <tbody>
        <?php
        echo '<td>' . $this->Form->input('data', array('type' => 'select', 'options' => $data_adventures, 'empty' => 'Any', 'id' => 'data')) . '</td>';
        ?>
    </tbody>
</table>
<?php
echo $this->Form->button('Consultar', array('type' => 'submit'));
echo '<div class="pageBreak">';
if (!empty($pc)) {

//Progressão
    $lvl_max = 1;
    for ($lvl = 1; $lvl <= $lvl_max; $lvl++) {
        echo ('<table><tr>');
        echo ('<th>Name</th>');
        echo ('<th>Level</th>');
        echo ('<th>Str</th>');
        echo ('<th>Dex</th>');
        echo ('<th>Con</th>');
        echo ('<th>Int</th>');
        echo ('<th>Wis</th>');
        echo ('<th>Cha</th>');
        echo ('<th>AC</th>');
        echo ('<th>Init</th>');
        echo ('<th>Speed</th>');
        echo ('<th>HP</th>');
        echo ('<th>XP Inicial</th>');
        echo ('<th>XP Final</th>');
        echo ('</tr>');

        foreach ($pc as $characterId => $progression) {
            echo ('<tr>');
            echo ('<td width="150">' . $progression['Adventurers']['name'] . '</td>');
            echo ('<td width="150">' . $progression['CharacterProgression']['lvl'] . '</td>');
            echo ('<td width="150">' . $progression['CharacterProgression']['strength'] . '</td>');
            echo ('<td width="150">' . $progression['CharacterProgression']['dextery'] . '</td>');
            echo ('<td width="150">' . $progression['CharacterProgression']['constitution'] . '</td>');
            echo ('<td width="150">' . $progression['CharacterProgression']['intelligence'] . '</td>');
            echo ('<td width="150">' . $progression['CharacterProgression']['wisdom'] . '</td>');
            echo ('<td width="150">' . $progression['CharacterProgression']['charisma'] . '</td>');
            echo ('<td width="150">' . $progression['CharacterProgression']['ac'] . '</td>');
            echo ('<td width="150">' . $progression['CharacterProgression']['initiative'] . '</td>');
            echo ('<td width="150">' . $progression['CharacterProgression']['speed'] . '</td>');
            echo ('<td width="150">' . $progression['CharacterProgression']['hit_point_max'] . '</td>');
            echo ('<td width="150"">' . $progression['AdventurersPerAdventure']['xp_inicial'] . '</td>');
            echo ('<td width="150" class="xp' . $characterId . '">' . $progression['AdventurersPerAdventure']['xp_final'] . '</td>');
            echo ('</tr>');
        }
        echo ('</table>');
    }
    echo $this->Form->button('Salvar', array('type' => 'submit', 'id' => 'SalvarXP'));
    echo '<button class = "casting" type = "button" id = "AtualizarXP">Atualizar XP</button>';
}
echo '</div>';
?>

<script type="text/javascript">
    $("#AtualizarXP").show();
    $("#SalvarXP").hide();

    $("#AtualizarXP").click(function () {
        $("td.xp1").append('<input type="text" name="data[Characters][valueXP1]" maxlength="6">');
        $("td.xp2").append('<input type="text" name="data[Characters][valueXP2]" maxlength="6">');
        $("td.xp3").append('<input type="text" name="data[Characters][valueXP3]" maxlength="6">');
        $("td.xp4").append('<input type="text" name="data[Characters][valueXP4]" maxlength="6">');
        $("td.xp5").append('<input type="text" name="data[Characters][valueXP5]" maxlength="6">');
        $("td.xp6").append('<input type="text" name="data[Characters][valueXP6]" maxlength="6">');
        $("td.xp7").append('<input type="text" name="data[Characters][valueXP7]" maxlength="6">');

        $("#AtualizarXP").hide();
        $("#SalvarXP").show();
    });

</script>