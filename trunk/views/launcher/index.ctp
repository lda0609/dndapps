<?php echo $this->element('css'); ?>
<?php echo $this->element('javascript'); ?>

<style>
    #rcorners {
        border-radius: 35px;
        border: 2px solid white;
        /*background-color: #6EA7D2;*/
        background: url(webroot/img/dragon4.jpg);
        background-position: left top;
        background-repeat: round;
        padding: 20px; 
        width: 800px;
        height: 480px; 
    }

    .outer {
        display: table;
        position: absolute;
        height: 100%;
        width: 100%;
    }

    .middle {
        display: table-cell;
        vertical-align: middle;
    }

    .inner {
        margin-left: auto;
        margin-right: auto; 
    }

    .button-launcher {
        border-radius: 30px;
        font-size: 2em;
        width: 240px;
        -webkit-filter: opacity(100%);
    }

    #Patch{
        float: right;
    }
</style>


<div class="outer">
    <div class="middle">
        <div id="rcorners" class="inner">
            <button id="Personagens" class="button-success pure-button button-launcher" onClick="window.open('http://localhost/dndapps/characters/');">Characters</button>
            <br><br>
            <button id="Aventura" class="button-error pure-button button-launcher" onClick="window.open('http://localhost/dndapps/encounters/');">Adventure</button>
            <br><br><br><br><br><br><br><br>
            <br><br><br><br><br><br><br><br><br>
            <?php
            debug($newPatch);
            if ($newPatch) {
                ?>
                <button id="Patch" class="button-warning pure-button button-launcher" onClick="window.open('http://localhost/dndapps/patcher/');">Novo Patch</button>
            <?php } ?>
        </div>
    </div>
</div>
