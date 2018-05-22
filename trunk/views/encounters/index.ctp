<?php echo $this->element('css'); ?>
<link rel="stylesheet" href="/<?php echo APP_DIR ?>/css/encounterBuilder.css" type="text/css"/> 
<link rel="stylesheet" href="/<?php echo APP_DIR ?>/css/turntracker.css" type="text/css"/> 
<?php echo $this->element('javascript'); ?>


<script>
    var dnd_xp = JSON.parse('<?php echo json_encode($dnd_xp); ?>');
    var dnd_classes = JSON.parse('<?php echo json_encode($dnd_classes); ?>');
    var dnd_skills = JSON.parse('<?php echo json_encode($dnd_skills); ?>');
    var dnd_alignment_players = JSON.parse('<?php echo json_encode($dnd_alignment_players); ?>');
</script>

<table id="table_body">
    <tr>
        <td id="td_left_size"  width="25%">
        </td>
        <!-- tabs -->
        <td id="td_tabs" width="50%">
            <div id="tabs">
                <ul>
                    <li><a id="t1" href="#aventureiros">Grupo</a></li>
                    <li><a id="t2" href="#criar_encontro">Criar Encontros</a></li>
                    <li><a id="t3" href="#consultar_aventura">Consultar Aventura</a></li>
                    <li><a id="t4" href="#turn_tracker">Turn Tracker</a></li>
                </ul>
                <!-- panes -->
                <div id ="aventureiros">
                    <?php echo $this->element('encounters/aventureiros'); ?>
                </div>
                <div id ="criar_encontro">
                    <?php echo $this->element('encounters/criar_encontro'); ?>
                </div>
                <div id ="consultar_aventura">
                    <?php echo $this->element('encounters/consultar_aventura'); ?>
                </div>
                <div id ="turn_tracker">
                    <?php echo $this->element('encounters/turn_tracker'); ?>
                </div>
            </div>
        </td>
        <td id="td_right_size"  width="25%">
        </td>
    </tr>
    <tr>
        <td colspan id="td_footer" class="td_footer"></td>
        <td colspan id="td_footer_info" class="td_footer"></td>
        <td colspan class="td_footer" ></td>
    </tr>
</table>

<script src="/<?php echo APP_DIR ?> /js/EncounterBuilder.js?122"></script>
<script src="/<?php echo APP_DIR ?>/js/turntracker.js"></script>
