<style>
    @media print {
        .pageBreak { display: block; page-break-after: always;}
    }
    table th {
        color: #e32;
        font-size: 110%;
        font-weight: bold;
        margin-bottom: 0.0em;
    }
    .spells {
        font-size: 80%;
    }    


</style>
<script src="http://code.jquery.com/jquery-2.0.0.js"></script>


<?php
foreach ($spellList as $lvl => $spellListForLevel) {
    switch ($lvl) {
        case 0:
            echo '<h3>' . $class . ' Cantrips (' . $lvl . ' Level)</h1>';
            break;
        case 1:
            echo '<h3>' . $class . ' ' . $lvl . 'st Level</h1>';
            break;
        case 2:
            echo '<h3>' . $class . ' ' . $lvl . 'nd Level</h1>';
            break;
        case 3:
            echo '<h3>' . $class . ' ' . $lvl . 'rd Level</h1>';
            break;
        default:
            echo '<h3>' . $class . ' ' . $lvl . 'th Level</h1>';
            break;
    }
    echo '<table class="spells">';
    foreach ($spellListForLevel as $key => $value) {
        if ($value['Spells']['ritual']) {
            $ritual = ' &#8226; Ritual';
        } else {
            $ritual = '';
        }
        if ($value['Spells']['cleric_domain']) {
            $complemento = ' &#8226; Domain Spell';
        }if ($value['Spells']['warlock_patron']) {
            $complemento = ' &#8226; Patron Spell';
        }if ($value['Spells']['druid_circle']) {
            $complemento = ' &#8226; Druid Circle';
        } else {
            $complemento = '';
        }
        ?>
        <tr>
            <th colspan="2"><?php echo $value['Spells']['name'] . ' (' . $value['Spells']['school'] . ')' . $ritual . $complemento; ?></th>
        </tr>
        <tr>
            <td width="32%">
                <?php echo '<b>Casting Time: </b>' . $value['Spells']['casting_time']; ?>
                <?php echo '<br><b>Range: </b>' . $value['Spells']['range']; ?>
                <?php echo '<br><b>Components: </b>' . $value['Spells']['components']; ?>
                <?php echo '<br><b>Duration: </b>' . $value['Spells']['duration']; ?>
            </td>
            <?php
            echo '<td>' . $value['Spells']['description'] . '</td></tr>';

            switch ($class) {
                case 'Cleric':
                    if (in_array($value['Spells']['name'], array('Inflict Wounds', 'Thunderwave', 'Continual Flame', 'Hold Person', 'Silence', 'Zone of Truth', 'Bestow Curse', 'Glyph of Warding', 'Remove Curse')))
                        echo '</table><div class="pageBreak"></div><table class="spells">';
                    break;
                case 'Druid':
                    if (in_array($value['Spells']['name'], array(
                        'Goodberry', 
                        'Thunderwave', 
                        'Dust Devil', 
                        'Flaming Sphere', 
                        'Locate Object', 
                        'Warding Wind', 
                        'Dispel Magic', 
                        'Plant Growth', 
                        'Wall of Water', 
                        'Wind Wall', 
                        'Conjure Minor Elementals', 
                        'Control Water', 
                        'Giant Insect', 
                        'Watery Sphere', 
                        'Control Winds',
                        'Mass Cure Wounds',
                        'Scrying', 
                       
                        
                        )))
                        echo '</table><div class="pageBreak"></div><table class="spells">';
                    break;
                case 'Ranger':
                    if (in_array($value['Spells']['name'], array('Ensnaring Strike', 'Darkvision')))
                        echo '</table><div class="pageBreak"></div><table class="spells">';
                    break;
                case 'Sorcerer':
                    if ($shortlist) {
                        if (in_array($value['Spells']['name'], array('Shocking Grasp', 'Scorching Ray')))
                            echo '</table><div class="pageBreak"></div><table class="spells">';
                    } else {
                        if (in_array($value['Spells']['name'], array(
                                    'Light', 'Poison Spray', 'True Strike', 'Sleep', 'Witch Bolt',
                                    'Enhance Ability', 'Invisibility', 'Misty Step', 'Spider Climb', 'Web',
                                    'Dispel Magic', 'Sleet Storm')))
                            echo '</table><div class="pageBreak"></div><table class="spells">';
                    }
                    break;
                case 'Wizard':
                    if (in_array($value['Spells']['name'], array(
                                'Light', 'Poison Spray', 'True Strike',
                                'Longstrider', 'Silent Image', 'Thunderwave', 'Witch Bolt',
                                'Cloud of Daggers', 'Gust of Wind', 'Locate Object', 'Misty Step', 'Ray of Enfeeblement', 'Spider Climb', 'Web',
                                'Remove Curse', 'Tongues'
                            )))
                        echo '</table><div class="pageBreak"></div><table class="spells">';
                    break;
                default:
                    break;
            }
        }
        echo '</table>';
    }
    ?>

<script type="text/javascript">
     $(document).ready(function () {
        $("#header").hide('');
        $("#footer").hide();
    });
</script>