<?php

include '../class/conexao.php';

function validaForm($form = array(), $acao) {

    $errorForm = array();

    foreach ($form as $key => $value) {
        if (empty($value)) {
            $errorForm['error']['tipo'] = 'campos';
            $errorForm['error']['msg'][] = $key;
        }
    }

    return $errorForm;
}

switch ($_REQUEST['acao']) {

    case 'cadastrar':

        $errorForm = validaForm($_REQUEST['form'], $_REQUEST['acao']);

        if (isset($errorForm['error']['msg'])) {
            echo json_encode($errorForm);
        } else {

            foreach ($_REQUEST['form'] as $key => $value) {
                switch (strtolower($key)) {
                    case 'celular':
                        $_REQUEST['form'][$key] = trim(str_replace('-', '', str_replace('.', '', $value)));
                        break;

                    case 'data_nascimento':
                        $data = new DateTime(str_replace('/', '-', $value));
                        $_REQUEST['form'][$key] = $data->format('Y-m-d');
                        break;

                    default :
                        $_REQUEST['form'][$key] = $value;
                }
            }

            $con = new Conexao();

            $rs = $con->insert(array(
                'data' => $_REQUEST['form'],
                'table' => 'contato'
                    )
            );

            echo $rs;
        }

        break;

    case 'editar':

        $errorForm = validaForm($_REQUEST['form'], $_REQUEST['acao']);

        if(isset($_REQUEST['form']['data_nascimento']) && !empty($_REQUEST['form']['data_nascimento'])) {
            
            $dataNasc = explode('/', $_REQUEST['form']['data_nascimento']);
            $_REQUEST['form']['data_nascimento'] = $dataNasc[2] . '-' . $dataNasc[1]  . '-' .$dataNasc[0];
            
        }
        
        if (isset($errorForm['error']['msg'])) {
            echo json_encode($errorForm);
        } else {
            $con = new Conexao();

            $rs = $con->update(array(
                'data' => $_REQUEST['form'],
                'where' => array(
                    'id_contato' => $_REQUEST['id_contato'],
                ),
                'table' => 'contato'
                    )
            );

            echo $rs;
        }

        break;

    case 'deletar':

        $con = new Conexao();

        $rs = $con->delete(array(
            'where' => array(
                'id_contato' => $_REQUEST['id_contato']
            ),
            'table' => 'contato'
                )
        );

        break;
}
