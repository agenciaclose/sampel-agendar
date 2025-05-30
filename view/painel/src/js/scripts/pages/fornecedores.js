$(document).ready(function () {

	$("#add_fornecedor").submit(function (c) {

        $('.form-load').addClass('show');
        $('button[type="submit"]').prop("disabled", true);

        c.preventDefault();
        var DOMAIN = $('body').data('domain');
        var form = $(this);

        $.ajax({
            type: "POST", async: true, data: form.serialize(),
            url: DOMAIN + '/painel/contratos/fornecedores/add/save',
            success: function (data) {
                if (data != "0") {
                    swal({type: 'success', title: 'Salvo com sucesso', showConfirmButton: false, timer: 2000});
                    setTimeout(function(){
                        location.reload();
                    }, 2000);
                    $('.form-load').removeClass('show');
                } else {

                    $('button[type="submit"]').prop("disabled", false);
                    $('.form-load').removeClass('show');

                }
            }
        });

    });

    $("#edit_fornecedor").submit(function (c) {

        $('.form-load').addClass('show');
        $('button[type="submit"]').prop("disabled", true);

        c.preventDefault();
        var DOMAIN = $('body').data('domain');
        var form = $(this);

        $.ajax({
            type: "POST", async: true, data: form.serialize(),
            url: DOMAIN + '/painel/contratos/fornecedores/edit/save',
            success: function (data) {
                if (data == "1") {
                    swal({type: 'success', title: 'Editado com sucesso', showConfirmButton: false, timer: 2000});
                    setTimeout(function(){
                        location.reload();
                    }, 2000);
                    $('.form-load').removeClass('show');
                } else {

                    $('button[type="submit"]').prop("disabled", false);
                    $('.form-load').removeClass('show');

                }
            }
        });

    });

    $('#empresa_cep').mask('00000-000', {reverse: true, clearIfNotMatch: true});

    var maskBehavior = function (val) {
        return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
    },
    options = {
        onKeyPress: function(val, e, field, options) {
            field.mask(maskBehavior.apply({}, arguments), options);
        }
    };
    
    $('#empresa_telefone').mask(maskBehavior, options);
    
});

window.deleteFornecedor = function (fornecedor) {
	Swal.fire({
		title: "Desativar esse Fornecedor?",
		text: "Ele será removido dos relatórios.",
		showCancelButton: true,
		cancelButtonText: 'Não',
		confirmButtonText: 'Sim',
		dangerMode: true,
	}).then((result) => {
		if (result.value === true) {
			var DOMAIN = $('body').data('domain');
            $.ajax({
                type: "POST", 
                async: true,
                data: {fornecedor: fornecedor},
                url: DOMAIN + '/painel/contratos/fornecedores/delete',
                success: function (data) {
                    if (data == "success") {
                        window.location.reload();
                    }else{
                        alert('ERRO AO ATUALIZADO');
                    }
                }
            });
		}
	});
}


window.cnpj_preenche = function (){
    let domain = $('body').data('domain');
    $.ajax({
        url: domain + '/painel/contratos/fornecedores/find-cnpj?cnpj='+$("#empresa_cnpj").val().replace(/[^\d]+/g,''),
        dataType: 'json',
        success: function(resposta){
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
        }
    });
}

$(document).ready(function () {
    function updateMaskAndLabel() {
        const tipo = $('input[name="empresa_tipo"]:checked').val();
        const $input = $('#empresa_cnpj');
        const $label = $('#label_cnpj_cpf');
        
        if (tipo === 'juridica') {
            // Ajusta para CNPJ
            $label.text('CNPJ:');
            $input.mask('00.000.000/0000-00', { reverse: true, clearIfNotMatch: true });
        } else {
            // Ajusta para CPF
            $label.text('CPF:');
            $input.mask('000.000.000-00', { reverse: true, clearIfNotMatch: true });
        }
    }

    // Atualiza máscara e label ao carregar a página
    updateMaskAndLabel();

    // Atualiza máscara e label ao trocar o tipo de empresa
    $('input[name="empresa_tipo"]').on('change', function () {
        updateMaskAndLabel();
    });


    $('#tabela-parcelas').DataTable({
        "paging": true,
        "searching": false,
        "lengthChange": false,
        "info": false,
        "ordering": false,
        "pageLength": 10,
        "language": {
            "paginate": {
                "next": '',
                "previous": ''
            }
        }
    });
});