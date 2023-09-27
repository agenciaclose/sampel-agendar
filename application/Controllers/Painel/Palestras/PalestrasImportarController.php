<?php

namespace Agencia\Close\Controllers\Painel\PalestrasImportarController;

use Agencia\Close\Controllers\Controller;
//use Agencia\Close\Models\Painel\PalestrasPainel;
use Shuchkin\SimpleXLSX;

class PalestrasImportarController extends Controller
{

    public function importar($params)
    {
        $this->setParams($params);

        if ($xlsx = SimpleXLSX::parse($_FILES['informacoes_arquivo']['tmp_name'])) {
			$arquivo = $xlsx->rows();
            var_dump($arquivo);
		} else {
			echo "ALGUM ERRO AO IMPORTAR";
		}


        // $palestras = new PalestrasPainel();
        // $palestras = $palestras->getPalestrasList()->getResult();
    }

}