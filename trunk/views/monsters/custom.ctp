<?php echo $this->element('css'); ?>
<link rel="stylesheet" href="/<?php echo APP_DIR ?>/css/customMonster.css" type="text/css"/> 

<?php
echo $this->element('javascript');
$monster_type = '';
foreach ($dnd_monster_type as $key => $value) {
    $monster_type = $monster_type . '<option value="' . $key . '">' . $value . '</option>';
}

$monster_tag = '';
foreach ($dnd_monster_tag as $key => $value) {
    $monster_tag = $monster_tag . '<option value="' . $key . '">' . $value . '</option>';
}

$alignment = '';
foreach ($dnd_alignment as $key => $value) {
    $alignment = $alignment . '<option value="' . $key . '">' . $value . '</option>';
}

$attribute = '';
for ($i = 0; $i <= 25; $i++) {
    $attribute = $attribute . '<option value="' . $i . '">' . $i . '</option>';
}
$damageTypes = array('Bludgeoning', 'Piercing', 'Slashing', 'Acid', 'Cold', 'Fire', 'Force', 'Lightning', 'Necrotic', 'Poison', 'Psychic', 'Radiant', 'Thunder');

$damageTypesSelect = '';
foreach ($damageTypes as $key => $value) {
    $damageTypesSelect = $damageTypesSelect . '<option value="' . $key . '">' . $value . '</option>';
}

$languages = array('Common', 'Dwarvish', 'Elvish', 'Giant', 'Gnomish', 'Goblin', 'Halfling', 'Orc', 'Abyssal', 'Celestial', 'Draconic', 'Deep Speech', 'Infernal', 'Primordial', 'Sylvan', 'Undercommon');
$conditions = array('Blinded', 'Charmed', 'Deafened', 'Exhaustion', 'Frightened', 'Grappled', 'Incapacitated', 'Invisible', 'Paralyzed', 'Petrified', 'Poisoned', 'Prone', 'Restrained', 'Stunned', 'Unconscious');
?>

