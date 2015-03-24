<?php echo $this->element('css'); ?>
<link rel="stylesheet" href="/<?php echo APP_DIR ?>/css/redmond/jquery-ui-1.10.4.custom.css" type="text/css"/> 
<script type="text/javascript" src="/<?php echo APP_DIR ?>/js/jquery-1.11.2.min.js"></script>
<script src="/<?php echo APP_DIR ?>/js/jquery-ui.js"></script>
<script>
    $(function () {
        $("#tabs").tabs();
    });
    var dnd_xp = JSON.parse('<?php echo json_encode($dnd_xp); ?>');
    var dnd_classes = JSON.parse('<?php echo json_encode($dnd_classes); ?>');
</script>

<!-- tabs -->
<div id="tabs">
    <ul>
        <li><a id="t1" href="#aventureiros">Grupo</a></li>
        <li><a id="t2" href="#encontro">Montar Encontro</a></li>
    </ul>
    <!-- panes -->
    <div id ="encontro">
        <?php echo $this->element('encontro'); ?>
    </div>
    <div id ="aventureiros">
        <?php echo $this->element('aventureiros'); ?>
    </div>

</div>

<script type="text/javascript" src="/<?php echo APP_DIR ?> /js/EncounterBuilder.js"></script>