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
//     area: {
//       top: "0%",
//       right: "0%",
//       left: "0%",
//       bottom: "0%"
//     },
//     singleChannel: false
//   },
//   decoder: {
//     readers: ["code_128_reader"]
//   },
// };

// Quagga.init(config, function(err) {
//   Quagga.start();

//   if (err) {
//     console.log("Erro: " + err);
//     return;
//   }

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
//           if(data == '0'){
//             $('#sendContent').html('<button type="button" class="btn btn-success btn-lg w-100 fw-bold rounded-0"><i class="fa-solid fa-shield-check fa-beat"></i> VERIFICADO</button>');
//             setTimeout(function() { location.reload(); }, 1500);
//           }else{
//             $('#sendContent').html('<button type="button" class="btn btn-danger btn-lg w-100 fw-bold rounded-0"><i class="fa-solid fa-triangle-exclamation fa-fade"></i> ERRO AO VERIFICAR</button>');
//             setTimeout(function() { location.reload(); }, 3000);
//           }
//         }
//       });

//     }else{
//       alert('ERRO AO LER O CÓDIGO')
//     }
    
//     Quagga.stop();
//   });
// });


Quagga.init({
  inputStream: {
      name: "Live",
      type: "LiveStream",
      target: document.querySelector("#camera"),
      constraints: {
          width: 400,
          height: 100,
          facingMode: "environment"
      },
  },
  decoder: {
      readers: ["code_128_reader"]
  },
}, function (err) {
    if (err) {
        console.error("Erro ao inicializar: " + err);
        return;
    }

  Quagga.start();

  Quagga.onProcessed(function (result) {
      var drawingCtx = Quagga.canvas.ctx.overlay,
          drawingCanvas = Quagga.canvas.dom.overlay;

      if (result) {
          if (result.boxes) {
              drawingCtx.clearRect(0, 0, parseInt(drawingCanvas.getAttribute("width")), parseInt(drawingCanvas.getAttribute("height")));
              result.boxes.filter(function (box) {
                  return box !== result.box;
              }).forEach(function (box) {
                  Quagga.ImageDebug.drawPath(box, { x: 0, y: 1 }, drawingCtx, { color: "blue", lineWidth: 2 });
              });
          }

          if (result.box) {
              Quagga.ImageDebug.drawPath(result.box, { x: 0, y: 1 }, drawingCtx, { color: "blue", lineWidth: 2 });
          }

          if (result.codeResult && result.codeResult.code) {
              Quagga.ImageDebug.drawPath(result.line, { x: 'vertical', y: 'horizontal' }, drawingCtx, { color: 'red', lineWidth: 3 });
          }
      }
      
  });
});