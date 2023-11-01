$(document).ready(function () {

    $('input[type=radio][name=tipo_cadastro]').change(function() {
        if (this.value == 'tipo_cpf') {
            $('.tipo_cpf').hide();
            $('.tipo_cnpj').show();
        }else if (this.value == 'tipo_cnpj') {
            $('.tipo_cpf').show();
            $('.tipo_cnpj').hide();
        }
    });

    $("#resenha").keyup(function () {
        if ($(this).val() != $("#senha").val()) {
            $('#info-resenha').show();
        } else {
            $('#info-resenha').hide();
        }
    });

    $("#register-cliente-form").submit(function (c) {
        $('.login-load').show();
        c.preventDefault();
        var DOMAIN = $('body').data('domain');
        var form = $(this);
        $.ajax({
            type: "POST", async: true, data: form.serialize(),
            url: DOMAIN + '/painel/equipe/cadastro',
            success: function (data) {
                if (data == "1") {
                    window.location.href = DOMAIN+'/painel/equipes';
                } else  if (data == "2")  {
                    $('button[type="submit"]').prop("disabled", false);
                    swal({type: 'warning', title: 'Email já cadastrado!', showConfirmButton: false, timer: 1500});
                    $('.login-load').hide();
                }else{
                    $('button[type="submit"]').prop("disabled", false);
                    swal({type: 'error', title: 'Houve um erro ao cadastrar.', showConfirmButton: false, timer: 1500});
                    $('.login-load').hide();
                }
            }
        });
    });

    $("#register-client-form").submit(function (c) {
        $('.login-load').show();
        c.preventDefault();
        var DOMAIN = $('body').data('domain');
        var form = $(this);
        $.ajax({
            type: "POST", async: true, data: form.serialize(),
            url: DOMAIN + '/painel/equipe/cadastro',
            success: function (data) {
                $('#info-cadastro-cliente').hide();
                $('#info-cadastro-cliente2').hide();
                if (data == "1") {
                    window.location.href = DOMAIN+'/painel/equipes';
                } else  if (data == "2")  {e
                    $('button[type="submit"]').prop("disabled", false);
                    swal({type: 'warning', title: 'Email já cadastrado!', showConfirmButton: false, timer: 1500});
                    $('.login-load').hide();
                }else{
                    $('button[type="submit"]').prop("disabled", false);
                    swal({type: 'error', title: 'Houve um erro ao cadastrar.', showConfirmButton: false, timer: 1500});
                    $('.login-load').hide();
                }
            }
        });
    });

});

window.show_pass = function show_pass(id) {
    var type = $('#' + id + '').attr('data-type');
    if (type == 'hide') {
        $('#' + id + '').attr('type', 'text');
        $('#' + id + '').attr('data-type', 'show');
    } else {
        $('#' + id + '').attr('type', 'password');
        $('#' + id + '').attr('data-type', 'hide');
    }
};

$(document).on('blur', '#cep', function (e) {
    var cep = $(this).val().replace(/\D/g, '');
    if (cep != "") {
        var validacep = /^[0-9]{8}$/;
        if(validacep.test(cep)) {
            $("#address").attr("placeholder", "Carregando...");
            $("#district").attr("placeholder", "Carregando...");
            $("#city").attr("placeholder", "Carregando...");
            $("#uf").attr("placeholder", "Carregando...");
            $.getJSON("//viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {
                if (!("erro" in dados)) {
                    $("#address").val(dados.logradouro);
                    $("#district").val(dados.bairro);
                    $("#city").val(dados.localidade);
                    $("#uf").val(dados.uf);
                }else{
                    limpa_formulário_cep_empresa();
                    alert("CEP não encontrado.");
                }
            });
        }else{
            limpa_formulário_cep_empresa();
            alert("Formato de CEP inválido.");
        }
    }else{
        limpa_formulário_cep_empresa();
    }
});

function limpa_formulário_cep_empresa() {
    $("#address").attr("placeholder", "");
    $("#district").attr("placeholder", "");
    $("#city").attr("placeholder", "");
    $("#uf").attr("placeholder", "");
    $("#address").val("");
    $("#district").val("");
    $("#city").val("");
    $("#uf").val("");
}

$(document).on('blur', '.cnpj', function (e) {

    let domain = $('body').data('domain');
    $.ajax({
        url: domain + '/find-cnpj?cnpj='+$("#empresa_cnpj").val().replace(/[^\d]+/g,''),
        dataType: 'json',
        success: function(resposta){

            console.log(resposta.status);

            if(resposta.status != "ERROR"){
                $("#empresa_nome").val(resposta.nome);
                $("#empresa_fantasia").val(resposta.fantasia);
                $("#empresa_atividade").val(resposta.atividade_principal[0].text + " (" + resposta.atividade_principal[0].code + ")");
                $("#empresa_telefone").val(resposta.telefone);
                $("#empresa_email").val(resposta.email);
                $("#empresa_logradouro").val(resposta.logradouro);
                $("#empresa_complemento").val(resposta.complemento);
                $("#empresa_bairro").val(resposta.bairro);
                $("#empresa_cidade").val(resposta.municipio);
                $("#empresa_uf").val(resposta.uf);
                $("#empresa_cep").val(resposta.cep);
                $("#empresa_numero").val(resposta.numero);
            }else{
                alert(resposta.message);
            }

        }
    });

});