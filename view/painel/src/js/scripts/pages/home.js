// Support Tracker - Radial Bar Chart
// --------------------------------------------------------------------
cardColor = config.colors_dark.cardColor;
labelColor = config.colors_dark.textMuted;
headingColor = config.colors_dark.headingColor;
shadeColor = 'dark';
grayColor = '#1194dc';


// GRAFICO PEDIDOS
var porcentagemPedido = $('#empenho-pedidos').data('porcentagem');
if (porcentagemPedido < 15) {var corbarra = '#dc3545';}else{var corbarra = '#1194dc';}
const EmepnhoPedidos = document.querySelector('#empenho-pedidos'),
  EmepnhoPedidosOptions = {
    series: [porcentagemPedido],
    labels: ['Porcentagem Restante'], chart: { height: 360, type: 'radialBar' }, plotOptions: { radialBar: { offsetY: 10, startAngle: -140, endAngle: 130, hollow: { size: '65%' }, track: { background: [corbarra], strokeWidth: '100%' }, dataLabels: { name: { offsetY: -20, color: labelColor, fontSize: '13px', fontWeight: '400', fontFamily: 'Public Sans' }, value: { offsetY: 10, color: headingColor, fontSize: '38px', fontWeight: '400', fontFamily: 'Public Sans' } } } }, colors: [corbarra], fill: { type: 'gradient', gradient: { shade: 'dark', shadeIntensity: 0.5, gradientToColors: [corbarra], inverseColors: true, opacityFrom: 1, opacityTo: 0.6, stops: [30, 70, 100] } }, stroke: { dashArray: 10 }, grid: { padding: { top: -20, bottom: 5 } }, states: { hover: { filter: { type: 'none' } }, active: { filter: { type: 'none' } } }, responsive: [ { breakpoint: 1025, options: { chart: { height: 330 } } }, { breakpoint: 769, options: { chart: { height: 280 } } } ]
  };
if (typeof EmepnhoPedidos !== undefined && EmepnhoPedidos !== null) {
  const EmepnhoPedidosRender = new ApexCharts(EmepnhoPedidos, EmepnhoPedidosOptions);
  EmepnhoPedidosRender.render();
}

// GRAFICO VISITAS
var porcentagemVisitas = $('#empenho-visitas').data('porcentagem');
if (porcentagemVisitas < 15) {var corbarra = '#dc3545';}else{var corbarra = '#1194dc';}
const EmepnhoVisitas = document.querySelector('#empenho-visitas'),
  EmepnhoVisitasOptions = {
    series: [porcentagemVisitas],
    labels: ['Porcentagem Restante'], chart: { height: 360, type: 'radialBar' }, plotOptions: { radialBar: { offsetY: 10, startAngle: -140, endAngle: 130, hollow: { size: '65%' }, track: { background: [corbarra], strokeWidth: '100%' }, dataLabels: { name: { offsetY: -20, color: labelColor, fontSize: '13px', fontWeight: '400', fontFamily: 'Public Sans' }, value: { offsetY: 10, color: headingColor, fontSize: '38px', fontWeight: '400', fontFamily: 'Public Sans' } } } }, colors: [corbarra], fill: { type: 'gradient', gradient: { shade: 'dark', shadeIntensity: 0.5, gradientToColors: [corbarra], inverseColors: true, opacityFrom: 1, opacityTo: 0.6, stops: [30, 70, 100] } }, stroke: { dashArray: 10 }, grid: { padding: { top: -20, bottom: 5 } }, states: { hover: { filter: { type: 'none' } }, active: { filter: { type: 'none' } } }, responsive: [ { breakpoint: 1025, options: { chart: { height: 330 } } }, { breakpoint: 769, options: { chart: { height: 280 } } } ]
  };
if (typeof EmepnhoVisitas !== undefined && EmepnhoVisitas !== null) {
  const EmepnhoVisitasRender = new ApexCharts(EmepnhoVisitas, EmepnhoVisitasOptions);
  EmepnhoVisitasRender.render();
}

