<?php

//Passos para criar um novo patch:
//scripts para executar no banco devem ser incluídos manualmente no arquivo scripts.txt, se não houver alterações deixar o arquivo em branco. 
//Para incluir novos monstros deve ser usada a função "create" abaixo. Editar as variáveis de acordo com a necessidade. 
//Atualizar o arquivo version.txt com a nova versão. 
//
//Para aplicar o patch, executar a função index diretamente ou pelo Launcher. 

class PatcherController extends AppController
{

    var $uses = array('Monsters', 'MonsterTypes', 'MonsterFavorites', 'Patch');
    
    function index()
    {
        $myfile = fopen("patch/version.txt", "r") or die("Unable to open file!");
        $version = fgets($myfile);
        fclose($myfile);
        $patch_version = $this->Patch->find('first', array('conditions' => array('id' => '1')));
        if (!empty($patch_version) && $patch_version['Patch']['version'] !== $version) {
            $myfile = fopen("patch/scripts.txt", "r") or die("Unable to open file!");
            while (!feof($myfile)) {
                $query = fgets($myfile);
                $this->Monsters->query($query);
            }
            fclose($myfile);

            $mypatch = fopen("patch/patch.txt", "r") or die("Unable to open file!");
            $patch_data = unserialize(fgets($mypatch));
            foreach ($patch_data as $key => $record) {
                $this->Monsters->saveAll($record);
            }
            $patch_data = unserialize(fgets($mypatch));
            $this->MonsterTypes->saveAll($patch_data);
            fclose($patch_data);

            $this->Patch->save(array('Patch' => array('id' => '1', 'version' => $version)));
            $this->set('mensagem', 'Patch versão ' . $version . ' instalado');
        } else {
            $this->set('mensagem', 'A aplicação já está com o patch mais recente instalado');
        }
    }

    //executar essa função para criar patch com os novos monstros. 
    function create()
    {

        $monster_id = '455'; //id do primeiro novo monstro
        $monsterTypes_id = '479'; //id da primeira nova relação "monster->types"

        $monsters = $this->Monsters->find('all', array(
            'conditions' => array(
                'id >=' => $monster_id
        )));
        $monsterTypes = $this->MonsterTypes->find('all', array(
            'conditions' => array(
                'AND' => array(
                    array('dnd_monsters_id <' => $monster_id),
                    array('id >' => $monsterTypes_id),
                )
        )));
        $myfile = fopen("patch/patch.txt", "w") or die("Unable to open file!");
        fwrite($myfile, serialize($monsters) . PHP_EOL);
        fwrite($myfile, serialize($monsterTypes) . PHP_EOL);
        fclose($myfile);
        $myfile = fopen("patch/patch.txt", "r") or die("Unable to open file!");
        $temp_read = fgets($myfile);
        $temp_read = unserialize($temp_read);
        debug($temp_read);
        $temp_read = fgets($myfile);
        $temp_read = unserialize($temp_read);
        debug($temp_read);
    }

}
