<style type="text/css">
    body {
        font-family: 'lucida grande',verdana,helvetica,arial,sans-serif;
        font-size: 70%;
    }

    table tr:nth-child(2n) td {
        background: #ffffff;
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
    echo $this->Form->create('monsters');

    $pages[1][0] = array_splice($monsterList, 0, 53);
    $pages[1][1] = array_splice($monsterList, 0, 50);
    $pages[1][2] = array_splice($monsterList, 0, 51);
    $pages[1][3] = array_splice($monsterList, 0, 50);

    $pages[2][0] = array_splice($monsterList, 0, 47);
    $pages[2][1] = array_splice($monsterList, 0, 48);
    $pages[2][2] = array_splice($monsterList, 0, 45);
    $pages[2][3] = array_splice($monsterList, 0, 43);
    
    $pages[3][0] = array_splice($monsterList, 0, 35);
    $pages[3][1] = array_splice($monsterList, 0);
    $pages[3][2] = array_splice($monsterList, 0);
    $pages[3][3] = array_splice($monsterList, 0);
    

    $flag = 0;
    $CRatual = 'inicializacao';
    foreach ($pages as $key1 => $page) {
        echo '<table><tr>';
        foreach ($page as $key2 => $columns) {
            echo '<td width="200">';
            echo '<table>';
            foreach ($columns as $key3 => $monster) {

                if ($CRatual != $monster['Monsters']['cr']) {
                    $CRatual = $monster['Monsters']['cr'];
                    $flag = 0;
                }
                if ($flag == 0) {
                    switch ($monster['Monsters']['cr']) {
                        case '0':
                        case '8':
                        case '12':
                        case '20':
                            echo '<th>CR ' . $monster['Monsters']['cr'] . ' </th>';
                            $flag = 1;
                            break;
                        case '0.125':
                            echo '<th><br>CR 1/8</th>';
                            $flag = 1;
                            break;
                        case '0.25':
                            echo '<th><br>CR 1/4</th>';
                            $flag = 1;
                            break;
                        case '0.5':
                            echo '<th><br>CR 1/2</th>';
                            $flag = 1;
                            break;
                        case '':
                            echo '<th><br>Template</th>';
                            $flag = 1;
                            break;
                        default:
                            echo '<th><br>CR ' . $monster['Monsters']['cr'] . ' </th>';
                            $flag = 1;
                            break;
                    }
                }
                echo '<tr><td>' . $monster['Monsters']['name'] . ', ' . $monster['Monsters']['page'] . '</td></tr>';
            }
            echo '</table>';

            echo '</td>';
        }
        echo '</tr></table>';
    }
   