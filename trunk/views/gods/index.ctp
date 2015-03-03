<?php
echo $this->Form->create('gods');
?>

<table class="form_tabela" cellspacing="2">
    <tbody>
        <?php
        echo $this->Form->input('god', array('type' => 'select', 'options' => $dnd_gods, 'empty' => 'Selecione'));
        ?>
    </tbody>
</table>

<div align="center">
    <?php echo $this->Form->button('Consultar', array('type' => 'submit')); ?>
</div>
