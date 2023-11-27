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

        public function editarSave(array $params)
        {
            if(!empty($params['senha'])){
                $params['senha'] = sha1($params['senha']);
            }else{
                unset($params['senha']);
            }
            
            unset($params['email_old']);
            $update = new Update();
            $update->ExeUpdate('usuarios', $params, 'WHERE id = :id', "id={$params['id']}");
            return $update;
        }

    }