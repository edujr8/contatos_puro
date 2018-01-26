<?php
include '../parts/header.html';
include '../parts/menu.php';
include '../../class/conexao.php';

$con = new Conexao();

if (isset($_REQUEST['acao']) AND $_REQUEST['acao'] === 'filtro') {
    if ($_REQUEST['form']['campo'] AND $_REQUEST['form']['valor']) {
        $rs = $con->query("SELECT * FROM contato WHERE " . $_REQUEST['form']['campo'] . ' LIKE "%' . $_REQUEST['form']['valor'] . '%"');
    } else {
        $rs = $con->select(array('table' => 'contato',));
    }
} else {
    $rs = $con->select(array('table' => 'contato',));
}

$data = $rs->fetchAll();
?>

<div class="container">

    <div class="row row-offcanvas row-offcanvas-right">

        <div class="col-xs-12 col-sm-2 sidebar-offcanvas" id="sidebar">

            <div class="list-group">
                <a href="../contato/lista_contato.php" class="list-group-item active">Lista de Contatos</a>
                <?php if ($_SESSION['usuario']['tipo_login'] == 1) { ?>
                    <a href="../contato/cadastro_contato.php" class="list-group-item">Novo Contato</a>
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
                        <option value="email">E-Mail</option>
                        <option value="celular">Celular</option>
                        <option value="operadora_cel">Operador</option>
                        <option value="estado">Estado</option>
                        <option value="cidade">Cidade</option>
                        <option value="data_nascimento">Data Nasc.</option>
                    </select>
                </div>                

                <button type="submit" class="btn btn-info" style="width: 100%"> 
                    <i class="glyphicon glyphicon-filter"></i> Filtrar
                </button>

            </form>

        </div>

        <div class="col-xs-12 col-sm-10">

            <p class="pull-right visible-xs">
                <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Menu Lateral</button>
            </p>     

            <div class="row">                                

                <?php
                if ($data) {
                    foreach ($data as $row) {
                        ?>

                        <div class="col-xs-12 col-lg-4 box-contato">
                            <h3><?php echo $row['nome'] ?></h3>

                            <hr>

                            <div class="col-xs-12">
                                <div class="list-group">
                                    <h5 class="list-group-item-heading"><b>E-Mail</b></h5>
                                    <p class="list-group-item-text"><?php echo $row['email'] ?></p>
                                </div>
                            </div>

                            <div class="col-xs-12">
                                <div class="list-group">
                                    <h5 class="list-group-item-heading"><b>Data Nasc.</b></h5>
                                    <p class="list-group-item-text">
                                        <?php
                                        $date = new DateTime($row['data_nascimento']);
                                        echo $date->format('d/m/Y')
                                        ?>
                                    </p>
                                </div> 
                            </div>

                            <div class="col-xs-6">

                                <div class="list-group">
                                    <h5 class="list-group-item-heading"><b>Celular</b></h5>
                                    <p class="list-group-item-text"><?php echo $row['celular'] ?></p>
                                </div>

                                <div class="list-group">
                                    <h5 class="list-group-item-heading"><b>Cidade</b></h5>
                                    <p class="list-group-item-text"><?php echo $row['cidade'] ?></p>
                                </div>

                            </div>

                            <div class="col-xs-6">

                                <div class="list-group">
                                    <h5 class="list-group-item-heading"><b>Operadora</b></h5>
                                    <p class="list-group-item-text"><?php echo $row['operadora_cel'] ?></p>
                                </div>

                                <div class="list-group">
                                    <h5 class="list-group-item-heading"><b>Estado</b></h5>
                                    <p class="list-group-item-text"><?php echo $row['estado'] ?></p>
                                </div>                                

                            </div>  

                            <?php if ($_SESSION['usuario']['tipo_login'] == 1) { ?>
                                <div class="col-xs-6">
                                    <a href="../contato/cadastro_contato.php?id_contato=<?php echo $row['id_contato'] ?>" class="btn btn-default" style="float: right;" >Editar</a>
                                </div>    

                                <div class="col-xs-6">
                                    <a id-contato="<?php echo $row['id_contato'] ?>" nome-contato="<?php echo $row['nome'] ?>" class="btn btn-danger deletar-contato" style="float: right; margin-left: 10px" >Excluir</a>
                                </div>                                
                            <?php } ?>
                        </div>

                        <?php
                    }
                } else {
                    ?>
                    <div class="alert alert-danger" role="alert">
                        <p>Não foram localizados registros para serem listados.</p>
                    </div>
                <?php } ?>

            </div>
        </div>

    </div>
</div>

<div id="modalExcluirContato" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <i class="glyphicon glyphicon-user"></i> Excluir Contato
                </h4>
            </div>

            <div class="modal-body">                
                <p>Excluir o contato <b id="nomeContato"></b> ?</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" id="confirmarExclusao" id-contato="" class="btn btn-danger">Confirmar</button>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="modal_sucesso" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="alert alert-success" style="margin-bottom: 0px" role="alert">  
                <i class="glyphicon glyphicon-ok"></i> Contato Excluido com sucesso !
            </div>           
        </div>
    </div>
</div>

<?php include '../parts/footer.html'; ?>

<script>

    $(document).ready(function () {

        $(".deletar-contato").on('click', function () {
            $("#nomeContato").text($(this).attr('nome-contato'));
            $("#confirmarExclusao").attr('id-contato', $(this).attr('id-contato'));
            $('#modalExcluirContato').modal();
        });

        $("#confirmarExclusao").on('click', function () {

            var
                    idContato = $('#confirmarExclusao').attr('id-contato');

            request = $.ajax({
                url: "../../controller/contatoController.php",
                type: "post",
                data: {
                    acao: 'deletar',
                    id_contato: idContato
                },
                dataType: 'html'
            });

            request.done(function (response) {
                $('#modalExcluirContato').modal('hide').on('hidden.bs.modal', function () {
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

