<div id="msg_lvlup">
    <table align="center">
        <tbody><tr>
                <td class="msg_sucesso">Personagem  com sucesso.</td>
            </tr>
        </tbody></table>
</div>

<div id="grupo1">
    <form class="pure-form pure-form-aligned">
        <fieldset>
            <div class="pure-control-group">
                <table border="0">
                    <tr>
                        <td>
                            <div class="pure-control-group"><label for="personagens">Personagens</label><select id="personagens"></select></div>
                        </td>
                    </tr>
                </table>
            </div>
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
                        <td rowspan="2">
                            <label for="name">Name</label><input id="name" type="text">
                        </td>
                        <td>
                            <label for="class">Class</label><select id="class"><?php echo $classes; ?></select>
                        </td>
                        <td>
                            <label for="level">Level</label><select id="level"><?php echo $lvl; ?></select>
                        </td>
                        <td>
                            <label for="background">Background</label><input id="background" type="text">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="race">Race</label><input id="race" type="text">
                        </td>
                        <td>
                            <label for="alignment">Alignment</label><select id="alignment"><?php echo $alignment; ?></select>
                        </td>
                        <td>
                            <label for="player">player</label><input id="player" type="text">
                        </td>
                    </tr>
                </table>

                <table border="0">
                    <tr>
                        <td>
                            <label for="str">Str</label><select id="str"><?php echo $attribute; ?></select>
                        </td>
                        <td>
                            <label for="dex">Dex</label><select id="dex"><?php echo $attribute; ?></select>
                        </td>
                        <td>
                            <label for="con">Con</label><select id="con"><?php echo $attribute; ?></select>
                        </td>
                        <td>
                            <label for="int">Int</label><select id="int"><?php echo $attribute; ?></select>
                        </td>
                        <td>
                            <label for="wis">Wis</label><select id="wis"><?php echo $attribute; ?></select>
                        </td>
                        <td>
                            <label for="cha">Cha</label><select id="cha"><?php echo $attribute; ?></select>
                        </td>
                    </tr>
                </table>
                <table border="0">
                    <tr>
                        <td>
                            <label for="ac">AC</label><input id="ac" type="number">
                        </td>
                        <td>
                            <label for="hp">HP</label><input id="hp" type="number">
                        </td>
                        <td>
                            <label for="init">Init</label><input id="init" type="number">
                        </td>
                        <td>
                            <label for="speed">Speed</label><input id="speed" type="number">
                        </td>
                    </tr>
                </table>
            </div>
        </fieldset>
    </form>

    <div align="center">
        <button class="pure-button pure-button-primary" type="button" id="btnGravar">Gravar</button>
    </div>
</div>