// GRAFICO VISITAS
var porcentagemPalestras = $('#empenho-palestras').data('porcentagem');
if (porcentagemPalestras < 15) {var corbarra = '#dc3545';}else{var corbarra = '#1194dc';}
const EmepnhoPalestras = document.querySelector('#empenho-palestras'),
  EmepnhoPalestrasOptions = {
    series: [porcentagemPalestras],
    labels: ['Porcentagem Restante'], chart: { height: 360, type: 'radialBar' }, plotOptions: { radialBar: { offsetY: 10, startAngle: -140, endAngle: 130, hollow: { size: '65%' }, track: { background: [corbarra], strokeWidth: '100%' }, dataLabels: { name: { offsetY: -20, color: labelColor, fontSize: '13px', fontWeight: '400', fontFamily: 'Public Sans' }, value: { offsetY: 10, color: headingColor, fontSize: '38px', fontWeight: '400', fontFamily: 'Public Sans' } } } }, colors: [corbarra], fill: { type: 'gradient', gradient: { shade: 'dark', shadeIntensity: 0.5, gradientToColors: [corbarra], inverseColors: true, opacityFrom: 1, opacityTo: 0.6, stops: [30, 70, 100] } }, stroke: { dashArray: 10 }, grid: { padding: { top: -20, bottom: 5 } }, states: { hover: { filter: { type: 'none' } }, active: { filter: { type: 'none' } } }, responsive: [ { breakpoint: 1025, options: { chart: { height: 330 } } }, { breakpoint: 769, options: { chart: { height: 280 } } } ]
  };
if (typeof EmepnhoPalestras !== undefined && EmepnhoPalestras !== null) {
  const EmepnhoPalestrasRender = new ApexCharts(EmepnhoPalestras, EmepnhoPalestrasOptions);
  EmepnhoPalestrasRender.render();
}

// GRAFICO EVENTOS
var porcentagemEventos = $('#empenho-eventos').data('porcentagem');
if (porcentagemEventos < 15) {var corbarra = '#dc3545';}else{var corbarra = '#1194dc';}
const EmepnhoEventos = document.querySelector('#empenho-eventos'),
  EmepnhoEventosOptions = {
    series: [porcentagemEventos],
    labels: ['Porcentagem Restante'], chart: { height: 360, type: 'radialBar' }, plotOptions: { radialBar: { offsetY: 10, startAngle: -140, endAngle: 130, hollow: { size: '65%' }, track: { background: [corbarra], strokeWidth: '100%' }, dataLabels: { name: { offsetY: -20, color: labelColor, fontSize: '13px', fontWeight: '400', fontFamily: 'Public Sans' }, value: { offsetY: 10, color: headingColor, fontSize: '38px', fontWeight: '400', fontFamily: 'Public Sans' } } } }, colors: [corbarra], fill: { type: 'gradient', gradient: { shade: 'dark', shadeIntensity: 0.5, gradientToColors: [corbarra], inverseColors: true, opacityFrom: 1, opacityTo: 0.6, stops: [30, 70, 100] } }, stroke: { dashArray: 10 }, grid: { padding: { top: -20, bottom: 5 } }, states: { hover: { filter: { type: 'none' } }, active: { filter: { type: 'none' } } }, responsive: [ { breakpoint: 1025, options: { chart: { height: 330 } } }, { breakpoint: 769, options: { chart: { height: 280 } } } ]
  };
if (typeof EmepnhoEventos !== undefined && EmepnhoEventos !== null) {
  const EmepnhoEventosRender = new ApexCharts(EmepnhoEventos, EmepnhoEventosOptions);
  EmepnhoEventosRender.render();
}

// GRAFICO PATROCINIOS
var porcentagemPatrocinios = $('#empenho-patrocinios').data('porcentagem');
if (porcentagemPatrocinios < 15) {var corbarra = '#dc3545';}else{var corbarra = '#1194dc';}
const EmepnhoPatrocinios = document.querySelector('#empenho-patrocinios'),
  EmepnhoPatrociniosOptions = {
    series: [porcentagemPatrocinios],
    labels: ['Porcentagem Restante'], chart: { height: 360, type: 'radialBar' }, plotOptions: { radialBar: { offsetY: 10, startAngle: -140, endAngle: 130, hollow: { size: '65%' }, track: { background: [corbarra], strokeWidth: '100%' }, dataLabels: { name: { offsetY: -20, color: labelColor, fontSize: '13px', fontWeight: '400', fontFamily: 'Public Sans' }, value: { offsetY: 10, color: headingColor, fontSize: '38px', fontWeight: '400', fontFamily: 'Public Sans' } } } }, colors: [corbarra], fill: { type: 'gradient', gradient: { shade: 'dark', shadeIntensity: 0.5, gradientToColors: [corbarra], inverseColors: true, opacityFrom: 1, opacityTo: 0.6, stops: [30, 70, 100] } }, stroke: { dashArray: 10 }, grid: { padding: { top: -20, bottom: 5 } }, states: { hover: { filter: { type: 'none' } }, active: { filter: { type: 'none' } } }, responsive: [ { breakpoint: 1025, options: { chart: { height: 330 } } }, { breakpoint: 769, options: { chart: { height: 280 } } } ]
  };
if (typeof EmepnhoPatrocinios !== undefined && EmepnhoPatrocinios !== null) {
  const EmepnhoPatrociniosRender = new ApexCharts(EmepnhoPatrocinios, EmepnhoPatrociniosOptions);
  EmepnhoPatrociniosRender.render();
}