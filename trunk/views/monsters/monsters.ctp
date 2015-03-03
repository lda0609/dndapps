<?php
echo $this->Form->create('monsters');
?>

<table class="form_tabela" cellspacing="2">
    <tbody>
        <?php
        echo $this->Form->input('monster', array('type' => 'select', 'options' => $dnd_monsters, 'empty' => 'Selecione'));
        ?>
    </tbody>
</table>

<div align="center">
    <?php echo $this->Form->button('Consultar', array('type' => 'submit')); ?>
</div>
