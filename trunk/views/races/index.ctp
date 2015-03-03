<style type="text/css">
    h3 {
        color: #294A9E;
        font-size: 165%;
    }

    p.breakhere {page-break-before: always}

    body {
        font-family: 'lucida grande',verdana,helvetica,arial,sans-serif;
        font-size: 60%;
    }

    table tr td {
        background: #fff;
        padding: 1px;
        text-align: left;
        vertical-align: top;
        border-bottom: 0px solid #ddd;
    }


</style>

<?php
foreach ($races as $key => $race) {
//            debug($race);
    ?>
    <table>

        <tr><td colspan="2"><h3><?php echo '<b>' . $race['commonTraits']['race'] . '</b>'; ?></h3></th></tr>

        <?php
        $ability1 = $race['commonTraits']['abilityScoreIncrease'][0]['ability'] . ' (+' . $race['commonTraits']['abilityScoreIncrease'][0]['increase'] . ')';
        if (isset($race['commonTraits']['abilityScoreIncrease'][1])) {
            $ability2 = ', ' . $race['commonTraits']['abilityScoreIncrease'][1]['ability'] . ' (+' . $race['commonTraits']['abilityScoreIncrease'][1]['increase'] . ')';
        } else {
            $ability2 = '';
        }
        ?>
        <tr><td>Ability Score Increase</td><td><?php echo $ability1 . $ability2; ?></td></tr>
        <tr><td>Adult Age/Old Age</td><td><?php echo $race['commonTraits']['age']['adult'] . '/' . $race['commonTraits']['age']['average']; ?></td></tr>
        <tr><td>Alignment tendency</td><td><?php echo $race['commonTraits']['alignment']; ?></td></tr>
        <tr><td>Size</td><td><?php echo $race['commonTraits']['size']; ?></td></tr>
        <tr><td>Height</td><td><?php echo $race['commonTraits']['height']; ?></td></tr>
        <tr><td>Weight</td><td><?php echo $race['commonTraits']['weight']; ?></td></tr>
        <tr><td>Speed</td><td><?php echo $race['commonTraits']['speed'] . ' ft'; ?></td></tr>
        <tr><th colspan="1">Traits</th></tr>
        <?php
        foreach ($race['uniqueTraits'] as $trait => $description) {
            ?>
            <tr><td><?php echo $trait; ?></td><td><?php echo $description; ?></td></tr>
            <?php
        }
        if (isset($race['subrace'])) {
            foreach ($race['subrace'] as $key2 => $subrace) {
                ?>
                <tr><th colspan="2"><?php echo '<b>' . $subrace['race'] . '</b>'; ?></th></tr>
                <tr><td>Ability Score Increase</td><td><?php echo $subrace['commonTraits']['abilityScoreIncrease'][0]['ability'] . ' (+' . $subrace['commonTraits']['abilityScoreIncrease'][0]['increase'] . ')'; ?></td></tr>
                <?php if (isset($subrace['commonTraits']['abilityScoreIncrease'][1])) { ?>
                    <tr><td></td><td><?php echo $subrace['commonTraits']['abilityScoreIncrease'][1]['ability'] . ' (+' . $subrace['commonTraits']['abilityScoreIncrease'][1]['increase'] . ')'; ?></td></tr>
                    <?php
                } if (isset($subrace['uniqueTraits'])) {
                    foreach ($subrace['uniqueTraits'] as $subTrait => $subDescription) {
                        ?>
                        <tr><td><?php echo $subTrait; ?></td><td><?php echo $subDescription; ?></td></tr>
                        <?php
                    }
                }
            }
        }
        ?>
    </table>
    <?php
    if (in_array($key, array('Dwarf', 'Elf', 'Half-Elf'))) {
        echo '<p class="breakhere"></p>';
    }
}
?>