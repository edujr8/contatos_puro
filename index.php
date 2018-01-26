<?php include 'view/parts/header.html'; ?>

<link href="css/signin.css" rel="stylesheet">

<div class="container">

    <form id="formLogin" class="form-signin box-login">

        <div class="alert alert-danger" style="display: none" id="alertaCampos" role="alert">                
        </div>

        <h4>Preencha seus dados de acesso !</h4>

        <input type="hidden" name="acao" value="logar" />

        <hr>

        <div class="form-group">
            <label for="inputEmail" class="sr-only">Login</label>
            <input type="text" name="login" id="login" class="form-control" placeholder="Login" required="" autofocus="">
        </div>

        <div class="form-group">
            <label for="inputPassword" class="sr-only">Senha</label>
            <input type="password" name="senha" id="senha" class="form-control" placeholder="Senha" required="">
        </div>

        <button class="btn btn-lg btn-primary btn-block" type="submit">Acessar</button>

        <hr>

        <div class="text-center">
            <a href="lembrar_senha.php" class="btn btn-link">Esqueci a Senha</a>
        </div>
    </form>

</div> <!-- /container -->

<?php include 'view/parts/footer.html'; ?>

<script>

    $(document).ready(function () {

        $("#formLogin").submit(function (event) {
            event.preventDefault();

            $('#alertaCampos').fadeOut()

            var ret = $.ajax({
                url: "controller/usuarioController.php",
                type: "post",
                data: $(this).serialize(),
                dataType: 'html'
            });

            ret.then(function (data) {

                data = JSON.parse(data);

                if (data == '1') {
                    $(location).attr("href", '/view/contato/lista_contato.php');
                } else if (data.error.tipo === 'login') {
                    $("#login, #senha").closest('.form-group').addClass('has-error');
                    $('#alertaCampos').html('<i class="glyphicon glyphicon-exclamation-sign"></i> <b>Atenção</b> ' + data.error.msg).fadeIn();
                }

            }, function (error) {
                console.log(error);
                $('#alertaCampos').html('Erro na solicitação verifique no console.').fadeIn();
            });

        });

    });

</script>