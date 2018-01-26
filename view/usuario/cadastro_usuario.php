<?php

include '../parts/header.html';
include '../parts/menu.php';
include '../../class/conexao.php';

if (isset($_REQUEST['id_usuario'])) {

    $con = new Conexao();

    $rs = $con->select(
            array(
                'table' => 'usuario',
                'data' => array(
                    'id_usuario' => $_REQUEST['id_usuario']
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
                
                <a href="../usuario/lista_usuario.php" class="list-group-item">Lista Usuários</a>
                
                <?php if ($_SESSION['usuario']['tipo_login'] == 1) { ?>
                    <?php if (isset($_REQUEST['id_usuario'])) {
                        ?>

                        <a href="../usuario/cadastro_usuario.php" class="list-group-item active">Novo Usuário</a>
                        <a href="" class="list-group-item active">Editar Usuário</a>

                    <?php } else { ?>

                        <a href="../usuario/cadastro_usuario.php" class="list-group-item active">Novo Usuário</a>

                        <?php
                    }
                } else {
                    ?>
                    <a href="" class="list-group-item active">Editar Usuário</a>
                <?php } ?>

            </div>

        </div>

        <div class="col-xs-12 col-sm-10">

            <p class="pull-right visible-xs">
                <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Menu Lateral</button>
            </p>

            <div class="alert alert-danger" style="display: none" id="alertaCampos" role="alert">                
            </div>

            <form id="formCadUsuario">

                <?php if (isset($_REQUEST['id_usuario'])) { ?>

                    <input type="hidden" name="acao" value="editar" />
                    <input type="hidden" id="id_usuario" name="id_usuario" value="<?php echo $_REQUEST['id_usuario'] ?>" />

                    <h3>
                        <b>Editar Usuários</b>
                    </h3>

                <?php } else { ?>

                    <input type="hidden" name="acao" value="cadastrar" />

                    <h3>
                        <b>Cadastro de Usuários</b>
                    </h3>

                <?php } ?>

                <hr>

                <div class="form-group"> 
                    <label>Nome *</label> 
                    <input type="text" name="form[nome]" id="nome" class="form-control" value="<?php echo (isset($data['nome'])) ? $data['nome'] : '' ?>"> 
                </div> 

                <div class="form-group"> 
                    <label>Login *</label> 

                    <?php if (isset($_REQUEST['id_usuario'])) { ?>

                        <input type="text" class="form-control" disabled value="<?php echo (isset($data['login'])) ? $data['login'] : '' ?>"> 

                    <?php } else { ?>     

                        <input type="text" name="form[login]" id="login" class="form-control"> 

                    <?php } ?>

                </div> 

                <div class="form-group"> 
                    <label>Tipo Usuário *</label> 
                    <select name="form[tipo_login]" id="tipo_login" class="form-control">
                        <option  value="">Selecione...</option>
                        <?php if($_SESSION['usuario']['tipo_login'] === '1' OR !isset($_REQUEST['id_usuario'])) { ?><option <?php echo (isset($data['tipo_login'])) ? ($data['tipo_login'] === '1') ? 'selected' : '' : '' ?> value="1">Administrador</option> <?php } ?>
                        <option <?php echo (isset($data['tipo_login'])) ? ($data['tipo_login'] === '2') ? 'selected' : '' : '' ?> value="2">Usuário</option>
                    </select>
                </div> 

                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label >Senha *</label> 
                            <input type="password" name="form[senha]" id="senha" class="form-control">
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>Confirmação *</label> 
                            <input type="password" name="form[confirmar]" id="confirmar" class="form-control">
                        </div> 
                    </div>
                </div> 

                <hr>

                <button type="submit" style="float: right" class="btn btn-default">Salvar Formulário</button>

            </form>

        </div>

    </div>
</div>

<div id="modal_sucesso" class="modal fade" tabindex="-1" role="dialog" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="alert alert-success" style="margin-bottom: 0px" role="alert">  
                <i class="glyphicon glyphicon-ok"></i> Usuário Cadastrado com sucesso !
            </div>           
        </div>
    </div>
</div>

<?php include '../parts/footer.html'; ?>

<script>

    $(document).ready(function () {

        $("#formCadUsuario").submit(function (event) {
            event.preventDefault();

            var ret = cadastarForm({
                form: $(this),
                controller: "../../controller/usuarioController.php",
            });

            ret.then(function (data) {

                data = JSON.parse(data);

                if (data == '1') {

                    $('#modal_sucesso').modal({
                        keyboard: false
                    }).on('hidden.bs.modal', function (e) {
<?php if ($_SESSION['usuario']['tipo_login'] == 1) { ?>
                            $(location).attr("href", '/view/usuario/lista_usuario.php');
<?php } else { ?>
                            $(location).attr("href", '/view/usuario/cadastro_usuario.php?id_usuario=' + $("#id_usuario").attr('value'));
<?php } ?>
                    });

                } else if (data.error.tipo === 'campos') {
                    for (var i in data.error.msg) {
                        item = data.error.msg[i];
                        $("#" + item).closest('.form-group').addClass('has-error');
                    }

                    msg = '<i class="glyphicon glyphicon-exclamation-sign"></i> <b>Atenção</b> preencha os campos obrigatórios indicados.';

                    $('#alertaCampos').html(msg).fadeIn();
                } else if (data.error.tipo === 'senha') {
                    $("#senha ,#confirmar").closest('.form-group').addClass('has-error');
                    $('#alertaCampos').html('<i class="glyphicon glyphicon-exclamation-sign"></i> <b>Atenção</b> ' + data.error.msg).fadeIn();
                } else if (data.error.tipo === 'login') {
                    $("#login").closest('.form-group').addClass('has-error');
                    $('#alertaCampos').html('<i class="glyphicon glyphicon-exclamation-sign"></i> <b>Atenção</b> ' + data.error.msg).fadeIn();
                }

            }, function (error) {
                console.log(error);
                $('#alertaCampos').html('Erro na solicitação verifique no console.').fadeIn();
            });

        });

    });

</script>

