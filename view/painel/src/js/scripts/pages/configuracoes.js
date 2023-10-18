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

                    Swal.fire({ title: 'Atualizado com sucesso!', icon: 'success', showCancelButton: false, showConfirmButton: false, timer: 2000});
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

    new FroalaEditor('#sobre_horarios', {
		key: "1C%kZV[IX)_SL}UJHAEFZMUJOYGYQE[\\ZJ]RAe(+%$==",
		enter: FroalaEditor.ENTER_BR,
		language: 'pt_br',
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

    new FroalaEditor('#regras_visita', {
		key: "1C%kZV[IX)_SL}UJHAEFZMUJOYGYQE[\\ZJ]RAe(+%$==",
		enter: FroalaEditor.ENTER_BR,
		language: 'pt_br',
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


    $("#configuracoes_regras").submit(function (c) {
        c.preventDefault();
        var DOMAIN = $('body').data('domain');
        var form = $(this);
        $.ajax({
            type: "POST", async: true, data: form.serialize(),
            url: DOMAIN + '/painel/visitas/config/save/regras',
            success: function (data) {

                if (data == "1") {

                    Swal.fire({ title: 'Atualizado com sucesso!', icon: 'success', showCancelButton: false, showConfirmButton: false, timer: 2000 });
                    setTimeout(function(){
                        location.reload();
                    }, 1500);

                } else {

                    Swal.fire({ title: 'Error ao atualizar!', icon: 'error', showCancelButton: false, showConfirmButton: false, timer: 2000 });

                    $('button[type="submit"]').prop("disabled", false);
                    $('.form-load').removeClass('show');

                }
            }
        });
    });

    $("#add_motivo").submit(function (c) {
        c.preventDefault();
        var DOMAIN = $('body').data('domain');
        var form = $(this);
        $.ajax({
            type: "POST", async: true, data: form.serialize(),
            url: DOMAIN + '/painel/visitas/config/save/motivo',
            success: function (data) {

                if (data == "1") {

                    Swal.fire({ title: 'Salvo com sucesso!', icon: 'success', showCancelButton: false, showConfirmButton: false, timer: 2000 });
                    setTimeout(function(){
                        location.reload();
                    }, 1500);

                } else {

                    Swal.fire({ title: 'Error ao salvar!', icon: 'error', showCancelButton: false, showConfirmButton: false, timer: 2000 });

                    $('button[type="submit"]').prop("disabled", false);
                    $('.form-load').removeClass('show');

                }
            }
        });
    });

    $(".edit_motivo").submit(function (c) {
        c.preventDefault();
        var DOMAIN = $('body').data('domain');
        var form = $(this);
        $.ajax({
            type: "POST", async: true, data: form.serialize(),
            url: DOMAIN + '/painel/visitas/config/edit/motivo',
            success: function (data) {

                if (data == "1") {

                    Swal.fire({ title: 'Editado com sucesso!', icon: 'success', showCancelButton: false, showConfirmButton: false, timer: 2000 });
                    setTimeout(function(){
                        location.reload();
                    }, 1500);

                } else {

                    Swal.fire({ title: 'Error ao editar!', icon: 'error', showCancelButton: false, showConfirmButton: false, timer: 2000 });

                    $('button[type="submit"]').prop("disabled", false);
                    $('.form-load').removeClass('show');

                }
            }
        });
    });

    window.deleteMotivo = function (id){
        var DOMAIN = $('body').data('domain');
        $.ajax({
            type: "POST", 
            async: true, 
            data: {id: id},
            url: DOMAIN + '/painel/visitas/config/delete/motivo',
            success: function (data) {

                if (data == "1") {

                    Swal.fire({ title: 'Deletado com sucesso!', icon: 'success', showCancelButton: false, showConfirmButton: false, timer: 2000 });
                    setTimeout(function(){
                        location.reload();
                    }, 1500);

                } else {

                    Swal.fire({ title: 'Error ao deletar!', icon: 'error', showCancelButton: false, showConfirmButton: false, timer: 2000 });

                    $('button[type="submit"]').prop("disabled", false);
                    $('.form-load').removeClass('show');

                }
            }
        });
    }


});