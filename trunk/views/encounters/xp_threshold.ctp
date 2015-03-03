<div id="conteudo_corpo">
    <?php echo $this->Form->create('Encounter', array('action' => 'xpThreshold')); ?>
    <table class="form_tabela" cellspacing="2">
        <tbody>
            <?php
            echo '<td>' . $this->Form->input('data', array('type' => 'select', 'options' => $data_adventures, 'empty' => 'Any', 'id' => 'data')) . '</td>';
            ?>
        </tbody>
    </table>
    <?php echo $this->Form->button('Consultar', array('type' => 'submit')); ?>

    <?php
    if (!empty($xpThreshold)) {

    echo '<br><br><h4><b>Adjusted XP per Adventuring Day: '. $adjustedXpDay . '</b></h4>';
        ?>
    <table>
            <?php
            echo ('<tr>');
            echo ('<td width="150">Easy</td><td>' . $xpThreshold['easy'] . '</td>');
            echo ('</tr>');
            echo ('<tr>');
            echo ('<td>Medium</td><td>' . $xpThreshold['medium'] . '</td>');
            echo ('</tr>');
            echo ('<tr>');
            echo (' <td>Hard</td><td>' . $xpThreshold['hard'] . '</td>');
            echo ('</tr>');
            echo ('<tr>');
            echo (' <td>Deadly</td><td>' . $xpThreshold['deadly'] . '</td>');
            echo ('</tr>');
            ?>    
    </table>
    <table>
            <?php
            echo ('<tr>');
            echo ('<td width="150"></td><th>1 (x0.5)</th><th>2(x1)</th><th>3-6(x1.5)</th><th>7-10(x2)</th><th>11-14(x2.5)</th><th>15+(x3)</th>');
            echo ('</tr>');
            foreach ($xpMultiplier as $difficulty => $diff_table) {
                echo ('<tr><td width="150">' . $difficulty . '</td>');
                foreach ($diff_table as $qtde => $XPvalue) {
                    echo ('<td>' . $XPvalue . '</td>');
                }
                echo ('</tr>');
            }
            ?>
    </table>

    <table>
            <?php
            echo ('<tr>');
            echo ('<th>Name</th>');
            echo ('<th>Race</th>');
            echo ('<th>Class</th>');
            echo ('<th>Player</th>');
            echo ('<th>Level</th>');
            echo ('</tr>');
            foreach ($adventurers as $key => $adventurer) {
                echo ('<tr><td width="150">' . $adventurer['Adventurers']['name'] . '</td>');
                echo ('<td width="150">' . $adventurer['Adventurers']['race'] . '</td>');
                echo ('<td width="150">' . $dnd_classes[$adventurer['Adventurers']['class']] . '</td>');
                echo ('<td width="150">' . $adventurer['Adventurers']['player'] . '</td>');
                echo ('<td width="150">' . $adventurer['AdventurersPerAdventure']['lvl_inicial'] . '</td></tr>');
            }
            ?>
    </table>


        <?php
    }
    ?>
</div>