<h1>Aventureiros</h1>
<div id="grupo1">
    <form class="pure-form pure-form-aligned">
        <fieldset>
            <div class="pure-control-group">
                <table border="0">
                    <tr>
                        <td>
                            <div class="pure-control-group"><label for="data">Data Da Aventura</label><select id="data"></select></div>
                        </td>
                        <td>
                            <div class="pure-control-group"><button class="pure-button pure-button-primary button-error" type="button" id="btnNovaAventura">Nova Aventura</button></div>
                        </td>
                    </tr>
                </table>
            </div>
        </fieldset>
    </form>
</div>
<div id="grupo2">
    <div class="pure-control-group"><label for="dataAventura">Data da Aventura:</label>  <input type="text" class="datepicker" id="dataAventura"></div>
</div>

<div id="conteudo_corpo">
    <table id='xpThreshhold' class="alternate">
    </table>

    <table id='adventurers' class="alternate">
    </table>

    <div id="saveButtom"></div> 
    <div id="divAtualizarAventura" align="right"></div>

</div>
<div id="confirmaXP" title="Confirmar Alteração XP">
    <p>A atualização é definitiva para todos os personagens. Campos vazios serão considerados como zero XP. </p>
</div>
<div style="display: none;">
    <button id="confirmaXPopener">Open Dialog</button>
</div>