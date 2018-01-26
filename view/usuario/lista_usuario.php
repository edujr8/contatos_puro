<?php
include '../parts/header.html';
include '../parts/menu.php';
include '../../class/conexao.php';

$con = new Conexao();

if (isset($_REQUEST['acao']) AND $_REQUEST['acao'] === 'filtro') {
    if ($_REQUEST['form']['campo'] AND $_REQUEST['form']['valor']) {
        $rs = $con->query("SELECT * FROM usuario WHERE " . $_REQUEST['form']['campo'] . ' LIKE "%' . $_REQUEST['form']['valor'] . '%"');
    } else {
        $rs = $con->select(array('table' => 'usuario',));
    }
} else {
    $rs = $con->select(array('table' => 'usuario',));
}

$data = $rs->fetchAll();
?>

<div class="container">

    <div class="row row-offcanvas row-offcanvas-right">

        <!--Menu Lateral-->
        <div class="col-xs-12 col-sm-2 sidebar-offcanvas" id="sidebar">

            <div class="list-group">
                <a href="../usuario/lista_usuario.php" class="list-group-item active">Lista de Usuários</a>
                <?php if ($_SESSION['usuario']['tipo_login'] == 1) { ?>
                    <a href="../usuario/cadastro_usuario.php" class="list-group-item">Novo Usuário</a>
                <?php } ?>
            </div>

            <form class="box-filtro" method="POST">

                <input type="hidden" name="acao" value="filtro" />

                <div class="form-group">
                    <input type="text" name="form[valor]" class="form-control" placeholder="">
                </div>

                <div class="form-group">
                    <select class="form-control" name="form[campo]">
                        <option value="">Selecione...</option>
                        <option value="nome">Nome</option>
                        <option value="login">Login</option>
                    </select>
                </div>                

                <button type="submit" class="btn btn-info" style="width: 100%"> 
                    <i class="glyphicon glyphicon-filter"></i> Filtrar
                </button>

            </form>

        </div>

        <!--Conteiner Central-->
        <div class="col-xs-12 col-sm-10">

            <p class="pull-right visible-xs">
                <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Menu Lateral</button>
            </p> 

            <h3>
                <b>Lista de Usuários</b>
            </h3>

            <hr>
            <?php if ($data) { ?>
                <table class="table table-hover table-responsive table-condensed"> 
                    <thead> 
                        <tr> 
                            <th class="text-center">ID</th> 
                            <th width="30%">Nome</th> 
                            <th width="20%">Login</th> 
                            <th width="20%">Tipo Usuário</th> 
                            <th></th>
                        </tr> 
                    </thead> 

                    <tbody> 
                        <?php
                        foreach ($data as $row) {
                            ?>

                            <tr> 
                                <th class="text-center">
                                    <?php echo $row['id_usuario'] ?>
                                </th> 
                                <td>
                                    <?php echo $row['nome'] ?>
                                </td> 
                                <td>
                                    <?php echo $row['login'] ?>
                                </td> 
                                <td>
                                    <?php echo ($row['tipo_login'] === '1') ? 'Administrador' : 'Usuário' ?>
                                </td> 
                                <td class="text-right">
                                    <?php if ($_SESSION['usuario']['tipo_login'] == 1) { ?>
                                        <a id-uduario="<?php echo $row['id_usuario'] ?>" nome-usuario="<?php echo $row['nome'] ?>" class="btn btn-danger btn-sm deletar-usuario" >Excluir</a>
                                        <a href="../usuario/cadastro_usuario.php?id_usuario=<?php echo $row['id_usuario'] ?>" class="btn btn-default btn-sm" >Editar</a>
                                    <?php } else if ($_SESSION['usuario']['id_usuario'] == $row['id_usuario']) { ?>
                                        <a href="../usuario/cadastro_usuario.php?id_usuario=<?php echo $row['id_usuario'] ?>" class="btn btn-default btn-sm" >Editar</a>
                                    <?php } ?>
                                </td>
                            </tr>

                        <?php } ?>

                    </tbody>
                </table>

            <?php } else { ?>

                <div class="alert alert-danger" role="alert">
                    <p>Não foram localizados registros para serem listados.</p>
                </div> 

            <?php } ?>

        </div>

    </div>
</div>

<div id="modalExcluirUsuario" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <i class="glyphicon glyphicon-user"></i> Excluir Usuário
                </h4>
            </div>

            <div class="modal-body">                
                <p>Excluir o usuário <b id="nomeUsuario"></b> ?</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" id="confirmarExclusao" id-usuario="" class="btn btn-danger">Confirmar</button>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="modal_sucesso" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="alert alert-success" style="margin-bottom: 0px" role="alert">  
                <i class="glyphicon glyphicon-ok"></i> Usuário Excluido com sucesso !
            </div>           
        </div>
    </div>
</div>

<?php include '../parts/footer.html'; ?>

<script>

    $(document).ready(function () {

        $(".deletar-usuario").on('click', function () {
            $("#nomeUsuario").text($(this).attr('nome-usuario'));
            $("#confirmarExclusao").attr('id-usuario', $(this).attr('id-uduario'));
            $('#modalExcluirUsuario').modal();
        });

        $("#confirmarExclusao").on('click', function () {

            var
                    idUsuario = $('#confirmarExclusao').attr('id-usuario');

            request = $.ajax({
                url: "../../controller/usuarioController.php",
                type: "post",
                data: {
                    acao: 'deletar',
                    id_usuario: idUsuario
                },
                dataType: 'html'
            });

            request.done(function (response) {
                $('#modalExcluirUsuario').modal('hide').on('hidden.bs.modal', function () {
                    $('#modal_sucesso').modal().on('hidden.bs.modal', function () {
                        location.reload();
                    });
                });
            });

            request.fail(function (jqXHR, textStatus, errorThrown) {
                console.error(
                        "The following error occurred: " +
                        textStatus, errorThrown
                        );
            });

        });

    })

</script>

