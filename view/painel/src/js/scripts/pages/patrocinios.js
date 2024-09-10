if ($('#descricao').length){

    if ($('#pais_patrocinio').length){
		$('#pais_patrocinio').select2({       
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

$(document).on('blur', '#cep_patrocinio', function (e) {
    var cep = $(this).val().replace(/\D/g, '');
    if (cep != "") {
        var validacep = /^[0-9]{8}$/;
        if(validacep.test(cep)) {
            $("#local_patrocinio").attr("placeholder", "Carregando...");
            $("#cidade_patrocinio").attr("placeholder", "Carregando...");
            $("#estado_patrocinio").attr("placeholder", "Carregando...");
            $.getJSON("//viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {
                if (!("erro" in dados)) {
                    $("#local_patrocinio").val(dados.logradouro+', '+dados.bairro);
                    $("#cidade_patrocinio").val(dados.localidade);
                    $("#estado_patrocinio").val(dados.uf);
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
    $("#local_patrocinio").attr("placeholder", "");
    $("#cidade_patrocinio").attr("placeholder", "");
    $("#estado_patrocinio").attr("placeholder", "");
    $("#local_patrocinio").val("");
    $("#cidade_patrocinio").val("");
    $("#estado_patrocinio").val("");
}

$(document).ready(function () {

	$("#add_patrocinio").submit(function (c) {

        $('.form-load').addClass('show');
        $('button[type="submit"]').prop("disabled", true);

        c.preventDefault();
        var DOMAIN = $('body').data('domain');
        var form = $(this);

        $.ajax({
            type: "POST", async: true, data: form.serialize(),
            url: DOMAIN + '/painel/patrocinios/add/save',
            success: function (data) {
                if (data != "0") {
                    swal({type: 'success', title: 'Salvo com sucesso', showConfirmButton: false, timer: 2000});
                    setTimeout(function(){
                        //location.reload();
                    }, 2000);
                    $('.form-load').removeClass('show');
                } else {

                    $('button[type="submit"]').prop("disabled", false);
                    $('.form-load').removeClass('show');

                }
            }
        });

    });

    $("#edit_patrocinio").submit(function (c) {

        $('.form-load').addClass('show');
        $('button[type="submit"]').prop("disabled", true);

        c.preventDefault();
        var DOMAIN = $('body').data('domain');
        var form = $(this);

        $.ajax({
            type: "POST", async: true, data: form.serialize(),
            url: DOMAIN + '/painel/patrocinios/edit/save',
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

    $('#pais_patrocinio').on('change', function() {
        var selectedValue = $(this).val();
        if (selectedValue !== 'Brasil') {
            $('.cep_patrocinio').hide();
            $('.cidade_estado').hide();
        }else{
            $('.cep_patrocinio').show();
            $('.cidade_estado').show();
        }
    });

});

function statusEvent(id, status_patrocinio) {
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
                data: {'id': id, status_patrocinio: status_patrocinio},
                url: DOMAIN + '/painel/patrocinios/status',
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