<style>
  table tr td {
    background: #fff;
    padding: 1px;
    text-align: left;
    vertical-align: top;
    border-bottom: 1px solid #ddd;
  }

  form div {
    clear: both;
    margin-bottom: 1em;
    padding: 0em;
    vertical-align: text-top;
  }

  .monster-name {
    font-size: 110%;
    padding: 0%;
    width: 300px;
  }

  .monster-data {
    font-size: 110%;
    padding: 0%;
    width: 300px;
  }
</style>

<?php echo $this->element('css'); ?>
<?php echo $this->element('javascript'); ?>

<?php
echo $this->Form->create('Monsters');
$monster_size = array('Tiny' => 'Tiny', 'Small' => 'Small', 'Medium' => 'Medium', 'Large' => 'Large', 'Huge' => 'Huge', 'Gargantuan' => 'Gargantuan');
?>

<table class="form_tabela" cellspacing="2">
  <tr>
    <td><?php echo $this->Form->input('name', array('type' => 'text', 'class' => 'monster-name')); ?></td>
  </tr>
  <tr>
    <td><?php echo $this->Form->input('size', array('type' => 'select', 'options' => $monster_size, 'class' => 'monster-data', 'selected'=>'Medium')); ?></td>
  </tr>
  <tr>
    <td>
      <?php
      echo $this->Form->input('type', array(
        'type' => 'select',
        'options' => $dnd_monster_type,
      ));
      ?>
    </td>
  </tr>
  <tr>
    <td>
      <?php
      echo $this->Form->input('tag', array(
        'type' => 'select',
        'options' => $dnd_monster_tag,
        'empty' => ''
      ));
      ?>
    </td>
  </tr>
  <tr>
    <td><?php echo $this->Form->input('alignment', array('type' => 'select', 'options' => $dnd_alignment, 'class' => 'monster-data')); ?></td>
  </tr>
  <tr>
    <td><?php echo $this->Form->input('hp', array('type' => 'text', 'class' => 'monster-data')); ?></td>
  </tr>
  <tr>
    <td><?php echo $this->Form->input('cr', array('type' => 'text', 'class' => 'monster-data')); ?></td>
  </tr>
  <tr>
    <td><?php echo $this->Form->input('page', array('type' => 'text', 'class' => 'monster-data')); ?></td>
  </tr>
  <tr>
    <td><?php echo $this->Form->input('book', array('type' => 'select', 'options' => array('MME1' => 'MME1', 'MToF' => 'MToF', 'Volo' => 'Volo', 'OotA' => 'OotA', 'MM' => 'MM'), 'class' => 'monster-data')); ?></td>
  </tr>

</table>
<div align="center">
  <?php echo $this->Form->button('Salvar', array('type' => 'submit')); ?>
</div>