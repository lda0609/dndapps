<?php
echo $this->Form->create('god', array('action' => 'save'));
?>

<h1><?php echo $dnd_gods[$god]; ?></h1>
<table class="form_tabela" cellspacing="2">
    <tbody>
        <?php
        echo $this->Form->input('godDomains', array(
            'type' => 'select',
            'multiple' => 'checkbox',
            'options' => $dnd_domains,
            'selected' => $godDomains,
        ));
        echo $this->Form->hidden('god', array('value' => $god));
        ?>
    </tbody>
</table>

<div align="center">
    <?php echo $this->Form->button('Consultar', array('type' => 'submit')); ?>
</div>
