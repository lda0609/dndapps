<h1>Aventureiros</h1>
<div id="grupo1">
    <form class="pure-form pure-form-aligned">
        <fieldset>
            <div class="pure-control-group">
                <table border="0">
                    <tr>
                        <td>
                           <div class="pure-control-group"><label for="data">Data Da Aventura</label><select id="data">

                                </select></div>
                            <?php
//                            echo $this->Form->input('data da aventura', array('type' => 'select', 'options' => $data_adventures, 'id' => 'data'));
                            ?>
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
    <table id="grupoHeader">

    </table>
    <table id='xpThreshhold'>
    </table>

    <table id='adventurers'>
    </table>

    <div id="saveButtom"></div> 

</div>