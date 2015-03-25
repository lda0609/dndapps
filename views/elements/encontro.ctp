<h1>Parâmetros de busca </h1>
<div class="modulo">
    <form class="pure-form">
        <table class="form_tabela" cellspacing="2">
            <tbody>
                <?php
                foreach ($dnd_xp as $cr => $xp) {
                    $cr_list[$cr] = $cr;
                }
                echo $this->Form->input('name', array('id' => 'monsterName', 'type' => 'text'));
                echo '<tr><td>' . $this->Form->input('CRMin', array('type' => 'select', 'options' => $cr_list, 'empty' => 'Any', 'id' => 'crMin', 'label' => 'CR Min')) . '</td>';
                echo '<td>' . $this->Form->input('CRMax', array('type' => 'select', 'options' => $cr_list, 'empty' => 'Any', 'id' => 'crMax', 'label' => 'CR Max')) . '</td>';
                echo '<td>' . $this->Form->input('Type', array('type' => 'select', 'options' => $dnd_monster_type, 'empty' => 'Any', 'id' => 'type')) . '</td>';
                echo '<td>' . $this->Form->input('alignment', array('type' => 'select', 'options' => $dnd_alignment, 'empty' => 'Any', 'id' => 'alignment')) . '</td></tr>';
                ?>
            </tbody>
        </table>
    </form>

    <div align="center">
        <button class="pure-button pure-button-primary" type="button" id="btnConsultar">Consultar</button>
        <button class="button-secondary pure-button" type="button" id="btnLimparConsulta">Limpar</button>
    </div>
    <br>
</div>
<div class="modulo">
    <div class="fixed-table-container">
        <div class="header-background"> </div>
        <div class="fixed-table-container-inner">
            <table id="tabResult">
                <thead>
                    <tr>
                        <th width="20px"><div class="th-inner"></div></th>
                <th><div class="th-inner">Monster</div></th>
                <th><div class="th-inner">Type</div></th>
                <th><div class="th-inner">CR</div></th>
                <th><div class="th-inner">Size</div></th>
                <th><div class="th-inner">Alignment</div></th>
                <th><div class="th-inner">Page</div></th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
<h1>Encontro </h1>
<div class="modulo">

    <table id="tabEncontro">
        <thead>
            <tr>
                <th width="20px"></th>
                <th>Monster</th>
                <th width="40px">Qtde</th>
                <th>CR</th>
                <th>Page</th>
                <th>XP</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
    <table>
        <thead>
            <tr>
                <td width="160px"><strong>Total Encontro: </strong></td>
                <td width="40px" id="totalEncontro"></td>
                <td id="dificuldade"></td>
            </tr>
        </thead>
    </table>
    <div align="center">
        <button class="button-secondary pure-button" type="button" id="btnLimparEncontro">Limpar Encontro</button>
    </div>
</div>