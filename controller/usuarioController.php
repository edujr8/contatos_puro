<?php

include '../class/conexao.php';

function validaForm($form = array(), $acao) {

    $errorForm = array();
    $isvalid = true;

    if (!empty($form['senha'])) {
        if ($form['senha'] !== $form['confirmar']) {
            $errorForm['error']['tipo'] = 'senha';
            $errorForm['error']['msg'] = 'As senhas informadas são diferentes';
            $isvalid = false;
        }
    }

    if (($acao === 'cadastrar' OR $acao === 'esqueci_senha') AND empty($form['senha'])) {
        $errorForm['error']['tipo'] = 'campos';
        $errorForm['error']['msg'][] = 'senha ';
    }

    if ($isvalid) {
        foreach ($form as $key => $value) {
            if ($key !== 'senha' AND $key !== 'confirmar') {
                if (empty($value)) {
                    $errorForm['error']['tipo'] = 'campos';
                    $errorForm['error']['msg'][] = $key;
                }
            }
        }
    }

    return $errorForm;
}

switch ($_REQUEST['acao']) {

    case 'cadastrar':

        $errorForm = validaForm($_REQUEST['form'], $_REQUEST['acao']);

        if (!empty($_REQUEST['form']['login'])) {
            $con = new Conexao();

            $rs = $con->select(
                    array(
                        'table' => 'usuario',
                        'data' => array(
                            'login' => $_REQUEST['form']['login']
                        )
                    )
            );

            if ($rs->fetch()) {
                $errorForm['error']['tipo'] = 'login';
                $errorForm['error']['msg'] = 'O login escolhido já existe, por favor escolha outro.';
            }
        }

        if (isset($errorForm['error']['msg'])) {
            echo json_encode($errorForm);
        } else {

            $_REQUEST['form']['senha'] = md5($_REQUEST['form']['senha']);
            unset($_REQUEST['form']['confirmar']);

            $rs = $con->insert(array(
                'data' => $_REQUEST['form'],
                'table' => 'usuario'
                    )
            );

            echo $rs;
        }

        break;

    case 'editar':

        $errorForm = validaForm($_REQUEST['form'], $_REQUEST['acao']);

        if (empty($_REQUEST['form']['senha'])) {
            unset($_REQUEST['form']['senha']);
        } else {
            $_REQUEST['form']['senha'] = md5($_REQUEST['form']['senha']);
        }

        if (isset($errorForm['error']['msg'])) {
            echo json_encode($errorForm);
        } else {
            $con = new Conexao();

            unset($_REQUEST['form']['confirmar']);

            $rs = $con->update(array(
                'data' => $_REQUEST['form'],
                'where' => array(
                    'id_usuario' => $_REQUEST['id_usuario'],
                ),
                'table' => 'usuario'
                    )
            );

            echo $rs;
        }

        break;

    case 'deletar':

        $con = new Conexao();

        $rs = $con->delete(array(
            'where' => array(
                'id_usuario' => $_REQUEST['id_usuario']
            ),
            'table' => 'usuario'
                )
        );

        break;

    case 'logar':

        $con = new Conexao();

        $rs = $con->select(
                array(
                    'table' => 'usuario',
                    'data' => array(
                        'login' => $_REQUEST['login'],
                        'senha' => md5($_REQUEST['senha'])
                    )
                )
        );

        $data = $rs->fetchAll();

        if ($data) {

            session_start();
            $_SESSION['usuario'] = $data[0];

            echo '1';
        } else {
            $errorForm['error']['tipo'] = 'login';
            $errorForm['error']['msg'] = 'Login ou Senha estão inválidos.';

            echo json_encode($errorForm);
        }

        break;

    case 'deslogar':

        if (session_status()) {
            session_start();
            unset($_SESSION['usuario']);
            session_destroy();
        }

        break;

    case 'esqueci_senha':

        $errorForm = validaForm($_REQUEST['form'], $_REQUEST['acao']);

        if (isset($errorForm['error']['msg'])) {
            echo json_encode($errorForm);
        } else {

            $con = new Conexao();

            $rs = $con->select(
                    array(
                        'table' => 'usuario',
                        'data' => array(
                            'login' => $_REQUEST['form']['login']
                        )
                    )
            );

            $data = $rs->fetchAll();

            if (empty($data)) {
                $errorForm['error']['tipo'] = 'login';
                $errorForm['error']['msg'] = 'Login não encontrado';

                echo json_encode($errorForm);
            } else {

                $_REQUEST['form']['senha'] = md5($_REQUEST['form']['senha']);
                unset($_REQUEST['form']['confirmar']);

                $rs = $con->update(array(
                    'data' => $_REQUEST['form'],
                    'where' => array(
                        'login' => $_REQUEST['form']['login'],
                    ),
                    'table' => 'usuario'
                        )
                );

                echo $rs;
            }
        }

        break;
}
