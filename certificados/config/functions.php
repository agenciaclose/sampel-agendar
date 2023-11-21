<?php
 	date_default_timezone_set("Brazil/East");

 	function separatorApplication($dados, $divisor){

		$separador_dados = '';

		if($dados != ''){

	        $separador = explode($divisor, $dados);

	        for ($s=0; $s <count($separador); $s++) {
	            if(($separador[$s] != '') && ($separador[$s] != ' ')){
	                $separador_dados .= '<span class="label">'.$separador[$s].'</span>';
	            }
	        }

		}

        return $separador_dados;
    }

    function monthTranslate($month) {
        switch ($month) {
            case 'Jan': $month = 'Jan'; break;
            case 'Feb': $month = 'Fev'; break;
            case 'Mar': $month = 'Mar'; break;
            case 'Apr': $month = 'Abr'; break;
            case 'May': $month = 'Mai'; break;
            case 'Jun': $month = 'Jun'; break;
            case 'Jul': $month = 'Jul'; break;
            case 'Aug': $month = 'Ago'; break;
            case 'Sep': $month = 'Set'; break;
            case 'Oct': $month = 'Out'; break;
            case 'Nov': $month = 'Nov'; break;
            case 'Dec': $month = 'Dez'; break;
        }
        return $month;
    }

	function text_uppercase($texto){
		$encoding = 'UTF-8';
		$texto = mb_convert_case(trim($texto), MB_CASE_UPPER, $encoding);
		return $texto;
	}

	function clearTelefone($palavra){
		$palavra = trim(preg_replace("/[\s]+/", " ", $palavra));
		trim($palavra);
		$palavra = str_replace("(","",$palavra);
		$palavra = str_replace(")","",$palavra);
		$palavra = str_replace("+","",$palavra);
		$palavra = str_replace("-","",$palavra);
		$palavra = str_replace(" ","",$palavra);
		return($palavra);
	}

	function clear_equivalenvcia($palavra){
		$palavra = trim(preg_replace("/[\s-]+/", " ", $palavra));
		trim($palavra);
		$palavra = str_replace("á","a",$palavra);
		$palavra = str_replace("à","a",$palavra);
		$palavra = str_replace("ã","a",$palavra);
		$palavra = str_replace("â","a",$palavra);
		$palavra = str_replace("é","e",$palavra);
		$palavra = str_replace("ê","e",$palavra);
		$palavra = str_replace("í","i",$palavra);
		$palavra = str_replace("ó","o",$palavra);
		$palavra = str_replace("ò","o",$palavra);
		$palavra = str_replace("Ò","o",$palavra);
		$palavra = str_replace("ô","o",$palavra);
		$palavra = str_replace("õ","o",$palavra);
		$palavra = str_replace("Ó","o",$palavra);
		$palavra = str_replace("Ö","o",$palavra);
		$palavra = str_replace("ú","u",$palavra);
		$palavra = str_replace("ü","u",$palavra);
		$palavra = str_replace("ç","c",$palavra);
		$palavra = str_replace("Á","a",$palavra);
		$palavra = str_replace("À","a",$palavra);
		$palavra = str_replace("Ã","a",$palavra);
		$palavra = str_replace("Â","a",$palavra);
		$palavra = str_replace("É","e",$palavra);
		$palavra = str_replace("Ê","e",$palavra);
		$palavra = str_replace("Í","i",$palavra);
		$palavra = str_replace("Ó","o",$palavra);
		$palavra = str_replace("Ô","o",$palavra);
		$palavra = str_replace("Õ","o",$palavra);
		$palavra = str_replace("Ú","u",$palavra);
		$palavra = str_replace("Ü","u",$palavra);
		$palavra = str_replace("Ç","c",$palavra);
		$palavra = str_replace("&","e",$palavra);
		$palavra = str_replace("$","s",$palavra);
		$palavra = str_replace("_","",$palavra);
		$palavra = str_replace("+","",$palavra);
		$palavra = str_replace(".","",$palavra);
		$palavra = str_replace("(","",$palavra);
		$palavra = str_replace(")","",$palavra);
		$palavra = str_replace(":","",$palavra);
		$palavra = str_replace(";","",$palavra);
		$palavra = str_replace("!","",$palavra);
		$palavra = str_replace("?","",$palavra);
		$palavra = str_replace("%","",$palavra);
		$palavra = str_replace("@","",$palavra);
		$palavra = str_replace("[","",$palavra);
		$palavra = str_replace("]","",$palavra);
		$palavra = str_replace("{","",$palavra);
		$palavra = str_replace("}","",$palavra);
		$palavra = str_replace("\"","",$palavra);
		$palavra = str_replace("/","",$palavra);
		$palavra = str_replace("'","",$palavra);
		$palavra = str_replace("'","",$palavra);
		$palavra = str_replace("'","",$palavra);
		$palavra = str_replace("---","",$palavra);
		$palavra = str_replace("--","",$palavra);
		$palavra = str_replace("|","",$palavra);
		$palavra = str_replace("º","",$palavra);
		$palavra = str_replace("ª","",$palavra);
		$palavra = str_replace("–","",$palavra);
		$palavra = str_replace("®","",$palavra);
		$palavra = str_replace("©","",$palavra);
		$palavra = str_replace("´","",$palavra);
		$palavra = str_replace("`","",$palavra);
		$palavra = str_replace("™","",$palavra);
		$palavra = str_replace("“","",$palavra);
		$palavra = str_replace("”","",$palavra);
		$palavra = str_replace("’","",$palavra);
		$palavra = str_replace("Ø","",$palavra);
		$palavra = str_replace("ì","",$palavra);
		$palavra = str_replace(" ","",$palavra);
		$palavra = str_replace(","," ",$palavra);
		return($palavra);
	}

	function noacento($palavra){
		$palavra = trim(preg_replace("/[\s]+/", " ", $palavra));
		trim($palavra);
		$palavra = str_replace("á","a",$palavra);
		$palavra = str_replace("à","a",$palavra);
		$palavra = str_replace("ã","a",$palavra);
		$palavra = str_replace("â","a",$palavra);
		$palavra = str_replace("é","e",$palavra);
		$palavra = str_replace("ê","e",$palavra);
		$palavra = str_replace("í","i",$palavra);
		$palavra = str_replace("ó","o",$palavra);
		$palavra = str_replace("ò","o",$palavra);
		$palavra = str_replace("Ò","o",$palavra);
		$palavra = str_replace("ô","o",$palavra);
		$palavra = str_replace("õ","o",$palavra);
		$palavra = str_replace("Ó","o",$palavra);
		$palavra = str_replace("Ö","o",$palavra);
		$palavra = str_replace("ú","u",$palavra);
		$palavra = str_replace("ü","u",$palavra);
		$palavra = str_replace("ç","c",$palavra);
		$palavra = str_replace("Á","a",$palavra);
		$palavra = str_replace("À","a",$palavra);
		$palavra = str_replace("Ã","a",$palavra);
		$palavra = str_replace("Â","a",$palavra);
		$palavra = str_replace("É","e",$palavra);
		$palavra = str_replace("Ê","e",$palavra);
		$palavra = str_replace("Í","i",$palavra);
		$palavra = str_replace("Ó","o",$palavra);
		$palavra = str_replace("Ô","o",$palavra);
		$palavra = str_replace("Õ","o",$palavra);
		$palavra = str_replace("Ú","u",$palavra);
		$palavra = str_replace("Ü","u",$palavra);
		$palavra = str_replace("Ç","c",$palavra);
		$palavra = str_replace("&","e",$palavra);
		$palavra = str_replace("$","s",$palavra);
		$palavra = str_replace("_","",$palavra);
		$palavra = str_replace("+","",$palavra);
		$palavra = str_replace(".","",$palavra);
		$palavra = str_replace("(","",$palavra);
		$palavra = str_replace(")","",$palavra);
		$palavra = str_replace(":","",$palavra);
		$palavra = str_replace(";","",$palavra);
		$palavra = str_replace("!","",$palavra);
		$palavra = str_replace(",","",$palavra);
		$palavra = str_replace("?","",$palavra);
		$palavra = str_replace("%","",$palavra);
		$palavra = str_replace("@","",$palavra);
		$palavra = str_replace("[","",$palavra);
		$palavra = str_replace("]","",$palavra);
		$palavra = str_replace("{","",$palavra);
		$palavra = str_replace("}","",$palavra);
		$palavra = str_replace("\"","",$palavra);
		$palavra = str_replace("/","",$palavra);
		$palavra = str_replace("'","",$palavra);
		$palavra = str_replace("'","",$palavra);
		$palavra = str_replace("'","",$palavra);
		$palavra = str_replace("-","",$palavra);
		$palavra = str_replace("---"," ",$palavra);
		$palavra = str_replace("--"," ",$palavra);
		$palavra = str_replace("|","",$palavra);
		$palavra = str_replace("º","",$palavra);
		$palavra = str_replace("ª","",$palavra);
		$palavra = str_replace("–","",$palavra);
		$palavra = str_replace("®","",$palavra);
		$palavra = str_replace("©","",$palavra);
		$palavra = str_replace("´","",$palavra);
		$palavra = str_replace("`","",$palavra);
		$palavra = str_replace("™","",$palavra);
		$palavra = str_replace("“","",$palavra);
		$palavra = str_replace("”","",$palavra);
		$palavra = str_replace("’","",$palavra);
		$palavra = str_replace("Ø","",$palavra);
		$palavra = str_replace("ì","",$palavra);
		$palavra = str_replace("-","",$palavra);
		return($palavra);
	}

	function _retirar_acentuacao($palavra){
		$palavra = trim(preg_replace("/[\s-]+/", " ", $palavra));
		trim($palavra);
		$palavra = str_replace("á","a",$palavra);
		$palavra = str_replace("à","a",$palavra);
		$palavra = str_replace("ã","a",$palavra);
		$palavra = str_replace("â","a",$palavra);
		$palavra = str_replace("é","e",$palavra);
		$palavra = str_replace("ê","e",$palavra);
		$palavra = str_replace("í","i",$palavra);
		$palavra = str_replace("ó","o",$palavra);
		$palavra = str_replace("ô","o",$palavra);
		$palavra = str_replace("õ","o",$palavra);
		$palavra = str_replace("ú","u",$palavra);
		$palavra = str_replace("ü","u",$palavra);
		$palavra = str_replace("ç","c",$palavra);
		$palavra = str_replace("Á","a",$palavra);
		$palavra = str_replace("À","a",$palavra);
		$palavra = str_replace("Ã","a",$palavra);
		$palavra = str_replace("Â","a",$palavra);
		$palavra = str_replace("É","e",$palavra);
		$palavra = str_replace("Ê","e",$palavra);
		$palavra = str_replace("Í","i",$palavra);
		$palavra = str_replace("Ó","o",$palavra);
		$palavra = str_replace("Ô","o",$palavra);
		$palavra = str_replace("Õ","o",$palavra);
		$palavra = str_replace("Ú","u",$palavra);
		$palavra = str_replace("Ü","u",$palavra);
		$palavra = str_replace("Ç","c",$palavra);
		$palavra = str_replace(" ","-",$palavra);
		$palavra = str_replace("_","-",$palavra);
		$palavra = str_replace("&","e",$palavra);
		$palavra = str_replace("+","-",$palavra);
		$palavra = str_replace(".","",$palavra);
		$palavra = str_replace("(","",$palavra);
		$palavra = str_replace(")","",$palavra);
		$palavra = str_replace(":","",$palavra);
		$palavra = str_replace(";","",$palavra);
		$palavra = str_replace("!","",$palavra);
		$palavra = str_replace(",","",$palavra);
		$palavra = str_replace("?","",$palavra);
		$palavra = str_replace("%","",$palavra);
		$palavra = str_replace("@","",$palavra);
		$palavra = str_replace("$","s",$palavra);
		$palavra = str_replace("[","",$palavra);
		$palavra = str_replace("]","",$palavra);
		$palavra = str_replace("{","",$palavra);
		$palavra = str_replace("}","",$palavra);
		$palavra = str_replace("\"","",$palavra);
		$palavra = str_replace("/","-",$palavra);
		$palavra = str_replace("'","",$palavra);
		$palavra = str_replace("'","",$palavra);
		$palavra = str_replace("'","",$palavra);
		$palavra = str_replace("---","-",$palavra);
		$palavra = str_replace("--","-",$palavra);
		$palavra = str_replace("|","",$palavra);
		$palavra = str_replace("º","",$palavra);
		$palavra = str_replace("ª","",$palavra);
		$palavra = str_replace("Ó","o",$palavra);
		$palavra = str_replace("Ö","o",$palavra);
		$palavra = str_replace("–","",$palavra);
		$palavra = str_replace("–","",$palavra);
		$palavra = str_replace("®","",$palavra);
		$palavra = str_replace("©","",$palavra);
		$palavra = str_replace("´","",$palavra);
		$palavra = str_replace("`","",$palavra);
		$palavra = str_replace("™","",$palavra);
		$palavra = str_replace("“","",$palavra);
		$palavra = str_replace("”","",$palavra);
		$palavra = str_replace("’","",$palavra);
		$palavra = str_replace("Ø","",$palavra);
		$palavra = str_replace("ì","",$palavra);
		return($palavra);
	}

	function limparcodigo($palavra){
		$palavra = trim(preg_replace("/[\s]+/", " ", $palavra));
		trim($palavra);
		$palavra = str_replace("á","a",$palavra);
		$palavra = str_replace("à","a",$palavra);
		$palavra = str_replace("ã","a",$palavra);
		$palavra = str_replace("â","a",$palavra);
		$palavra = str_replace("é","e",$palavra);
		$palavra = str_replace("ê","e",$palavra);
		$palavra = str_replace("í","i",$palavra);
		$palavra = str_replace("ó","o",$palavra);
		$palavra = str_replace("ô","o",$palavra);
		$palavra = str_replace("õ","o",$palavra);
		$palavra = str_replace("ú","u",$palavra);
		$palavra = str_replace("ü","u",$palavra);
		$palavra = str_replace("ç","c",$palavra);
		$palavra = str_replace("Á","a",$palavra);
		$palavra = str_replace("À","a",$palavra);
		$palavra = str_replace("Ã","a",$palavra);
		$palavra = str_replace("Â","a",$palavra);
		$palavra = str_replace("É","e",$palavra);
		$palavra = str_replace("Ê","e",$palavra);
		$palavra = str_replace("Í","i",$palavra);
		$palavra = str_replace("Ó","o",$palavra);
		$palavra = str_replace("Ô","o",$palavra);
		$palavra = str_replace("Õ","o",$palavra);
		$palavra = str_replace("Ú","u",$palavra);
		$palavra = str_replace("Ü","u",$palavra);
		$palavra = str_replace("Ç","c",$palavra);
		$palavra = str_replace("&","e",$palavra);
		$palavra = str_replace("@","",$palavra);
		$palavra = str_replace("$","s",$palavra);
		$palavra = str_replace(" ","",$palavra);
		$palavra = str_replace("-","",$palavra);
		$palavra = str_replace("º","",$palavra);
		$palavra = str_replace("ª","",$palavra);
		$palavra = str_replace("Ó","o",$palavra);
		$palavra = str_replace("Ö","o",$palavra);
		$palavra = str_replace("®","",$palavra);
		$palavra = str_replace("©","",$palavra);
		$palavra = str_replace("´","",$palavra);
		$palavra = str_replace("`","",$palavra);
		$palavra = str_replace("™","",$palavra);
		$palavra = str_replace("“","",$palavra);
		$palavra = str_replace("”","",$palavra);
		$palavra = str_replace("’","",$palavra);
		$palavra = str_replace("Ø","",$palavra);
		$palavra = str_replace("ì","",$palavra);
		$palavra = str_replace("'","",$palavra);
		$palavra = str_replace("(","",$palavra);
		$palavra = str_replace(")","",$palavra);
		$palavra = str_replace("+","",$palavra);
		$palavra = str_replace("-","",$palavra);
		return($palavra);
	}

	function limparApp($palavra){
		$palavra = trim(preg_replace("/[\s]+/", " ", $palavra));
		trim($palavra);
		$palavra = str_replace("â€²","",$palavra);
		$palavra = str_replace("Â€²","",$palavra);
		$palavra = str_replace("â€³","",$palavra);
		$palavra = str_replace("Â€³","",$palavra);
		return($palavra);
	}

	function ClearSearch($pesquisa){
		$palavras = array("da","de","di","do","du","para","pra","em","in","por","até","ate");
		return preg_replace('/\b('.implode('|',$palavras).')\b/','',$pesquisa);
	}

	function limitarTexto($texto, $limite){
		$contador = strlen($texto);
		if ( $contador >= $limite ) {      
			$texto = substr($texto, 0, strrpos(substr($texto, 0, $limite), ' ')) . '...';
			return $texto;
		}
		else{
			return $texto;
		}
	}
	
	setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

