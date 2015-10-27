<?php

class LauncherController extends AppController
{

    var $uses = array('Patch');

    function index()
    {
        $myfile = fopen("patch/version.txt", "r") or die("Unable to open file!");
        $version = fgets($myfile);
        fclose($myfile);

        $patch_version = $this->Patch->find('first', array('conditions' => array('id' => '1')));
        if (!empty($patch_version) && $patch_version['Patch']['version'] !== $version) {
            $this->set('newPatch', 1);
        } else {
            $this->set('newPatch', false);
        }
    }

}
