// var cameraElement = document.getElementById("camera");
// var startButton = document.getElementById("startButton");

// var config = {
//   inputStream: {
//     name: "Live",
//     type: "LiveStream",
//     target: cameraElement,
//     constraints: {
//       width: { min: 400 },
//       height: { min: 100 },
//       facingMode: "environment"
//     },
//     singleChannel: false,
//   },
//   decoder: {
//     readers: ["code_128_reader"]
//   },
//   locate: true,
// };

// // Inicializa o leitor de código de barras
// Quagga.init(config, function(err) {
//   Quagga.start();

//   if (err) {
//     console.log("Erro: " + err);
//     return;
//   }

//   // Configura um ouvinte para quando um código de barras for lido
//   Quagga.onDetected(function(result) {

//     $('#sendContent').html('<button type="button" class="btn btn-warning btn-lg w-100 fw-bold rounded-0"><i class="fa-solid fa-sync fa-spin"></i> VERIFICANDO...</button>');
    
//     let id_visita = $('#id_visita').val();
//     let DOMAIN = $('body').data('domain');

//     if(result.codeResult.code) {

//       $.ajax({
//         type: "POST", 
//         async: true, 
//         data: { 'id_visita': id_visita, 'codigo': result.codeResult.code},
//         url: DOMAIN + '/visita/recepcao/confirmar',
//         success: function (data) {
//           $('#sendContent').html('<button type="button" class="btn btn-success btn-lg w-100 fw-bold rounded-0"><i class="fa-solid fa-shield-check fa-beat"></i> VERIFICADO</button>');
//           setTimeout(function() { location.reload(); }, 3000);
//         }
//       });

//     }else{
//       alert('ERRO AO LER O CÓDIGO')
//     }
    
//     // Pare a leitura após um código de barras ser encontrado
//     Quagga.stop();
//   });
// });


$("#confirmar-presenca").submit(function (c) {

  $('#sendContentModal').html('<button type="button" class="btn btn-warning btn-lg w-100 fw-bold rounded-0"><i class="fa-solid fa-sync fa-spin"></i> VERIFICANDO...</button>');

  c.preventDefault();

  var DOMAIN = $('body').data('domain');
  var form = $(this);

  $.ajax({
      type: "POST", async: true, data: form.serialize(),
      url: DOMAIN + '/visita/recepcao/confirmar',
      success: function (data) {
        if(data == 0){
          $('#sendContentModal').html('<button type="button" class="btn btn-success btn-lg w-100 fw-bold rounded-0"><i class="fa-solid fa-shield-check fa-beat"></i> VERIFICADO</button>');
          setTimeout(function() { location.reload(); }, 3000);
        }else{
          $('#sendContentModal').html('<button type="submit" class="btn btn-danger btn-lg w-100 fw-bold rounded-0">INSCRIÇÃO NÃO ENCONTRADA. VERIFICAR NOVAMENTE.</button>');
          //setTimeout(function() { location.reload(); }, 3000);
        }

      }
  });

});