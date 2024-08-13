$(document).ready(function () {
    var DOMAIN = $('body').data('domain');

    $("#add_orcamento").submit(function (c) {

        $('.form-load').addClass('show');
        $('button[type="submit"]').prop("disabled", true);

        c.preventDefault();
        var DOMAIN = $('body').data('domain');
        var form = $(this)[0];
        var formData = new FormData(form);

        $.ajax({
            type: "POST",
            processData: false,
            contentType: false,
            data: formData,
            url: DOMAIN + '/painel/orcamento/add/save',
            success: function (data) {

                if (data == "1") {
                    swal({type: 'success', title: 'Salvo com sucesso', showConfirmButton: false, timer: 2000});
                    setTimeout(function(){
                        window.location.reload();
                    }, 2000);
                    $('.form-load').removeClass('show');
                } else {

                    $('button[type="submit"]').prop("disabled", false);
                    $('.form-load').removeClass('show');

                }
            }
        });

    });

    $("#edit_orcamento").submit(function (c) {

        $('.form-load').addClass('show');
        $('button[type="submit"]').prop("disabled", true);

        c.preventDefault();
        var DOMAIN = $('body').data('domain');
        var form = $(this)[0];
        var formData = new FormData(form);

        $.ajax({
            type: "POST",
            processData: false,
            contentType: false,
            data: formData,
            url: DOMAIN + '/painel/orcamento/edit/save',
            success: function (data) {
                if (data == "1") {
                    swal({type: 'success', title: 'Editado com sucesso', showConfirmButton: false, timer: 2000});
                    setTimeout(function(){
                        window.location.reload();
                    }, 2000);
                    $('.form-load').removeClass('show');
                } else {

                    $('button[type="submit"]').prop("disabled", false);
                    $('.form-load').removeClass('show');

                }
            }
        });

    });
    
    $('#orcamento').select2({
        tags: true,
        dropdownParent: $('#orcamentoManager .offcanvas-body'),
        dropdownAutoWidth: true,
        width: '100%',
        tabindex: -1,
        ajax: {
            url: DOMAIN + '/painel/orcamento/get/terms',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function(obj) {
                        return { id: obj.id, text: obj.text };
                    })
                };
            },
        },
        matcher: function(params, data) {
            if ($.trim(params.term) === '') {return data;}
            if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {return data;}
            return null;
        },
        createTag: function(params) {
            var term = $.trim(params.term);
            if(term === "") { return null; }
            setTimeout(function() {
                $(".select2-results li:first-child").addClass("new");
            }, 100);
            return {id: term, text: term};
        }
    });
    
    $('.money').mask("#.##0,00", {reverse: true});
    
});

function removeOrcamento(id){
    var DOMAIN = $('body').data('domain');

    Swal.fire({
        title: "Deseja deletar?",
        text: "Essa ação não poderá ser desfeita.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim, deletar!",
        cancelButtonText: "Não, cancelar!",
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST", 
                async: true,
                url: DOMAIN + '/painel/orcamento/remove/'+id,
                success: function (data) {
                    if (data == "1") {
                        window.location.reload();
                    }else{
                        alert('ERRO AO DELETAR');
                    }
                }
            });
        }
    });
    
}