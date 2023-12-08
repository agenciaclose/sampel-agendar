$("#form_editar_equipe").submit(function (e) {

    e.preventDefault();

    $('.form-load').addClass('show');
    $('button[type="submit"]').prop("disabled", true);

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

$('.remover_equipe').click(function (e) {

    e.preventDefault();
    var DOMAIN = $('body').data('domain');
    let visita = $(this).data("visita");
    let membro = $(this).data("membro");

    $.ajax({
        type: "POST", 
        async: true, 
        data: { 'visita': visita, 'membro': membro },
        url: DOMAIN + '/visita/removeEquipe',
        success: function (data) {

            if (data == "0") {

                $('.form-load').removeClass('show');
                swal({type: 'success', title: 'Removido com sucesso!', showConfirmButton: false, timer: 1500});
                $('.lista_equipe .'+visita+'-'+membro).hide();
                //setTimeout(function() { location.reload(); }, 1500);

            } else {
                swal({type: 'warning', title: 'Erro ao remover!', showConfirmButton: false, timer: 1500});
                $('button[type="submit"]').prop("disabled", false);
                $('.form-load').removeClass('show');

            }
        }
    });

});