function codigo_label($codigo){
	$itens = '';
	$codigos = explode(',', $codigo);
	for ($i=0; $i < count($codigos); $i++) { 
		$itens .= '<span class="label">'.$codigos[$i].'</span>';
	}
	return $itens;
}

function isMobileDevice() { 
	return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo 
		|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]); 
}

function checkMobileDevice(){

	$iPod    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
	$iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
	$iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
	$Android = stripos($_SERVER['HTTP_USER_AGENT'],"Android");
	$webOS   = stripos($_SERVER['HTTP_USER_AGENT'],"webOS");

	if( $iPod || $iPhone ){
		return 'IOS';
	}else if($iPad){
		return 'IOS';
	}else if($Android){
		return 'ANDROID';
	}else if($webOS){
		return 'WEB';
	}

}

function get_client_ip() {
	$ipaddress = '';
	if (isset($_SERVER['HTTP_CLIENT_IP']))
		$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	else if(isset($_SERVER['HTTP_X_FORWARDED']))
		$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
		$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	else if(isset($_SERVER['HTTP_FORWARDED']))
		$ipaddress = $_SERVER['HTTP_FORWARDED'];
	else if(isset($_SERVER['REMOTE_ADDR']))
		$ipaddress = $_SERVER['REMOTE_ADDR'];
	else
		$ipaddress = 'UNKNOWN';
	return $ipaddress;
}

function formatarCPF($cpf) {
    $cpf = (string)$cpf;
    if(strlen($cpf) != 11) {
        return "CPF inválido";
    }
    return substr($cpf, 0, 3) . '.' .
           substr($cpf, 3, 3) . '.' .
           substr($cpf, 6, 3) . '-' .
           substr($cpf, 9, 2);
}

function formatarData($dataOriginal) {
    $data = new DateTime($dataOriginal);
    return $data->format('d-m-Y');
}