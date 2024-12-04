<?php

namespace Agencia\Close\Controllers\Painel\ContratosPainel;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Painel\ContratosPainel;
use Agencia\Close\Models\Painel\PedidosPainel;

class ContratosPainelController extends Controller
{
    public function index($params)
    {
        $this->setParams($params);
        $this->permissions('contratos', '"view"');

        $model = new ContratosPainel();
        $contratos = $model->getContratos()->getResult();

        $this->render('painel/pages/contratos/index.twig', ['menu' => 'contratos', 'contratos' => $contratos]);
    }

    public function productAdd($params)
    {
        $this->setParams($params);
        $paises = $this->nomesPaises();
        $this->render('painel/pages/contratos/form.twig', ['paises' => $paises]);
    }

    public function productEdit($params)
    {
        $this->setParams($params);

        $contrato = new ContratosPainel();
        $contrato = $contrato->getContratoID($params['id'])->getResult();

        $paises = $this->nomesPaises();
        
        $this->render('painel/pages/contratos/form.twig', ['contrato' => $contrato[0], 'paises' => $paises]);
    }

    public function nomesPaises()
    {
        $paises = ['Brasil','Afeganistão','África do Sul','Albânia','Alemanha','Andorra','Angola','Antígua e Barbuda','Arábia Saudita','Argélia','Argentina','Armênia','Austrália','Áustria','Azerbaijão','Bahamas','Bangladesh','Barbados','Bahrein','Belarus','Bélgica','Belize','Benin','Butão','Bolívia','Bósnia e Herzegovina','Botsuana','Brunei','Bulgária','Burquina Faso','Burundi','Cabo Verde','Camboja','Camarões','Canadá','Catar','Cazaquistão','Chade','Chile','China','Chipre','Colômbia','Comores','Congo-Brazzaville','Congo-Kinshasa','Coreia do Norte','Coreia do Sul','Costa do Marfim','Costa Rica','Croácia','Cuba','Dinamarca','Djibouti','Dominica','Egito','El Salvador','Emirados Árabes Unidos','Equador','Eritreia','Eslováquia','Eslovênia','Espanha','Estado da Palestina','Estados Unidos','Estônia','Eswatini','Etiópia','Fiji','Filipinas','Finlândia','França','Gabão','Gâmbia','Gana','Geórgia','Granada','Grécia','Guatemala','Guiana','Guiné','Guiné Equatorial','Guiné-Bissau','Haiti','Honduras','Hungria','Iêmen','Ilhas Marshall','Ilhas Salomão','Índia','Indonésia','Irã','Iraque','Irlanda','Islândia','Israel','Itália','Jamaica','Japão','Jordânia','Kiribati','Kuwait','Laos','Lesoto','Letônia','Líbano','Libéria','Líbia','Liechtenstein','Lituânia','Luxemburgo','Macedônia do Norte','Madagascar','Malásia','Malaui','Maldivas','Mali','Malta','Marrocos','Maurícia','Mauritânia','México','Mianmar','Micronésia','Moçambique','Moldova','Mônaco','Mongólia','Montenegro','Namíbia','Nauru','Nepal','Nicarágua','Níger','Nigéria','Noruega','Nova Zelândia','Omã','Países Baixos','Palau','Panamá','Papua-Nova Guiné','Paquistão','Paraguai','Peru','Polônia','Portugal','Quênia','Quirguistão','Reino Unido','República Centro-Africana','República Dominicana','República Tcheca','Romênia','Ruanda','Rússia','Samoa','San Marino','Santa Lúcia','São Cristóvão e Nevis','São Tomé e Príncipe','São Vicente e Granadinas','Seicheles','Senegal','Serra Leoa','Sérvia','Singapura','Síria','Somália','Sri Lanka','Sudão','Sudão do Sul','Suécia','Suíça','Suriname','Tailândia','Taiwan','Tajiquistão','Tanzânia','Timor-Leste','Togo','Tonga','Trinidad e Tobago','Tunísia','Turcomenistão','Turquia','Tuvalu','Ucrânia','Uganda','Uruguai','Uzbequistão','Vanuatu','Vaticano','Venezuela','Vietnã','Zâmbia','Zimbábue'];
        return $paises;
    }

    public function productStatus($params)
    {
        $this->setParams($params);
        
        //VERIFICA SE EXISTE PEDIDOS NAQUELE Contratos
        $model = new PedidosPainel();
        $pedidos = $model->getPedidoOrcamentoID($params['id'], 'contratos');
        
        if($pedidos->getResult()){
            $pedidoParams = [];
            foreach ($pedidos->getResult() as $pedido) {
                
                $pedidoParams['id'] = $pedido['id'];
                $pedidoParams['status_pedido'] = 0;
                
                $model = new PedidosPainel();
                $model->statusRecusadoSave($pedidoParams);                
            }
        }

        //DESATIVA O Contratos
        $status = new ContratosPainel();
        $status = $status->getContratoStatus($params);
        if($status){
            echo 'success';
        }else{
            echo 'error';
        }

    }

    public function addContratoSave($params)
    {
        $this->setParams($params);
        $save = new ContratosPainel();
        $save = $save->addContratoSave($params);
        if($save){ echo $save; }
    }

    public function editContratoSave($params)
    {
        $this->setParams($params);
        $save = new ContratosPainel();
        $save = $save->editContratoSave($params);
        if($save){ echo '1'; }
    }

}