function cadastarForm(data) {

    $('.has-error').removeClass('has-error');
    $("#alertaCampos").fadeOut();

    var
            request,
            rs,
            $form = data.form,
            serializedData = $form.serialize();

    request = $.ajax({
        url: data.controller,
        type: "post",
        data: serializedData,
        dataType: 'html'
    });

    rs = request.done(function (response) {
        return response;
    });

    rs = request.fail(function (jqXHR, textStatus, errorThrown) {
        console.error(
                "The following error occurred: " +
                textStatus, errorThrown
                );

        return false;
    });

    return rs;
}

$(document).ready(function () {

    $("#btn-deslogar").on('click', function (event) {
        $("#nomeUsuarioModal").text($("#nomeUsuarioLogado").text());
        $('#modalLogout').modal();
    });

    $("#confirmarLogout").on('click', function () {

        var ret = $.ajax({
            url: "../../controller/usuarioController.php",
            type: "post",
            data: {acao: 'deslogar'},
            dataType: 'html'
        });

        ret.then(function () {
            $(location).attr("href", '../../index.php');
        });
    });

});
