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
        "pageLength": 7,
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

  });