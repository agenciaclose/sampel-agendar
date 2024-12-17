(function () {

  let labelColor, headingColor, borderColor;

  labelColor = config.colors_dark.textMuted;
  headingColor = config.colors_dark.headingColor;
  borderColor = config.colors_dark.borderColor;

})();

$(document).ready(function() {

  $('#pedidos_mes').DataTable({
      "paging": true,
      "searching": false,
      "lengthChange": false,
      "info": false,
      "ordering": false,
      "pageLength": 6,
      "language": {
          "paginate": {
              "next": '',
              "previous": ''
          }
      }
  });

  $('.lista-mes-contratos').click(function() {
    $('.lista-mes-contratos .barra > div').removeClass('active');
    $(this).find('.barra > div').addClass('active');
  });

  $('.lista-mes-contratos .barra').click(function() {

    var periodo = $(this).data('periodo');
    periodo = periodo.split('/');

    var DOMAIN = $('body').data('domain');

    $.ajax({
        type: "POST", 
        async: true, 
        data: {'primeiro_dia': periodo[0], 'ultimo_dia': periodo[1]},
        url: DOMAIN + '/painel/dashboard/contratos/orcamento-por-mes',
        success: function (response) {
          $('#relatorio-mes').html(response);
          $('#pedidos_mes').DataTable({
            "paging": true,
            "searching": false,
            "lengthChange": false,
            "info": false,
            "ordering": false,
            "pageLength": 6,
            "language": {
                "paginate": {
                    "next": '',
                    "previous": ''
                }
            }
          });
        }
    });

    $.ajax({
      type: "POST", 
      async: true, 
      data: {'primeiro_dia': periodo[0], 'ultimo_dia': periodo[1]},
      url: DOMAIN + '/painel/dashboard/contratos/valores-por-mes',
      success: function (response) {
        var valores = response.split('/');
        $('#mes-pago').html(numberFormat(valores[0], 2, ',', '.'));
        $('#mes-nao-pago').html(numberFormat(valores[1], 2, ',', '.'));
      }
    });

  });

});

function numberFormat(number, decimals, decPoint, thousandsSep) {
  number = Number(number).toFixed(decimals);

  let parts = number.split('.');
  let integerPart = parts[0];
  let decimalPart = parts[1] || '';

  // Regular expression to add thousands separator
  const regex = /\B(?=(\d{3})+(?!\d))/g;
  integerPart = integerPart.replace(regex, thousandsSep);

  return decimalPart 
      ? integerPart + decPoint + decimalPart 
      : integerPart;
}

$(document).on('click', '.goFiltroContratos', function(e) {
  e.preventDefault();
  var ids = $(this).attr('data-ids').replace(/,$/, '');
  var DOMAIN = $('body').data('domain');

  var form = $('<form>', {
      action: DOMAIN + '/painel/contratos/filtro',
      method: 'POST'
  }).append($('<input>', {
      type: 'hidden',
      name: 'ids',
      value: ids
  }));

  $('body').append(form);
  form.submit();
});