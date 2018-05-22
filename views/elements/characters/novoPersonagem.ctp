<div id="msg_inclusao">
    <table align="center">
        <tbody><tr>
                <td class="msg_sucesso">Personagem salvo com sucesso.</td>
            </tr>
        </tbody>
    </table>
</div>
<div id="grupo1">
    <form class="pure-form pure-form-aligned">
        <fieldset>
            <div class="pure-control-group"><label for="personagensGravados">Personagens Gravados</label><select id="personagensGravados"></select></div>
        </fieldset>
    </form>
</div>
<div id="body">
    <?php
    $lvl = '';
    for ($i = 1; $i <= 20; $i++) {
        $lvl = $lvl . '<option value="' . $i . '">' . $i . '</option>';
    }
    $classes = '';
    foreach ($dnd_classes as $key => $value) {
        $classes = $classes . '<option value="' . $key . '">' . $value . '</option>';
    }

    $alignment = '';
    foreach ($dnd_alignment_players as $key => $value) {
        $alignment = $alignment . '<option value="' . $key . '">' . $value . '</option>';
    }

    $attribute = '';
    for ($i = 6; $i <= 25; $i++) {
        $attribute = $attribute . '<option value="' . $i . '">' . $i . '</option>';
    }
    ?>
    <form class="pure-form pure-form-stacked">
        <fieldset>
            <div class="pure-control-group">
                <table class="pure-table" border="0">
                    <tr>
                        <td>
                            <label for="name">Name</label><input id="name" type="text" class="field">
                        </td>
                        <td>
                            <label for="class">Class</label><select id="class"><?php echo $classes; ?></select>
                        </td>
                        <td>
                            <label for="level">Level</label><select id="level"><?php echo $lvl; ?></select>
                        </td>
                        <td>
                            <label for="background">Background</label><input id="background" type="text" class="field">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="race">Race</label><input id="race" type="text" class="field">
                        </td>
                        <td>
                            <label for="alignment">Alignment</label><select id="alignment"><?php echo $alignment; ?></select>
                        </td>
                        <td>
                            <label for="player">Player</label><input id="player" type="text" class="field">
                        </td>
                        <td>
                            <label for="player">
                                <span title="lvl 1: 300xp&#13;&#10;lvl 2: 900xp&#13;&#10;lvl 3: 2700xp&#13;&#10;lvl 4: 6500xp&#13;&#10;lvl 5: 6500xp&#13;&#10;lvl 6: 14000xp&#13;&#10;lvl 7: 23000xp&#13;&#10;lvl 8: 34000xp&#13;&#10;lvl 9: 48000xp&#13;&#10;lvl 10: 64000xp&#13;&#10;lvl 11: 85000xp&#13;&#10;lvl 12: 100000xp&#13;&#10;lvl 13: 120000xp&#13;&#10;lvl 14: 140000xp&#13;&#10;lvl 15: 165000xp&#13;&#10;lvl 16: 195000xp&#13;&#10;lvl 17: 225000xp&#13;&#10;lvl 18: 265000xp&#13;&#10;lvl 19: 305000xp&#13;&#10;lvl 20: 355000xp"> XP <i class="fa fa-question-circle"></i></span>
                            </label><input id="xp" type="number" class="field large-number">
                        </td>
                    </tr>
                </table>



                <table border="0">
                    <tr>
                        <td>
                            <label for="str">Str</label><select id="str" class="attribute_field"><?php echo $attribute; ?></select>
                        </td>
                        <td>
                            <label for="dex">Dex</label><select id="dex" class="attribute_field"><?php echo $attribute; ?></select>
                        </td>
                        <td>
                            <label for="con">Con</label><select id="con" class="attribute_field"><?php echo $attribute; ?></select>
                        </td>
                        <td>
                            <label for="int">Int</label><select id="int" class="attribute_field"><?php echo $attribute; ?></select>
                        </td>
                        <td>
                            <label for="wis">Wis</label><select id="wis" class="attribute_field"><?php echo $attribute; ?></select>
                        </td>
                        <td>
                            <label for="cha">Cha</label><select id="cha" class="attribute_field"><?php echo $attribute; ?></select>
                        </td>
                    </tr>
                </table>
                <table border="0">
                    <tr>
                        <td>
                            <label for="ac">AC</label><input id="ac" type="number" class="field small-number">
                        </td>
                        <td>
                            <label for="hp">HP</label><input id="hp" type="number" class="field small-number">
                        </td>
                        <td>
                            <label for="init">Init</label><input id="init" type="number" class="field small-number">
                        </td>
                        <td>
                            <label for="speed">Speed</label><input id="speed" type="number" class="field small-number">
                        </td>
                    </tr>
                </table>
            </div>
        </fieldset>
    </form>
    <form class="pure-form pure-form-aligned">
        <fieldset>
            <div class="pure-control-group">
                <table class="pure-table" border="0">
                    <tr>
                        <?php
                        $rows = '';
                        $cont = 1;
                        foreach ($dnd_skills as $key => $skill) {
                            $rows .= '<tr><td>' . $skill . '</td><td><input id="' . $key . '" type="number" class="skill small-number"></td></tr>';
                            if ($cont++ % 6 == 0) {
                                $column = '<td><table>' . $rows . '</table></td>';
                                echo $column;
                                $rows = '';
                            }
                        }
                        ?>
                    </tr>
                </table>
            </div>
        </fieldset>
    </form>

    <div align="center">
        <button class="pure-button pure-button-primary" type="button" id="btnGravar">Gravar Novo</button>
        <button class="pure-button button-warning" type="button" id="btnAlterar">Alterar Atual</button>
        <button class="pure-button button-error" type="button" id="btnLevelUp">Level Up</button>
        <button class="pure-button button-secondary" type="button" id="btnLimpar">Limpar</button>
    </div>
</div>