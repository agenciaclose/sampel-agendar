$(document).ready(function () {

	$("#configuracoes_visita").submit(function (c) {

        $('.form-load').addClass('show');
        $('button[type="submit"]').prop("disabled", true);

        c.preventDefault();
        var DOMAIN = $('body').data('domain');
        var form = $(this);

        $.ajax({
            type: "POST", async: true, data: form.serialize(),
            url: DOMAIN + '/painel/visitas/config/save',
            success: function (data) {

                if (data == "1") {

                    $('.form-load').removeClass('show');
                    $('button[type="submit"]').prop("disabled", false);

                    Swal.fire({
                        title: 'Atualizado com sucesso!',
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        timer: 2000,
                    }).then((result) => {
                        if (result.isConfirmed) {
                           location.reload();
                        }
                    });

                } else {

                    $('button[type="submit"]').prop("disabled", false);
                    $('.form-load').removeClass('show');

                }
            }
        });

    });

});