if ($('#descricao').length){

    if ($('#pais_evento').length){
		$('#pais_evento').select2({       
            dropdownParent: $('#formEvent .offcanvas-body'),
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

$(document).on('blur', '#cep_evento', function (e) {
    var cep = $(this).val().replace(/\D/g, '');
    if (cep != "") {
        var validacep = /^[0-9]{8}$/;
        if(validacep.test(cep)) {
            $("#local_evento").attr("placeholder", "Carregando...");
            $("#cidade_evento").attr("placeholder", "Carregando...");
            $("#estado_evento").attr("placeholder", "Carregando...");
            $.getJSON("//viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {
                if (!("erro" in dados)) {
                    $("#local_evento").val(dados.logradouro+', '+dados.bairro);
                    $("#cidade_evento").val(dados.localidade);
                    $("#estado_evento").val(dados.uf);
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
    $("#local_evento").attr("placeholder", "");
    $("#cidade_evento").attr("placeholder", "");
    $("#estado_evento").attr("placeholder", "");
    $("#local_evento").val("");
    $("#cidade_evento").val("");
    $("#estado_evento").val("");
}

$(document).ready(function () {

	$("#add_evento").submit(function (c) {

        $('.form-load').addClass('show');
        $('button[type="submit"]').prop("disabled", true);

        c.preventDefault();
        var DOMAIN = $('body').data('domain');
        var form = $(this);

        $.ajax({
            type: "POST", async: true, data: form.serialize(),
            url: DOMAIN + '/eventos/add/save',
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

    $("#edit_evento").submit(function (c) {

        $('.form-load').addClass('show');
        $('button[type="submit"]').prop("disabled", true);

        c.preventDefault();
        var DOMAIN = $('body').data('domain');
        var form = $(this);

        $.ajax({
            type: "POST", async: true, data: form.serialize(),
            url: DOMAIN + '/eventos/edit/save',
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

    $('#pais_evento').on('change', function() {
        var selectedValue = $(this).val();
        if (selectedValue !== 'Brasil') {
            $('.cep_evento').hide();
            $('.cidade_estado').hide();
        }else{
            $('.cep_evento').show();
            $('.cidade_estado').show();
        }
    });

});

function statusEvent(id, status_evento) {
	Swal.fire({
		title: "Desativar esse Evento?",
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
                data: {'id': id, status_evento: status_evento},
                url: DOMAIN + '/painel/eventos/status',
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

function duplicar(id_evento) {
	Swal.fire({
		title: "DESEJA DUPLICAR ESTE ITEM?",
        type: "warning", 
		showCancelButton: true,
        confirmButtonColor: "#5cb85c", 
		cancelButtonText: 'NÃO',
		confirmButtonText: 'SIM'
	}).then((result) => {
		if (result.value === true) {
			var DOMAIN = $('body').data('domain');
            $.ajax({
                type: 'POST',
                url: DOMAIN + '/painel/eventos/duplicar',
                data: {id_evento:id_evento},
                success: function (response) {
                    swal("", "DUPLICADO!", "success");
                    setTimeout(function(){
                        location.reload();
                    }, 1500);
                }
            });
		}
	});
}