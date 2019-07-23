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
                echo '<td>' . $this->Form->input('Tag', array('type' => 'select', 'options' => $dnd_monster_tag, 'empty' => 'Any', 'id' => 'tag')) . '</td>';
                echo '<td>' . $this->Form->input('Environment', array('type' => 'select', 'options' => $dnd_monster_environments, 'empty' => 'Any', 'id' => 'environment')) . '</td>';
//                echo '<td>' . $this->Form->input('alignment', array('type' => 'select', 'options' => $dnd_alignment, 'empty' => 'Any', 'id' => 'alignment')) . '</td></tr>';
                ?>
            </tbody>
        </table>
    </form>

    <div align="center">
        <button class="button-secondary pure-button" type="button" id="btnLimparConsulta">Limpar</button>
        <button class="pure-button pure-button-primary" type="button" id="btnConsultar">Consultar</button>
        <button class="pure-button pure-button-primary button-warning" type="button" id="btnConsultarFavoritos">Favoritos</button>
    </div>
    <br>
</div>
<div class="modulo">
    <div class="fixed-table-container">
        <div class="header-background"> </div>
        <div class="fixed-table-container-inner">
            <table id="tabResult" class="alternate">
                <thead>
                    <tr>
                        <th width="20px"><div class="th-inner"></div></th>
                        <th width="20px"><div class="th-inner"></div></th>
                        <th><div class="th-inner">Monster</div></th>
                        <th><div class="th-inner">Type</div></th>
                        <th><div class="th-inner">Tag</div></th>
                        <th><div class="th-inner">CR</div></th>
                        <th><div class="th-inner">Size</div></th>
                        <th><div class="th-inner">HP</div></th>
                        <th><div class="th-inner">Alig.</div></th>
                        <th width="90px"><div class="th-inner">Page</div></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
<h1>Encontro </h1>

<div id="msg">
    <table align="center">
        <tbody><tr>
                <td class="msg_sucesso">Encontro salvo com sucesso.</td>
            </tr>
        </tbody></table>
</div>


<div class="modulo">
    <form class="pure-form">
        <table>
            <tr>
                <td><input placeholder="Título do Encontro" id="tituloEncontro" type="text"></td>
                <td><input placeholder="Tesouro"  id="tesouro" type="text"></td>
            </tr>
            <tr>
                <td colspan="2"><input placeholder="Informações Adicionais"  id="information" type="text"></td>
            </tr>
        </table>
    </form>

    <table id="tabEncontro">
        <thead>
            <tr>
                <th width="20px"></th>
                <th>Monster</th>
                <th width="150px">Alias</th>
                <th width="40px">Qtde</th>
                <th>CR</th>
                <th>HP</th>
                <th>XP</th>
                <th>Page</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <table>
        <thead>
            <tr>
                <td width="140px"><strong>Total Encontro: </strong></td>
                <td width="50px" id="totalEncontro"></td>
                <td width="140px"><strong>Adjusted XP:</strong></td>
                <td width="50px" id="adjustedXP"></td>
                <td id="dificuldade"></td>
            </tr>
        </thead>
    </table>


    <div align="center">
        <button class="button-secondary pure-button" type="button" id="btnLimparEncontro">Limpar Encontro</button>
        <button class="pure-button pure-button-primary" type="button" id="btnSalvarEncontro">Salvar Encontro</button>
    </div>
</div>
