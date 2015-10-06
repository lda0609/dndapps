<?php echo $this->element('css'); ?>
<link rel="stylesheet" href="/<?php echo APP_DIR ?>/css/characters.css" type="text/css"/> 
<?php echo $this->element('javascript'); ?>

<script>
    var dnd_alignment_players = JSON.parse('<?php echo json_encode($dnd_alignment_players); ?>');
    var dnd_classes = JSON.parse('<?php echo json_encode($dnd_classes); ?>');
</script>

<!-- tabs -->
<div id="tabs">
    <ul>
        <li><a id="t1" href="#aventureiros">Personagens</a></li>
        <li><a id="t2" href="#novoPersonagem">Editar</a></li>
    </ul>
    <!-- panes -->
    <div id="aventureiros">
        <?php echo $this->element('characters/characters'); ?>
    </div>
    <div id="novoPersonagem">
        <?php echo $this->element('characters/novoPersonagem'); ?>
    </div>
</div>

<script src="/<?php echo APP_DIR ?> /js/characters.js"></script>
