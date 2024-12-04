if ($('#descricao').length){

    if ($('#pais_contrato').length){
		$('#pais_contrato').select2({       
            dropdownParent: $('#formContrato .offcanvas-body'),
            dropdownAutoWidth: true,
            width: '100%',
            tabindex: -1
        });
	}

    new FroalaEditor('#descricao', {
        key: "1C%kZV[IX)_SL}UJHAEFZMUJOYGYQE[\\ZJ]RAe(+%$==",
        enter: FroalaEditor.ENTER_BR,
        language: 'pt_br',
        placeholderText: 'Digite uma descrição...', 
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

$(document).on('blur', '#cep_contrato', function (e) {
    var cep = $(this).val().replace(/\D/g, '');
    if (cep != "") {
        var validacep = /^[0-9]{8}$/;
        if(validacep.test(cep)) {
            $("#local_contrato").attr("placeholder", "Carregando...");
            $("#cidade_contrato").attr("placeholder", "Carregando...");
            $("#estado_contrato").attr("placeholder", "Carregando...");
            $.getJSON("//viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {
                if (!("erro" in dados)) {
                    $("#local_contrato").val(dados.logradouro+', '+dados.bairro);
                    $("#cidade_contrato").val(dados.localidade);
                    $("#estado_contrato").val(dados.uf);
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
    $("#local_contrato").attr("placeholder", "");
    $("#cidade_contrato").attr("placeholder", "");
    $("#estado_contrato").attr("placeholder", "");
    $("#local_contrato").val("");
    $("#cidade_contrato").val("");
    $("#estado_contrato").val("");
}

$(document).ready(function () {

	$("#add_contrato").submit(function (c) {

        $('.form-load').addClass('show');
        $('button[type="submit"]').prop("disabled", true);

        c.preventDefault();
        var DOMAIN = $('body').data('domain');
        var form = $(this);

        $.ajax({
            type: "POST", async: true, data: form.serialize(),
            url: DOMAIN + '/painel/contratos/add/save',
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

    $("#edit_contrato").submit(function (c) {

        $('.form-load').addClass('show');
        $('button[type="submit"]').prop("disabled", true);

        c.preventDefault();
        var DOMAIN = $('body').data('domain');
        var form = $(this);

        $.ajax({
            type: "POST", async: true, data: form.serialize(),
            url: DOMAIN + '/painel/contratos/edit/save',
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

    $('#pais_contrato').on('change', function() {
        var selectedValue = $(this).val();
        if (selectedValue !== 'Brasil') {
            $('.cep_contrato').hide();
            $('.cidade_estado').hide();
        }else{
            $('.cep_contrato').show();
            $('.cidade_estado').show();
        }
    });

});

function statusEvent(id, status_contrato) {
	Swal.fire({
		title: "Desativar esse Patrocínio?",
		text: "Ele será removido dos relatórios, e caso existam pedidos vinculados a ele, esses pedidos serão recusados e os itens retornarão ao estoque.",
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
                data: {'id': id, status_contrato: status_contrato},
                url: DOMAIN + '/painel/contratos/status',
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