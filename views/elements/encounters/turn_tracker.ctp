<div id="divMenuTracker">
    <table>
        <tr>
            <td class="button-menu"><a onclick="loadPlayers()"><img id="imgLoadPlayers" height="60px" src="/dndapps/img/adventurer.jpg" title="Adicionar Aventureiros" class="clickable"></a></td>
            <td class="button-menu"><a onclick="startCombat()"><img id="imgStartCombat" height="60px" src="/dndapps/img/combat.png" title="Iniciar Combate" class="clickable button-disabled"></a></td>
            <td class="button-menu"><a onclick="nextTurn()"><img id="imgNextTurn" height="60px" src="/dndapps/img/red_next.png" title="Próximo Turno" class="clickable button-disabled"></a></td>
            <td class="button-menu"><a onclick="limparTracker()"><img id="imgLimparTracker" height="60px" src="/dndapps/img/red_stop_playback.png" title="Parar Encontro" class="clickable button-disabled"></a></td>
            <td class="button-menu"></td>
            <td></td>
        </tr>
    </table>


</div>

<div id="divTurnTracker">
    <table id="tableTracker" class="pure-table">
        <thead>
            <tr>
                <th align="center">Init <a id="toggleLockInit" class="unlocked" href="#"><i class="fa fa-unlock-alt"></i></a></th>
                <th align="center">Player</th>
                <th align="center">HP alternativo</th>
                <th align="center">HP mod <a id="toggleLockHPMod" class="locked" href="#"><i class="fa fa-lock"></i></a></th>
            </tr>
        </thead>
        <tbody id="tbodyTracker" class="sortable"></tbody>
    </table>
    <div id="divInformation">

    </div>
    <br>
    <button id="btnOdenar" class="pure-button pure-button-primary">Ordenar por iniciativa</button>
    <button id="btnDamage" class="pure-button button-damage">Damage</button>
    <button id="btnHeal" class="pure-button button-heal"><i class="fa fa-medkit"> Heal</i></button>
    <button id="btnHPTemp" class="pure-button button-hptemp">HP Temp</button>
</div>
