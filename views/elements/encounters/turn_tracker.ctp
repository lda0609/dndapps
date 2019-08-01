<div id="divMenuTracker">
	<a onclick="loadPlayers()"><img id="imgLoadPlayers" height="60px" src="/dndapps/img/adventurer.jpg" title="Adicionar Aventureiros (alt-a)" class="clickable button-menu"></a>
	<a onclick="startCombat()"><img id="imgStartCombat" height="60px" src="/dndapps/img/combat.png" title="Iniciar Combate (alt-s)" class="clickable button-disabled button-menu"></a>
	<a onclick="nextTurn()"><img id="imgNextTurn" height="60px" src="/dndapps/img/red_next.png" title="Próximo Turno (alt-n, alt-')" class="clickable button-disabled button-menu"></a>
	<a onclick="limparTracker()"><img id="imgLimparTracker" height="60px" src="/dndapps/img/red_stop_playback.png" title="Parar Encontro (alt-p)" class="clickable button-disabled button-menu"></a>
	<img id="imgInformation" height="60px" src="/dndapps/img/information.png" title="Ver informações extras abaixo" class="clickable button-menu" style="display: none;">
</div>
<hr class="hr-top">

<div id="divTurnTracker">
    <table id="tableTracker" class="pure-table">
        <thead>
            <tr>
                <th align="center">Init<a id="toggleLockInit" class="unlocked" href="#"><i class="fas fa-unlock-alt"></i></a></th>
                <th align="center" colspan="2">Player</th>
                <th align="center">HP</th>
                <th align="center">HP mod<a id="toggleLockHPMod" class="locked" href="#"><i class="fas fa-lock"></i></a></th>
            </tr>
        </thead>
        <tbody id="tbodyTracker" class="sortable"></tbody>
    </table>
    <hr class="hr-bottom">

    <br>
    <span title="alt-o"><button id="btnOdenar" class="pure-button pure-button-primary clickable"><i class="fas fa-sort-numeric-desc"> Ordenar por iniciativa</i></button></span>
    <p align="center">
        <span title="alt-1"><button id="btnDamage" class="pure-button button-damage clickable"><i class="fas fa-bomb"> Damage</i></button></span>
        <span title="alt-2"><button id="btnHeal" class="pure-button button-heal clickable"><i class="fas fa-medkit"> Heal</i></button></span>
        <span title="alt-3"><button id="btnHPTemp" class="pure-button button-hptemp clickable"><i class="fas fa-shield"> HP Temp</i></button></span>
    </p>
</div>
