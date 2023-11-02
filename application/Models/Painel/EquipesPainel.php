<?php

    namespace Agencia\Close\Models\Painel;

    use Agencia\Close\Conn\Conn;
    use Agencia\Close\Conn\Create;
    use Agencia\Close\Conn\Read;
    use Agencia\Close\Conn\Update;
    use Agencia\Close\Helpers\User\Identification;
    use Agencia\Close\Helpers\User\UserIdentification;
    use Agencia\Close\Models\Model;

    class EquipesPainel extends Model
    {
    	
        public function getEquipesList()
        {
            $read = new Read();
            $read->FullRead("SELECT * FROM usuarios WHERE tipo = '4' ORDER BY `nome` ASC");
            return $read;
        }

        public function getEquipesEditar($id)
        {
            $read = new Read();
            $read->FullRead("SELECT * FROM usuarios WHERE id = :id ORDER BY `nome` ASC", "id={$id}");
            return $read;
        }

        public function cadastroSave(array $params)
        {
            $params['senha'] = sha1($params['senha']);
            $create = new Create();
            $create->ExeCreate('usuarios', $params);
            return $create->getResult();
        }

    }