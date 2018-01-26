<?php include 'view/parts/header.html'; ?>

<link href="css/signin.css" rel="stylesheet">

<div class="container">

    <form id="formEsqueciSenha" class="form-signin box-login">

        <div class="alert alert-danger" style="display: none" id="alertaCampos" role="alert">                
        </div>

        <input type="hidden" name="acao" value="esqueci_senha" />

        <h4>Recuperar Senha</h4>

        <hr>

        <div class="form-group">
            <label for="inputEmail" class="sr-only">Login</label>
            <input type="text" id="login" name="form[login]" class="form-control" placeholder="Login" autofocus="">
        </div>

        <div class="form-group">
            <label for="inputPassword" class="sr-only">Senha</label>
            <input type="password" id="senha" name="form[senha]" class="form-control" placeholder="Senha" >
        </div>

        <div class="form-group">
            <label for="inputPassword" class="sr-only">Confirmar</label>
            <input type="password" id="confirmar" name="form[confirmar]" class="form-control" placeholder="Confirmar" >
        </div>

        <button class="btn btn-lg btn-primary btn-block" type="submit">Alterar</button>

        <hr>

        <div class="text-center">
            <a href="index.php" class="btn btn-link">Voltar</a>
        </div>

    </form>

</div> <!-- /container -->

<div id="modal_sucesso" class="modal fade" tabindex="-1" role="dialog" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="alert alert-success" style="margin-bottom: 0px" role="alert">  
                <i class="glyphicon glyphicon-ok"></i> Senha Alterada com sucesso !
            </div>           
        </div>
    </div>
</div>

<?php include 'view/parts/footer.html'; ?>

<script>

    $(document).ready(function () {

        $("#formEsqueciSenha").submit(function (event) {
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
                        $(location).attr("href", 'index.php');
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

        })

    })

</script>