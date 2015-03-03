<?php
echo $this->Form->create('spells', array('action' => 'saveClassSpells'));
echo $this->Form->input('spells', array(
    'type' => 'select',
    'multiple' => 'checkbox',
    'options' => $spellList,
    'selected' => $classSpells,
));
echo $this->Form->hidden('classId', array('value' => $classId));
?>
<div align = "center">
    <?php echo $this->Form->button('Salvar', array('type' => 'submit')); ?>
</div>

?>