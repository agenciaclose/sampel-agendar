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

$('.visita-status').click(function(){
    swal({
        title: "Deseja cancelar esse Evento?",
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: "Sim",
        denyButtonText: `Não`
    }).then((result) => {
        if (result.value) {
            var DOMAIN = $('body').attr('data-domain');
            var visita = $(this).attr('data-visita');
            var action = $(this).attr('data-action');
            $.ajax({url: domain+'/painel/visita/status/'+action+'/'+visita, 
                success: function(result){
                    swal("Evento Cancelado!", "", "success");
                    setTimeout(function() { window.location.href = DOMAIN; }, 2000);
                }
            });
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
                setTimeout(function() { location.reload(); }, 1500);

            } else {
                swal({type: 'warning', title: 'Erro ao remover!', showConfirmButton: false, timer: 1500});
                $('button[type="submit"]').prop("disabled", false);
                $('.form-load').removeClass('show');

            }
        }
    });

});

$('#tabela_dinamica').on('click', '.btn-excluir-inscricao', function (e) {

    e.preventDefault();
    var $btn = $(this);
    var $row = $btn.closest('tr');
    var DOMAIN = $('body').data('domain');
    var id = $btn.data('id');
    var visita = $btn.data('visita');

    swal({
        title: "Deseja excluir esta inscrição?",
        showCancelButton: true,
        showDenyButton: true,
        confirmButtonText: "Sim",
        denyButtonText: `Não`
    }).then((result) => {
        if (!result.value) {
            return;
        }

        $.ajax({
            type: "POST",
            async: true,
            data: { id: id, visita_id: visita },
            url: DOMAIN + '/visita/inscricao/excluir',
            success: function (data) {

                if (data === "success") {
                    swal({type: 'success', title: 'INSCRIÇÃO EXCLUÍDA!', showConfirmButton: false, timer: 1500});
                    $row.fadeOut(400, function () {
                        $(this).remove();
                    });
                } else if (data === "forbidden") {
                    swal({type: 'warning', title: 'Você não tem permissão para excluir esta inscrição.', showConfirmButton: false, timer: 2000});
                } else {
                    swal({type: 'error', title: 'ERRO AO EXCLUIR!', showConfirmButton: false, timer: 1500});
                }
            },
            error: function () {
                swal({type: 'error', title: 'ERRO AO EXCLUIR!', showConfirmButton: false, timer: 1500});
            }
        });
    });
});


// GERA QRCODE DA PALESTRA
function qrcodeGenVisitas(id_visita) {
    var DOMAIN = $('body').data('domain');
    
    $.ajax({
        type: "POST",
        async: true,
        url: DOMAIN + '/qr/generate',
        data: {
            "frame_name": "bottom-frame",
            "qr_code_text": DOMAIN + '/visita/inscricao/' + id_visita + '?a=qr',
            "image_format": "SVG",
            "frame_color": "#246CB1",
            "frame_text_color": "#ffffff",
            "frame_icon_name": "mobile",
            "frame_text": "INSCRIÇÃO",
            "marker_left_template": "version13",
            "marker_right_template": "version13",
            "marker_bottom_template": "version13"
        },
        success: function (qrcode) {
            qrcodeSaveVisitas(id_visita, qrcode);
        }
    });
}

function qrcodeSaveVisitas (id_visita, qrcode){
    var DOMAIN = $('body').data('domain');

    const formData = new FormData()
    formData.append('id_visita', id_visita);
    formData.append('qrcode', qrcode);

    $.ajax({
        type: "POST",
        url: DOMAIN + '/visita/cadastro/save-qrcode',
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
            qrcodeGenFeedback(id_visita);
        }
    });
}
// ##

// GERA QRCODE DO FEEDBACK
function qrcodeGenFeedback(id_visita) {
    var DOMAIN = $('body').data('domain');
    
    $.ajax({
        type: "POST", 
        async: true, 
        data: { 
                "frame_name": "bottom-frame",
                "qr_code_text": DOMAIN + '/visita/feedback/'+id_visita,
                "image_format": "SVG",
                "frame_color": "#246CB1",
                "frame_text_color": "#ffffff",
                "frame_icon_name": "mobile",
                "frame_text": "FEEDBACK",
                "marker_left_template": "version13",
                "marker_right_template": "version13",
                "marker_bottom_template": "version13"
            },
        url: DOMAIN + '/qr/generate',
        success: function (qrcode) {
            qrcodeSaveFeedback(id_visita, qrcode);
        }
    });
}

