(function () {

  let labelColor, headingColor, borderColor;

  labelColor = config.colors_dark.textMuted;
  headingColor = config.colors_dark.headingColor;
  borderColor = config.colors_dark.borderColor;

})();

  $(document).ready(function() {

    $('#curva_abc').DataTable({
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

    $('#pedidos_equipe').DataTable({
      "paging": true,
      "searching": false,
      "lengthChange": false,
      "info": false,
      "ordering": false,
      "pageLength": 8,
      "language": {
          "paginate": {
              "next": '',
              "previous": ''
          }
      }
    });

    $('#pedidos_estados').DataTable({
      "paging": true,
      "searching": false,
      "lengthChange": false,
      "info": false,
      "ordering": false,
      "pageLength": 5,
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