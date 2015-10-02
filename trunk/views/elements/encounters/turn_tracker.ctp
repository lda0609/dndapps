<div id="divMenuTracker">
    <a onclick="loadPlayers()"><img height="60px" src="/dndapps/img/adventurer.jpg" title="Adicionar Aventureiros" class="clickable"></a>
    <a onclick="limparTracker()"><img height="70px" src="/dndapps/img/dragon.jpg" title="Limpar Encontro" class="clickable"></a>
</div>

<div id="divTurnTracker">
    <table id="tableTracker" class="pure-table">
        <thead>
            <tr>
                <th align="center">Init <a id="toggleLockInit" class="unlocked" href="#"><i class="fa fa-unlock-alt"></i></a></th>
                <th align="center">Player</th>
                <th align="center">HP alternativo</th>
                <th align="center">HP mod <a id="toggleLockHPMod" class="locked" href="#"><i class="fa fa-lock"></i></a></th>

            <tr>
        </thead>


    </table>
    <div id="divInformation">

    </div>
    <br>
    <button id="btnOdenar" class="pure-button pure-button-primary">Ordenar por iniciativa</button>
    <button id="btnDamage" class="pure-button button-damage">Damage</button>
    <button id="btnHeal" class="pure-button button-heal"><i class="fa fa-medkit"> Heal</i></button>
    <button id="btnHPTemp" class="pure-button button-hptemp">HP Temp</button>
</div>
