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

    <?php // debug($gods);  ?>

    <h3>Gods of Kelenia</h3>
    <table>
        <tr>
            <th>Name</th>
            <th>Alignment</th>
            <th>Domains</th>
            <th>Symbol</th>
        </tr>

        <?php
        foreach ($gods as $key => $god) {
            $godDomains = '';
            foreach ($god['GodsDomains'] as $key2 => $godDomain) {
                if (empty($godDomains)) {
                    $godDomains = $dnd_domains[$godDomain['dnd_domains_id']];
                } else {
                    $godDomains = $godDomains . ', ' . $dnd_domains[$godDomain['dnd_domains_id']];
                }
            }
            ?>
            <tr>
                <td><?php echo '<b>' . $god['Gods']['name'] . '</b>, ' . $god['Gods']['description']; ?></td>
                <td><?php echo $god['Gods']['alignment']; ?></td>
                <td><?php echo $godDomains; ?></td>
                <td><?php echo $god['Gods']['symbol']; ?></td>
            </tr>
            <?php
        }
        ?>

    </table>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <h3>Gods by domains</h3>

    <table>
        <tr>
            <th>Domain</th>
            <th colspan="8">Gods</th>
        </tr>
        <?php foreach ($domainsGodsC as $god => $domains) {
            ?>
            <tr>
                <td><?php echo $dnd_domains[$god]; ?></td>
                <?php foreach ($domains as $key => $domain) {
                    ?>
                    <td><?php echo $dnd_gods[$domain]; ?></td>
                    <?php
                }
                ?>
            </tr>
            <?php
        }
        ?>

    </table>

    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>


    <h3>Human Gods and domains</h3>

    <table>
        <tr>
            <th>Name</th>
            <th colspan="3">Domains</th>
        </tr>

        <?php foreach ($godsDomainsH as $god => $domains) {
            ?>
            <tr>
                <td><?php echo $dnd_gods[$god]; ?></td>
                <?php foreach ($domains as $key => $domain) {
                    ?>
                    <td><?php echo $dnd_domains[$domain]; ?></td>
                    <?php
                }
                ?>
            </tr>
            <?php
        }
        ?>

    </table>
    <br>
    <br>
    <h3>Nonhuman Gods and domains</h3>

    <table>
        <tr>
            <th>Name</th>
            <th colspan="3">Domains</th>
        </tr>

        <?php foreach ($godsDomainsNH as $god => $domains) {
            ?>
            <tr>
                <td><?php echo $dnd_gods[$god]; ?></td>
                <?php foreach ($domains as $key => $domain) {
                    ?>
                    <td><?php echo $dnd_domains[$domain]; ?></td>
                    <?php
                }
                ?>
            </tr>
            <?php
        }
        ?>

    </table>
    <br>
    <br>

    <br>
    <h3>Domains and Human Gods</h3>

    <table>
        <tr>
            <th>Domain</th>
            <th colspan="7">Gods</th>
        </tr>
        <?php foreach ($domainsGodsH as $god => $domains) {
            ?>
            <tr>
                <td><?php echo $dnd_domains[$god]; ?></td>
                <?php foreach ($domains as $key => $domain) {
                    ?>
                    <td><?php echo $dnd_gods[$domain]; ?></td>
                    <?php
                }
                ?>
            </tr>
            <?php
        }
        ?>

    </table>
    <br>
    <br>

    <h3>Domains and nonhuman Gods</h3>

    <table>
        <tr>
            <th>Domain</th>
            <th colspan="7">Gods</th>
        </tr>
        <?php foreach ($domainsGodsNH as $god => $domains) {
            ?>
            <tr>
                <td><?php echo $dnd_domains[$god]; ?></td>
                <?php foreach ($domains as $key => $domain) {
                    ?>
                    <td><?php echo $dnd_gods[$domain]; ?></td>
                    <?php
                }
                ?>
            </tr>
            <?php
        }
        ?>

    </table>