function qrcodeSaveFeedback (id_visita, qrcode){
    var DOMAIN = $('body').data('domain');

    const formData = new FormData()
    formData.append('id_visita', id_visita);
    formData.append('qrcode', qrcode);

    $.ajax({
        type: "POST",
        url: DOMAIN + '/visita/cadastro/save-qrcode-feedback',
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
            window.open(DOMAIN + '/visita/qrcode/'+id_visita, "_blank");
        }
    });
}

function exportInscritosVisita(visitaId) {
    var DOMAIN = $('body').data('domain') || $('body').attr('data-domain');
    if (!DOMAIN) {
        return;
    }
    var qs = window.location.search || '';
    swal({
        title: 'Gerando Lista de Inscritos',
        text: 'Por favor, aguarde...',
        allowOutsideClick: false,
        showConfirmButton: false,
        onOpen: function () {
            swal.showLoading();
        }
    });
    fetch(DOMAIN + '/visita/lista/' + visitaId + '/export-inscritos' + qs, {
        method: 'GET',
        credentials: 'same-origin',
        headers: { Accept: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,*/*' }
    }).then(function (response) {
        if (!response.ok) {
            throw new Error('HTTP ' + response.status);
        }
        var cd = response.headers.get('Content-Disposition');
        var filename = 'inscritos_visita_' + visitaId + '.xlsx';
        if (cd) {
            var m = /filename="([^"]+)"/i.exec(cd) || /filename=([^;\s]+)/i.exec(cd);
            if (m && m[1]) {
                filename = m[1].replace(/\+/g, ' ');
                try {
                    filename = decodeURIComponent(filename);
                } catch (e) { /* ignore */ }
            }
        }
        return response.blob().then(function (blob) {
            return { blob: blob, filename: filename };
        });
    }).then(function (r) {
        swal.close();
        var url = window.URL.createObjectURL(r.blob);
        var a = document.createElement('a');
        a.href = url;
        a.download = r.filename;
        document.body.appendChild(a);
        a.click();
        a.remove();
        window.URL.revokeObjectURL(url);
    }).catch(function () {
        swal.close();
        swal({ type: 'error', title: 'Não foi possível gerar a lista.', showConfirmButton: false, timer: 2500 });
    });
}

// ##
$(document).ready(function () {

    if ($('#descricao').length){
        new FroalaEditor('#descricao', {
            key: "1C%kZV[IX)_SL}UJHAEFZMUJOYGYQE[\\ZJ]RAe(+%$==",
            enter: FroalaEditor.ENTER_BR,
            language: 'pt_br',
            entities: '',
            pastePlain: true,
            attribution: false,
            theme: 'dark',
            toolbarButtons: {
                'moreText': {
                'buttons': ['bold', 'italic', 'underline', 'strikeThrough', 'fontSize', 'clearFormatting'],
                'buttonsVisible': 2
                },
                'moreParagraph': {
                'buttons': ['alignLeft', 'alignCenter',  'alignRight']
                },
                'moreRich': {
                'buttons': ['emoticons', 'fontAwesome']
                }
            }
        });
    }


    $("#editar_visita_form").submit(function (c) {

        $('.form-load').addClass('show');
        $('button[type="submit"]').prop("disabled", true);

        c.preventDefault();
        var DOMAIN = $('body').data('domain');
        var form = $(this);

        $.ajax({
            type: "POST", async: true, data: form.serialize(),
            url: DOMAIN + '/agendar/editar',
            success: function (data) {

                if (data == "1") {
                    
                    $('.form-load').removeClass('show');
                    swal({type: 'success', title: 'EDITADO COM SUCESSO!', showConfirmButton: false, timer: 1500});
                    setTimeout(function(){
                        location.reload();
                    }, 1500);

                } else {

                    $('button[type="submit"]').prop("disabled", false);
                    $('.form-load').removeClass('show');

                }
            }
        });

    });


});