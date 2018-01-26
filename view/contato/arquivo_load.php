
<?php
include '../parts/header.html';
include '../parts/menu.php';
include '../../class/conexao.php';

$con = new Conexao();
?>

<div class="container">

    <div class="row row-offcanvas row-offcanvas-right">

        <div class="col-xs-12 col-sm-2 sidebar-offcanvas" id="sidebar">

            <div class="list-group">
                <a href="../contato/lista_contato.php" class="list-group-item">Lista de Contatos</a>
                <a href="../contato/cadastro_contato.php" class="list-group-item">Novo Contato</a>
            </div>

        </div>

        <div class="col-xs-12 col-sm-10">

            <p class="pull-right visible-xs">
                <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Menu Lateral</button>
            </p>                    

            <div class="row">                

                <div class="jumbotron">
                    <h2><b>Arquivo Carregado !</b></h2>
                    <p>Os contatos existentes no arquivo foram salvos com sucesso.</p>
                    <p><a class="btn btn-primary btn-lg" href="../contato/lista_contato.php" role="button">Lista de Contatos</a></p>
                </div>

            </div>
        </div>

    </div>
</div>