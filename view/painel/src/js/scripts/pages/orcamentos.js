$(document).ready(function () {

    $(".tags").tagsinput()

    var DOMAIN = $('body').data('domain');

    if ($('#descricao').length){    
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

    $("#add_orcamento").submit(function (c) {

        $('.form-load').addClass('show');
        $('button[type="submit"]').prop("disabled", true);

        c.preventDefault();
        var DOMAIN = $('body').data('domain');
        var ID = $(this).data('orcamento');
        var tipo = $(this).data('tipo');
        var form = $(this)[0];
        var formData = new FormData(form);

        $.ajax({
            type: "POST",
            processData: false,
            contentType: false,
            data: formData,
            url: DOMAIN + '/painel/orcamento/add/save',
            success: function (data) {

                if (data != 0) {
                    swal({type: 'success', title: 'Salvo com sucesso', showConfirmButton: false, timer: 2000});
                    setTimeout(function(){
                        window.location.href = DOMAIN + "/painel/"+tipo+"/orcamento/edit/"+ID+"/"+data;
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

    $('#tag_orcamento').select2({
        tags: true,
        dropdownAutoWidth: true,
        width: '100%',
        tabindex: -1,
        placeholder: "Selecione ou digite uma tag", 
        ajax: {
            url: DOMAIN + '/painel/orcamento/tags/get/terms',
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

    $('#id_fornecedor').select2({
        dropdownAutoWidth: true,
        width: '100%',
        tabindex: -1,
        ajax: {
            url: DOMAIN + '/painel/contratos/fornecedores/get/terms',
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
    
    $('#orcamento').select2({
        tags: true,
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

    //PARCELAS
    $('#gerar_parcelas').on('click', function() {
        let valorOrcamento = parseFloat($('input[name="valor_orcamento"]').val().replace(/\./g, '').replace(',', '.'));
        let qtdParcelas = parseInt($('input[name="qtd_parcelas"]').val());
        let valorParcela = valorOrcamento / qtdParcelas;
        let $divParcelas = $('.parcelas');

        $divParcelas.empty();

        for (let i = 0; i < qtdParcelas; i++) {
            let parcelaHtml = `
                <div class="mb-2 row">
                    <div class="col-sm-6">
                        <label class="control-label">Valor da Parcela</label>
                        <input type="text" name="valor_parcela[]" class="form-control money" value="${valorParcela.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}">
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Data de Pagamento</label>
                        <input type="date" name="data_parcela[]" class="form-control">
                        <input type="hidden" name="numero_parcela[]" value="${(i + 1)}">
                    </div>
                </div>
            `;
            $divParcelas.append(parcelaHtml);
        }

        // Adiciona o evento de mudança ao primeiro campo de data após gerar as parcelas
        $('input[name="data_parcela[]"]').first().on('change', function() {
            var primeiraData = new Date($(this).val());
            var todosInputs = $('input[name="data_parcela[]"]');
            
            todosInputs.each(function(index) {
                if(index > 0) {
                    var novaData = new Date(primeiraData);
                    novaData.setMonth(novaData.getMonth() + index);
                    var novaDataFormatada = novaData.toISOString().split('T')[0];
                    $(this).val(novaDataFormatada);
                }
            });
        });
    });
    
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

function orcamentoChangeType(id, tipo_contrato){
    var DOMAIN = $('body').data('domain');

    $.ajax({
        type: "POST", 
        async: true,
        data: { 'id': id, 'tipo_contrato': tipo_contrato },
        url: DOMAIN + '/painel/orcamento/tipo_contrato',
        success: function () {
            window.location.reload();
        }
    }); 
}
