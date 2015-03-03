<?php
echo $this->Form->create('monster', array('action' => 'save'));
?>

<div align="center">
    <?php echo $this->Form->button('Consultar', array('type' => 'submit')); ?>
</div>

<h1><?php echo $dnd_monsters[$monster['Monsters']['id']] . ' - ' . $monster['Monsters']['id'] . ' - page ' . $monster['Monsters']['page']; ?></h1>
<table class="form_tabela" cellspacing="2">
    <tr>
        <?php
        echo '<td>';
        echo $this->Form->input('monsterType', array(
            'type' => 'select',
            'multiple' => 'checkbox',
            'options' => $dnd_monster_type,
            'selected' => $monsterType,
        ));
        $monster_size = array('Tiny' => 'Tiny', 'Small' => 'Small', 'Medium' => 'Medium', 'Large' => 'Large', 'Huge' => 'Huge', 'Gargantuan' => 'Gargantuan');
        echo '</td><td>';
        echo $this->Form->input('size', array(
            'type' => 'radio',
            'options' => $monster_size,
            'value' => $monster['Monsters']['size'],
        ));
        echo '</td>';
        echo '</td><td>';
        echo $this->Form->input('alignment', array(
            'type' => 'radio',
            'options' => $dnd_alignment,
            'value' => $monster['Monsters']['alignment'],
        ));
        echo '</td>';
        echo $this->Form->hidden('monster', array('value' => $monster['Monsters']['id']));
        ?>

    </tr>
</table>

