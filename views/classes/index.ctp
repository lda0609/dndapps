<style type="text/css">
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
    </style>

    <h2>Character Class</h2>
    <table>
        <tr>
            <th>Name</th>
            <th>Hit Dice</th>
            <th>Armor</th>
            <th>Weapon</th>
            <th>Tool</th>
            <th>Saving Throws</th>
            <th>Skills</th>
        </tr>

        <?php
        foreach ($classes as $key => $class) {
            ?>
            <tr>
                <td><?php echo '<b>' . $class['Classes']['name'] . '</b>'; ?></td>
                <td><?php echo $class['Classes']['hd']; ?></td>
                <td><?php echo $class['Classes']['armor']; ?></td>
                <td><?php echo $class['Classes']['weapon']; ?></td>
                <td><?php echo $class['Classes']['tool']; ?></td>
                <td><?php echo $class['Classes']['saving_throws']; ?></td>
                <td><?php echo $class['Classes']['Skills']; ?></td>
            </tr>
            <?php
        }
        ?>

    </table>
