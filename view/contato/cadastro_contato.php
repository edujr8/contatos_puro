<?php
include '../parts/header.html';
include '../parts/menu.php';
include '../../class/conexao.php';

if ($_SESSION['usuario']['tipo_login'] == 2) {
    header('Location: ../contato/lista_contato.php');
}

if (isset($_REQUEST['id_contato'])) {

    $con = new Conexao();

    $rs = $con->select(
            array(
                'table' => 'contato',
                'data' => array(
                    'id_contato' => $_REQUEST['id_contato']
                )
            )
    );

    $data = $rs->fetch();
}
?>

<div class="container">

    <div class="row row-offcanvas row-offcanvas-right">

        <div class="col-xs-12 col-sm-2 sidebar-offcanvas" id="sidebar">

            <div class="list-group">
                <a href="../contato/lista_contato.php" class="list-group-item">Lista de Contatos</a>
                <?php if (isset($_REQUEST['id_contato'])) { ?>

                    <a href="../contato/cadastro_contato.php" class="list-group-item ">Novo Contato</a>
                    <a href="" class="list-group-item active">Editar Contato</a>

                <?php } else { ?>

                    <a href="../contato/cadastro_contato.php" class="list-group-item active">Novo Contato</a>

                <?php } ?>
            </div>

        </div>

        <div class="col-xs-12 col-sm-10">

            <p class="pull-right visible-xs">
                <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Menu Lateral</button>
            </p>   

            <div class="alert alert-danger" style="display: none" id="alertaCampos" role="alert">                
            </div>

            <?php if (!isset($_REQUEST['id_contato'])) { ?>

                <form id="formEnvioArquivo" method="POST" action="../../controller/arquivoController.php" enctype="multipart/form-data" class="col-sm-12">
                    <h3>
                        <b>Carregamento de Arquivo</b>
                    </h3>

                    <div class="col-xs-12">
                        <div class="form-group">                        
                            <input type="file" name="arquivo_contato" required=""> 
                            <p class="help-block">Somente Arquivo xlsx e xls</p> 
                        </div> 

                        <div id="submitArquivo" style="float: right">
                            <button  type="submit" class="btn btn-default">Ler Arquivo</button>
                        </div>
                    </div>
                </form>

                <hr class="col-sm-12">

            <?php } ?>

            <form id="formCadContatoManual" class="col-sm-12">

                <?php if (isset($_REQUEST['id_contato'])) { ?>

                    <input type="hidden" name="id_contato" value="<?php echo $_REQUEST['id_contato'] ?>" />
                    <input type="hidden" name="acao" value="editar" />

                    <h3>
                        <b>Editar Contato</b>
                    </h3>
                <?php } else { ?>

                    <input type="hidden" name="acao" value="cadastrar" />

                    <h3>
                        <b>Cadastro Manual</b>
                    </h3>

                <?php } ?>

                <div class="col-xs-12 col-sm-6">
                    <div class="form-group"> 
                        <label>Nome *</label> 
                        <input type="text" class="form-control" name="form[nome]" id="nome" value="<?php echo (isset($data['nome'])) ? $data['nome'] : '' ?>"> 
                    </div> 

                    <div class="form-group"> 
                        <label>E-Mail *</label> 
                        <input type="email" class="form-control" name="form[email]" id="email" value="<?php echo (isset($data['email'])) ? $data['email'] : '' ?>"> 
                    </div> 

                    <div class="form-group"> 
                        <label>Cidade *</label> 
                        <input type="text" class="form-control" name="form[cidade]" id="cidade" value="<?php echo (isset($data['cidade'])) ? $data['cidade'] : '' ?>"> 
                    </div> 

                    <div class="form-group"> 
                        <label>Estado *</label> 
                        <input type="text" class="form-control" name="form[estado]" id="estado" value="<?php echo (isset($data['estado'])) ? $data['estado'] : '' ?>"> 
                    </div> 

                </div>

                <div class="col-xs-12 col-sm-6">

                    <div class="form-group"> 
                        <label>Data de Nascimento *</label> 
                        <input type="text" class="form-control data-mask" name="form[data_nascimento]" id="data_nascimento" value="
                        <?php
                        if ($data['data_nascimento']) {
                            $date = new DateTime($data['data_nascimento']);
                            echo $date->format('d/m/Y');
                        }
                            ?>
                               "> 
                    </div> 

                    <div class="form-group"> 
                        <label>Celular *</label> 
                        <input type="text" class="form-control" name="form[celular]" id="celular" value="<?php echo (isset($data['celular'])) ? $data['celular'] : '' ?>"> 
                    </div> 

                    <div class="form-group"> 
                        <label>Operadora *</label> 
                        <input type="text" class="form-control" name="form[operadora_cel]" id="operadora_cel" value="<?php echo (isset($data['operadora_cel'])) ? $data['operadora_cel'] : '' ?>"> 
                    </div> 

                </div>

                <div class="col-xs-12">
                    <hr>
                    <button type="submit" style="float: right" class="btn btn-default">Salvar Formulário</button>
                </div>

            </form>

        </div>

    </div>
</div>

<div id="modal_sucesso" class="modal fade" tabindex="-1" role="dialog" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="alert alert-success" style="margin-bottom: 0px" role="alert">  
                <i class="glyphicon glyphicon-ok"></i> Contato Cadastrado com sucesso !
            </div>           
        </div>
    </div>
</div>

<?php include '../parts/footer.html'; ?>

<script>

    $(document).ready(function () {

        $(".data-mask").mask("99/99/9999");

        $("#formEnvioArquivo").submit(function () {
            $("#submitArquivo").html('Carregando...');
        });

        $("#formCadContatoManual").submit(function (event) {
            event.preventDefault();

            var rest = cadastarForm({
                form: $(this),
                controller: "../../controller/contatoController.php",
            });

            rest.then(function (data) {

                data = JSON.parse(data);
                if (data == '1') {

                    $('#modal_sucesso').modal({
                        keyboard: false
                    }).on('hidden.bs.modal', function (e) {
                        $(location).attr("href", '/view/contato/lista_contato.php');
                    });

                } else if (data.error.tipo === 'campos') {
                    for (var i in data.error.msg) {
                        item = data.error.msg[i];
                        $("#" + item).closest('.form-group').addClass('has-error');
                    }

                    msg = '<i class="glyphicon glyphicon-exclamation-sign"></i> <b>Atenção</b> preencha os campos obrigatórios indicados.';

                    $('#alertaCampos').html(msg).fadeIn();
                }

            }, function (error) {
                console.log(error);
                $('#alertaCampos').html('Erro na solicitação verifique no console.').fadeIn();
            });
        });

    });

</script>

