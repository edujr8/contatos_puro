
<?php include '../../class/validacao_login.php'; ?>

<nav class="navbar navbar-fixed-top navbar-inverse">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <a class="navbar-brand" href="#">Ger. de Contatos</a>
        </div>

        <div id="navbar" class="collapse navbar-collapse">

            <ul class="nav navbar-nav">
                <li>
                    <a href="../contato/lista_contato.php">Contatos</a>
                </li>

                <?php if ($_SESSION['usuario']['tipo_login'] == 1) { ?>

                    <li>
                        <a href="../usuario/lista_usuario.php">Usuários</a>
                    </li>

                <?php } else { ?>

                    <li>
                        <a href="../usuario/cadastro_usuario.php?id_usuario=<?php echo $_SESSION['usuario']['id_usuario'] ?>">Usuários</a>
                    </li>

                <?php } ?>

            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a class="btn disabled">Olá <span id="nomeUsuarioLogado"><?php echo $_SESSION['usuario']['nome'] ?></span> !</a>
                </li>
                <li>
                    <a id="btn-deslogar">Logout</a>
                </li>
            </ul>

        </div><!-- /.nav-collapse -->
    </div><!-- /.container -->
</nav><!-- /.navbar -->

<div id="modalLogout" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <i class="glyphicon glyphicon-log-out"></i> Deslogar
                </h4>
            </div>

            <div class="modal-body">                
                <p> <b id="nomeUsuarioModal"></b>, deseja finalizar a sessão e sair do sistem ?</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" id="confirmarLogout" id-contato="" class="btn btn-danger">Confirmar</button>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->