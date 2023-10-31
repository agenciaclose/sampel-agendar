// LIBRARY
import './plugins/owlcarousel/owl.carousel.min'
import './plugins/rangeslider/ion.rangeSlider.min'
import './services/basic'
import './services/login'
import './services/home'
import './services/areas'
import './services/myaccount'
import './services/agendar'
import './services/visitas'

// APP
import './app';

import DataTable from 'datatables.net-dt';
 
let table = new DataTable('#tabela_dinamica',  {
    order: [[0, 'desc']],
    language: {
        url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json',
    },
});