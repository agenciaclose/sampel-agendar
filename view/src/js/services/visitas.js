$(document).ready(function () {

    $("#form_editar_equipe").submit(function (c) {

        $('.form-load').addClass('show');
        $('button[type="submit"]').prop("disabled", true);

        c.preventDefault();
        var DOMAIN = $('body').data('domain');
        var form = $(this);

        $.ajax({
            type: "POST", async: true, data: form.serialize(),
            url: DOMAIN + '/visita/listaEquipesSave',
            success: function (data) {

                if (data == "0") {
                    $('.form-load').removeClass('show');
                    swal({type: 'success', title: 'Equipe editada com sucesso!', showConfirmButton: false, timer: 1500});
                    setTimeout(function() { location.reload(); }, 1500);
                } else {

                    $('button[type="submit"]').prop("disabled", false);
                    $('.form-load').removeClass('show');

                }
            }
        });

    });
});