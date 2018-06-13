<?php
echo $this->Form->create('Monsters');
?>
<table class="form_tabela" cellspacing="2">
    <tbody>
        <tr>
            <td>
                <?php
                echo $this->Form->input('id', array('type' => 'select', 'options' => $monsterList, 'empty' => 'Selecione'));
                ?>
            </td>
            <td>
                <?php echo $this->Form->button('Consultar', array('type' => 'submit')); ?>
            </td>
        </tr>
    </tbody>
</table>
<?php
echo $this->Form->end();
?>

<?php if (isset($monster)) { ?>
    <?php
//    echo $this->Form->create('Monsters', array('action' => 'update'));
    ?>
    <form action="/dndapps/monsters/update" id="MonstersIndexForm" method="post" accept-charset="utf-8">
        <div align="center">
            <?php echo $this->Form->button('Atualizar', array('type' => 'submit')); ?>
        </div>
        <table class="form_tabela" cellspacing="2">
            <tr>
                <?php
                echo '<td>';
                echo $this->Form->input('monsterType', array(
                    'name' => 'data[MonsterTypes][dnd_type_id]',
                    'type' => 'select',
                    'multiple' => 'checkbox',
                    'options' => $dnd_monster_type,
                    'selected' => $monsterType,
                ));
                $monster_size = array('Tiny' => 'Tiny', 'Small' => 'Small', 'Medium' => 'Medium', 'Large' => 'Large', 'Huge' => 'Huge', 'Gargantuan' => 'Gargantuan');
                echo '</td><td>';
                echo $this->Form->input('size', array(
                    'name' => 'data[Monsters][size]',
                    'type' => 'radio',
                    'options' => $monster_size,
                    'value' => $monster['Monsters']['size'],
                ));
                echo '</td>';
                echo '</td><td>';
                echo $this->Form->input('alignment', array(
                    'name' => 'data[Monsters][alignment]',
                    'type' => 'radio',
                    'options' => $dnd_alignment,
                    'value' => $monster['Monsters']['alignment'],
                ));
                echo $this->Form->hidden('id', array(
                    'name' => 'data[Monsters][id]',
                    'value' => $monster['Monsters']['id']
                ));
                echo '</td>';
                echo '<td>';
                echo $this->Form->input('monsterEnvironment', array(
                    'name' => 'data[MonsterEnvironments][dnd_environments_id]',
                    'type' => 'select',
                    'multiple' => 'checkbox',
                    'options' => $dnd_monster_environments,
                    'selected' => $monsterEnv,
                ));
                echo '</td>';

                ?>
            </tr>
        </table>
    </form>
<?php } ?>