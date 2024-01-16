// Selecione o elemento da câmera e o botão de início
var cameraElement = document.getElementById("camera");
var startButton = document.getElementById("startButton");

// Configuração do leitor de código de barras
var config = {
  inputStream: {
    name: "Live",
    type: "LiveStream",
    target: cameraElement,
    constraints: {
      width: 640,
      height: 480,
      facingMode: "environment" // Use a câmera traseira (se disponível)
    },
  },
  decoder: {
    readers: ["ean_reader"] // Pode usar outros tipos de leitores, dependendo das necessidades
  },
};

// Inicializa o leitor de código de barras
Quagga.init(config, function(err) {
  if (err) {
    console.error(err);
    return;
  }
  
  // Adiciona um ouvinte de clique ao botão de início
  startButton.addEventListener("click", function() {
    Quagga.start();
  });
  
  // Configura um ouvinte para quando um código de barras for lido
  Quagga.onDetected(function(result) {
    alert("Código de barras lido: " + result.codeResult.code);
    
    // Pare a leitura após um código de barras ser encontrado
    Quagga.stop();
  });
});