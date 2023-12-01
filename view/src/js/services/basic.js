// TOOLTIP
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl);
});

// POPOVER
var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl)
});

var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover-html"]'));
var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
    const popoverId = popoverTriggerEl.attributes['data-bs-popover-content'];
    if (popoverId) {
        const contentEl=$(`#${popoverId.value}`).html();
        return new bootstrap.Popover(popoverTriggerEl, {
            content: contentEl,
            html: true,
        });
    }else{
    }
});

$('.submenu').click(function() {
    $(this).next('ul.sub-menu').stop(true, true).slideToggle(400);
    return false;
  });

// jQuery Mask 
var maskBehavior = function (val) {
    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
},
options = {
    onKeyPress: function(val, e, field, options) {
        field.mask(maskBehavior.apply({}, arguments), options);
    }
};

$('.products-order li a').click(function() {
    const column = $(this).data('column');
    const orderBy = $(this).data('order-by');
    setCookie('CookieOrderByColumn', column, 365);
    setCookie('CookieOrderBy', orderBy, 365);
    location.reload();
});
function setCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    eraseCookie(name);
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}
function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
function eraseCookie(name) {   
    document.cookie = name+'=; Max-Age=-99999999;';  
}

$('.telefone').mask(maskBehavior, options);
$('.cep').mask('00000-000', {reverse: true, clearIfNotMatch: true});
$('.cpf').mask('000.000.000-00', {reverse: true, clearIfNotMatch: true});
$('.cnpj').mask('00.000.000/0000-00', {reverse: true});

$('.cpf_number').mask('00000000000', {reverse: true, clearIfNotMatch: true});

$(".send_email_equipe").click(function (c) {

    $(this).prop("disabled", true);
    $(this).html('<i class="fa-solid fa-sync fa-spin"></i> EMAILS SENDO ENVIADOS');

    c.preventDefault();
    let DOMAIN = $('body').data('domain');
    let visita_id = $(this).data('visita');

    $.ajax({
        type: "GET", 
        async: true,
        url: DOMAIN + '/visita/sendEmailEquipe/'+visita_id,
        success: function () {
            swal({type: 'success', title: 'Emails enviados com sucesso', showConfirmButton: false, timer: 1500});
            setTimeout(function() { location.reload(); }, 1500);
        }
    });

});

$(".send_email_estatisticas_equipe").click(function (c) {

    $(this).prop("disabled", true);
    $(this).html('<i class="fa-solid fa-sync fa-spin"></i> EMAILS SENDO ENVIADOS');

    c.preventDefault();
    let DOMAIN = $('body').data('domain');
    let visita_id = $(this).data('visita');

    $.ajax({
        type: "GET", 
        async: true,
        url: DOMAIN + '/visita/sendEmailEstatisticas/'+visita_id,
        success: function () {
            swal({type: 'success', title: 'Emails enviados com sucesso', showConfirmButton: false, timer: 1500});
            setTimeout(function() { location.reload(); }, 1500);
        }
    });

});

$("#gerar_certificado").submit(function (c) {
    $('.form-load').addClass('show');
    $('button[type="submit"]').prop("disabled", true);

    c.preventDefault();
    var DOMAIN = $('body').data('domain');
    var form = $(this);

    $.ajax({
        type: "POST", async: true, data: form.serialize(),
        url: DOMAIN + '/certificados/emitirCheckVisita',
        success: function (data) {
            window.location.href = DOMAIN + '/certificados/pdf/visita.php?codigo='+data;
        }
    });
});

$("#minhas_inscricoes").submit(function (c) {
    $('.form-load').addClass('show');
    $('button[type="submit"]').prop("disabled", true);

    c.preventDefault();
    var DOMAIN = $('body').data('domain');
    var form = $(this);

    $.ajax({
        type: "POST", async: true, data: form.serialize(),
        url: DOMAIN + '/minhas-inscricoes/checkInscricoes',
        success: function (data) {
            window.location.href = DOMAIN + '/minhas-inscricoes/lista';
        }
    });
});