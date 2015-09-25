<?php echo $this->element('css'); ?>
<link rel="stylesheet" href="/<?php echo APP_DIR ?>/css/encounterBuilder.css" type="text/css"/> 
<link rel="stylesheet" href="/<?php echo APP_DIR ?>/css/turntracker.css" type="text/css"/> 

<script src="/<?php echo APP_DIR ?>/js/jquery-1.11.2.min.js"></script>
<script src="/<?php echo APP_DIR ?>/js/jquery-ui.js"></script>
<script src="/<?php echo APP_DIR ?>/js/meiomask.min.js"></script>
<script>
    var dnd_xp = JSON.parse('<?php echo json_encode($dnd_xp); ?>');
    var dnd_classes = JSON.parse('<?php echo json_encode($dnd_classes); ?>');
</script>

<!-- tabs -->
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

<script src="/<?php echo APP_DIR ?> /js/EncounterBuilder.js"></script>
<script src="/<?php echo APP_DIR ?>/js/turntracker.js"></script>
