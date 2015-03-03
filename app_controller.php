<?php

//App::import('Vendor', 'oauth', array('file' => 'oauth' . DS . 'oauth_consumer.php'));
//App::import('Vendor', 'Pinhao.cel_format');

class AppController extends Controller
{

    var $components = array('TabelasAuxiliares');

    function mostraMsg($codMsg = '', $param = null)
    {
        $strMsg = '';

        switch ($codMsg) {
            //	CONFIRMAÇÕES
            case 1001:
                $strMsg = 'Os dados foram cadastrados com sucesso.';
                break;
            case 1002:
                $strMsg = 'Os dados foram alterados com sucesso.';
                break;
            case 1003:
                $strMsg = 'Os dados foram excluídos com sucesso.';
                break;

            //  AVISOS
            case 2001:
                $strMsg = 'Pelo menos um campo deve ser preenchido';
                break;

            // ERROS
            case 3001:
                $strMsg = 'Os dados não foram cadastrados, tente novamente.';
                break;
        }

        return substr($codMsg, 0, 1) . '|' . $strMsg;
    }

}