<div>
    <table id="table_body">
        <tr>
            <td id="td_left_size"  width="25%">
                <ul id="menu">
                    <li class="ui-widget-header"><div>Creation Steps</div></li>
                    <li><div class="menu-button" >General</div></li>
                    <li><div class="menu-button" >Extended</div></li>
                    <li><div class="menu-button" >Vulnerabilities, Resistances, and Immunities</div></li>
                    <li><div class="menu-button" >Languages and Environments</div></li>
                </ul>
            </td>
            <!-- tabs -->
            <td width="50%">
                <div id="MonsterForm">
                    <form class="pure-form pure-form-stacked">
                        <fieldset>
                            <div class="pure-control-group">

                                <div class="forms" id="General"  style="display: none;">
                                    <h2>General Information</h2>
                                    <table class="pure-table" border="0">
                                        <tr>
                                            <td colspan="2">
                                                <label for="name">Name</label><input id="name" type="text" class="pure-input-1">
                                            </td>
                                            <td>
                                                <label for="size">Size</label>
                                                <select id="size" class="pure-input-1">
                                                    <option value="Tiny">Tiny</option>
                                                    <option value="Medium" selected>Medium</option>
                                                    <option value="Large">Large</option>
                                                    <option value="Huge">Huge</option>
                                                    <option value="Gargantuan">Gargantuan</option>
                                                </select>
                                            </td>
                                            <td>
                                                <label for="monsterType">Type</label><select id="monsterType" class="pure-input-1"><?php echo $monster_type; ?></select>
                                            </td>
                                            <td colspan="2">
                                                <label for="monsterType">Tag</label><select id="monsterTag" class="pure-input-1"><?php echo $monster_tag; ?></select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for="alignment">Alignment</label><select id="alignment" class="pure-input-1"><?php echo $alignment; ?></select>
                                            </td>
                                            <td>
                                                <label for="hp">HP</label><input id="hp" type="number" class="field medium-number">
                                            </td>
                                            <td>
                                                <label for="cr">CR</label><input id="cr" type="number" class="field small-number">
                                            </td>
                                            <td>                                            
                                                <label for="book">Book</label>
                                                <select id="book" class="pure-input-1">
                                                    <option value="Custom">Custom</option>
                                                    <option value="MToF">MToF</option>
                                                    <option value="Volo">Volo</option>
                                                    <option value="OotA">OotA</option>
                                                    <option value="MM">MM</option>
                                                </select>
                                            </td>
                                            <td>
                                                <label for="bookPage">Page</label><input id="bookPage" type="number" class="field medium-number">
                                            </td>
                                            <td>
                                                <label for="template">Template<input id="template" type="checkbox"></label>
                                            </td>

                                        </tr>
                                    </table>

                                </div>
                                <div class="forms" id="Extended">
                                    <h2>Abilities</h2>
                                    <table border="0">
                                        <tr>
                                            <td>
                                                <label for="str">Str</label><input id="str" type="number" class="attribute_field small-number" value="10">
                                            </td>
                                            <td>
                                                <label for="dex">Dex</label><input id="dex" type="number" class="attribute_field small-number" value="10">
                                            </td>
                                            <td>
                                                <label for="con">Con</label><input id="con" type="number" class="attribute_field small-number" value="10">
                                            </td>
                                            <td>
                                                <label for="int">Int</label><input id="int" type="number" class="attribute_field small-number" value="10">
                                            </td>
                                            <td>
                                                <label for="wis">Wis</label><input id="wis" type="number" class="attribute_field small-number" value="10">
                                            </td>
                                            <td>
                                                <label for="cha">Cha</label><input id="cha" type="number" class="attribute_field small-number" value="10">
                                            </td>
                                            <td>
                                                <label for="proficiency">Proficiency</label><input id="proficiency" type="number" class="attribute_field small-number" value="2">
                                            </td>
                                            <td>
                                                <label for="ac">AC</label><input id="ac" type="number" class="field small-number">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for="strSave">Save<input id="strSave" type="checkbox"></label>
                                            </td>
                                            <td>
                                                <label for="dexSave">Save<input id="dexSave" type="checkbox"></label>
                                            </td>
                                            <td>
                                                <label for="conSave">Save<input id="conSave" type="checkbox"></label>
                                            </td>
                                            <td>
                                                <label for="intSave">Save<input id="intSave" type="checkbox"></label>
                                            </td>
                                            <td>
                                                <label for="wisSave">Save<input id="wisSave" type="checkbox"></label>
                                            </td>
                                            <td>
                                                <label for="chaSave">Save<input id="chaSave" type="checkbox"></label>
                                            </td>
                                        </tr>
                                    </table>

                                    <h2>Movement</h2>
                                    <table>
                                        <tr>
                                            <td>
                                                <label for="baseSpeed">Base Speed</label><input id="baseSpeed" type="number" class="field small-number">
                                            </td>
                                            <td>
                                                <label for="flySpeed">Fly</label><input id="flySpeed" type="number" class="field small-number">
                                            </td>
                                            <td>
                                                <label for="swimSpeed">Swim</label><input id="swimSpeed" type="number" class="field small-number">
                                            </td>
                                            <td>
                                                <label for="climbSpeed">Climb</label><input id="climbSpeed" type="number" class="field small-number">
                                            </td>
                                            <td>
                                                <label for="burrowSpeed">Burrow</label><input id="burrowSpeed" type="number" class="field small-number">
                                            </td>
                                        </tr>
                                    </table>

                                    <h2>Skills</h2>
                                    <table border="0">
                                        <tr>
                                            <?php
                                            $count = 0;
                                            foreach ($dnd_skills as $key => $skill) {
                                                if ($count++ % 6 == 0) {
                                                    echo '</tr><tr>';
                                                }
                                                ?>
                                                <td>
                                                    <label for="<?= $key ?>"><?= $skill ?></label><input id="<?= $key ?>" type="number" class="field small-number">
                                                </td>
                                            <?php } ?>
                                        </tr>
                                    </table>

                                    <h2>Senses</h2>
                                    <table>
                                        <tr>
                                            <td>
                                                <label for="blindsight">Blindsight<input id="blindsight" type="checkbox"></label>
                                                <input id="blindsightValue" type="number" class="field small-number">
                                            </td>
                                            <td>
                                                <label for="darkvision">Darkvision<input id="darkvision" type="checkbox"></label>
                                                <input id="darkvisionValue" type="number" class="field small-number">
                                            </td>
                                            <td>
                                                <label for="tremorsenseSpeed">Tremorsense<input id="tremorsenseSpeed" type="checkbox"></label>
                                                <input id="tremorsenseValue" type="number" class="field small-number">
                                            </td>
                                            <td>
                                                <label for="truesightSpeed">Truesight<input id="truesightSpeed" type="checkbox"></label>
                                                <input id="truesightValue" type="number" class="field small-number">
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="forms" id="Vulnerabilities-Resistances-and-Immunities" style="display: none;">
                                    <h2>Damage Vulnerabilities, Resistances, and Immunities</h2>
                                    <table border="0">
                                        <tr>
                                            <th>Damage Type</th>
                                            <th>Vulnerabilities</th>
                                            <th>Resistances</th>
                                            <th>Immunities</th>
                                        </tr>
                                        <tr>
                                            <td width="25%">
                                                <ul class="selectable">
                                                    <?php
                                                    foreach ($damageTypes as $key => $value) {
                                                        echo '<li class="ui-widget-content" id="liType' . $value . '">' . $value
                                                        . ' <img class="clickable btnImmunity" align="right" width="20px" src="/dndapps/img/Alphabet/i24px.png" alt="' . $value . '">'
                                                        . ' <img class="clickable btnResistance" align="right" width="20px" src="/dndapps/img/Alphabet/r24px.png" alt="' . $value . '">'
                                                        . ' <img class="clickable btnVulnerability" align="right" width="20px" src="/dndapps/img/Alphabet/v24px.png" alt="' . $value . '"><br>';
                                                    }
                                                    ?>
                                                </ul>
                                            </td>

                                            <td width="25%">
                                                <ul class="selectable" id="vulnerabilities">
                                                </ul>
                                            </td>
                                            <td width="25%">
                                                <ul class="selectable" id="resistances">
                                                </ul>
                                            </td>
                                            <td width="25%">
                                                <ul class="selectable" id="immunities">
                                                </ul>
                                            </td>
                                        </tr>
                                    </table>

                                    <h2>Conditions Immunities</h2>
                                    <table border="0">
                                        <tr>
                                            <?php
                                            $count = 0;
                                            foreach ($conditions as $key => $value) {
                                                if ($count++ % 5 == 0) {
                                                    echo '</tr><tr>';
                                                }
                                                ?>
                                                <td>
                                                    <label for="<?= $value ?>" class="pure-checkbox">
                                                        <input id="<?= $value ?>" type="checkbox" value="">
                                                        <?= $value ?>
                                                    </label>
                                                </td>
                                            <?php } ?>
                                        </tr>
                                    </table>
                                </div>

                                <div class="forms" id="Languages-and-Environments" style="display: none;">
                                    <h2>Languages</h2>
                                    <table border="0">
                                        <tr>
                                            <?php
                                            $count = 0;
                                            foreach ($languages as $key => $value) {
                                                if ($count++ % 6 == 0) {
                                                    echo '</tr><tr>';
                                                }
                                                ?>
                                                <td>
                                                    <label for="<?= $value ?>" class="pure-checkbox">
                                                        <input id="<?= $value ?>" type="checkbox" value="">
                                                        <?= $value ?>
                                                    </label>
                                                </td>
                                            <?php } ?>
                                        </tr>
                                    </table>

                                    <h2>Environments</h2>
                                    <table border="0">
                                        <tr>
                                            <?php
                                            foreach ($dnd_monster_environments as $key => $value) {
                                                if ($key == 7) {
                                                    echo '</tr><tr>';
                                                }
                                                ?>
                                                <td>
                                                    <label for="<?= $value ?>Skill" class="pure-checkbox">
                                                        <input id="<?= $value ?>Skill" type="checkbox" value="">
                                                        <?= $value ?>
                                                    </label>
                                                </td>
                                            <?php } ?>
                                        </tr>
                                    </table>
                                </div>

                                <div class="forms" id="Actions" >
                                    <h1>Actions</h1>
                                    <table class="pure-table" border="0">
                                        <tr>
                                            <td>
                                                <label for="actionTemplate">Load Action</label>
                                                <select id="actionTemplate">
                                                    <option value="0"></option>
                                                    <option value="1">Radiant Attack</option>
                                                    <option value="2">Necrotic Attack</option>
                                                </select>
                                            </td>
                                            <td>
                                                <label for="actionName">Action Name</label><input id="actionName" type="text" class="pure-input-1">
                                            </td>
                                            <td>
                                                <label for="actionAttackBonus">Attack</label><input id="actionAttackBonus" type="number" class="field small-number">
                                            </td>
                                            <td>
                                                <label for="actionType">Action Type</label>
                                                <select id="actionType">
                                                    <option value="Bonus">Bonus</option>
                                                    <option value="Action" selected>Action</option>
                                                    <option value="Reaction">Reaction</option>
                                                </select>
                                            </td>
                                            <td>
                                                <label for="actionLimitation">Usage Limitation</label><input id="actionLimitation" type="text" class="pure-input-1">
                                            </td>
                                        </tr>
                                    </table>
                                    <table>
                                        <tr>
                                            <td>
                                                <label for="attackType">Attack Type</label>
                                                <select id="attackType">
                                                    <option value=""></option>
                                                    <option value="Melee Weapon Attack">Melee Weapon Attack</option>
                                                    <option value="Ranged Weapon Attack">Ranged Weapon Attack</option>
                                                    <option value="Melee or Ranged Weapon Attack">Melee or Ranged W. Att.</option>
                                                    <option value="Melee Spell Attack">Melee Spell Attack</option>
                                                    <option value="Ranged Spell Attack">Ranged Spell Attack</option>
                                                </select>
                                            </td>
                                            <td>
                                                <label for="actionReach">Reach</label><input id="actionReach" type="number" class="field small-number">
                                            </td>
                                            <td>
                                                <label for="actionShortRange">Short Range</label><input id="actionShortRange" type="number" class="field small-number">
                                            </td>
                                            <td>
                                                <label for="actionLongRange">Long Range</label><input id="actionLongRange" type="number" class="field small-number">
                                            </td>
                                            <td>
                                                <label for="actionTarget">Target</label>
                                                <select id="actionTarget">
                                                    <option value=""></option>
                                                    <option value="one target" selected>One Target</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </table>

                                    Primary Damage
                                    <table>
                                        <tr>
                                            <td>
                                                <label for="primaryAverage">Average</label><input id="primaryAverage" type="number" class="field small-number">
                                            </td>
                                            <td>
                                                <label for="primaryDice">Dice</label><input id="primaryDice" type="text" class="pure-input-1">
                                            </td>
                                            <td>
                                                <label for="primaryType">Type</label><select id="primaryType"><?php echo $damageTypesSelect; ?></select>
                                            </td>
                                        </tr>
                                    </table>

                                    Secondary Damage
                                    <table>
                                        <tr>
                                            <td>
                                                <label for="secondaryAverage">Average</label><input id="secondaryAverage" type="number" class="field small-number">
                                            </td>
                                            <td>
                                                <label for="secondaryDice">Dice</label><input id="secondaryDice" type="text" class="pure-input-1">
                                            </td>
                                            <td>
                                                <label for="secondaryType">Type</label><select id="secondaryType"><?php echo $damageTypesSelect; ?></select>
                                            </td>
                                        </tr>
                                    </table>


                                    Special Actions
                                    <table>
                                        <tr>
                                            <td width="75%">
                                                <label for="SADescription">Description</label><textarea class="pure-input-1" id="SADescription" ></textarea>
                                            </td>
                                            <td  width="25%">
                                                <label for="SALimitation">Usage Limitation</label><input id="SALimitation" type="text" class="pure-input-1">
                                            </td>
                                        </tr>
                                    </table>

                                </div> <!--div menu-->

                            </div>
                        </fieldset>
                    </form>


                </div>
            </td>
            <td id="td_right_size"  width="25%">
            </td>
        </tr>
        <tr>
            <td colspan id="td_footer" class="td_footer"></td>
            <td colspan id="td_footer_info" class="td_footer"></td>
            <td colspan class="td_footer" ></td>
        </tr>
    </table>
</div>

<script src="/<?php echo APP_DIR ?>/js/customMonsters.js?2"></script>
