$(document).on('submit', '#form_equipe_evento', function (e) {
    console.log('TESTE');
    e.preventDefault();

    $('.form-load').addClass('show');
    $('button[type="submit"]').prop("disabled", true);

    var DOMAIN = $('body').data('domain');
    var form = $(this);

    $.ajax({
        type: "POST", async: true, data: form.serialize(),
        url: DOMAIN + '/eventos/listaEquipesSave',
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

$(document).on('click', '.remover_equipe', function (e) {

    e.preventDefault();
    var DOMAIN = $('body').data('domain');
    let evento = $(this).data("evento");
    let membro = $(this).data("membro");

    $.ajax({
        type: "POST", 
        async: true, 
        data: { 'evento': evento, 'membro': membro },
        url: DOMAIN + '/eventos/removeEquipe',
        success: function (data) {

            if (data == "0") {

                $('.form-load').removeClass('show');
                swal({type: 'success', title: 'Removido com sucesso!', showConfirmButton: false, timer: 1500});
                $('.lista_equipe .'+evento+'-'+membro).hide();

            } else {
                swal({type: 'warning', title: 'Erro ao remover!', showConfirmButton: false, timer: 1500});
                $('button[type="submit"]').prop("disabled", false);
                $('.form-load').removeClass('show');

            }
        }
    });

});

$(document).on('click', '.send_email_equipe_evento', function (e) {

    $(this).prop("disabled", true);
    $(this).html('<i class="fa-solid fa-sync fa-spin"></i> EMAILS SENDO ENVIADOS');

    e.preventDefault();
    let DOMAIN = $('body').data('domain');
    let evento_id = $(this).data('evento');

    $.ajax({
        type: "GET", 
        async: true,
        url: DOMAIN + '/eventos/sendEmailEquipeEventos/'+evento_id,
        success: function () {
            swal({type: 'success', title: 'Emails enviados com sucesso', showConfirmButton: false, timer: 1500});
            setTimeout(function() { location.reload(); }, 1500);
        }
    });

});