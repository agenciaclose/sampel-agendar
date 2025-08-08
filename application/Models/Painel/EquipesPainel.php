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
    	public function getCargosList()
        {
            $read = new Read();
            $read->FullRead("SELECT * FROM roles ORDER BY `id` DESC");
            return $read;
        }

        public function getEquipesList()
        {
            $read = new Read();
            $read->FullRead("SELECT * FROM usuarios WHERE tipo in (4, 1) ORDER BY `nome` ASC");
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
            $params['senha'] = sha1('sampel4321');
            unset($params['email_old']);
            $params['codigo_privado'] = $this->getCodigoPrivado();

            if(!empty($params['senha'])){
                $params['senha'] = sha1($params['senha']);
            }else{
                $params['senha'] = '123mudar';
            }

            unset($params['cargos']);

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

            unset($params['cargos']);
            
            unset($params['email_old']);
            $update = new Update();
            $update->ExeUpdate('usuarios', $params, 'WHERE id = :id', "id={$params['id']}");
            return $update;
        }

        public function saveCargos(array $cargos, $id_user)
        {
            $read = new Read();
            $read->FullRead("DELETE FROM`usuario_roles` WHERE `id_user` = :id_user", "id_user={$id_user}");

            foreach($cargos as $cargo) {
                $params['id_role'] = $cargo;
                $params['id_user'] = $id_user;
                $create = new Create();
                $create->ExeCreate('usuario_roles', $params);
            }
        }

        public function salvarSenha(array $params)
        {
            $params['senha'] = sha1($params['senha']);
            unset($params['resenha']);
            $codigo_privado = $params['codigo_privado'];
            $params['codigo_privado'] = '';
            $update = new Update();
            $update->ExeUpdate('usuarios', $params, 'WHERE codigo_privado = :codigo_privado', "codigo_privado={$codigo_privado}");
            return $update;
        }

        public function statusEquipe($params)
        {
            $read = new Read();
            $read->FullRead("UPDATE `usuarios` SET `situacao` = :situacao WHERE id = :id", "situacao={$params['situacao']}&id={$params['id_user']}");
            return $read;
        }

        public function getCodigoPrivado() {
            $caracteres = 'abcdefghijklmnopqrstuvwxyz0123456789';
            $codigo = '';
            $tamanhoCodigo = 8;
            for ($i = 0; $i < $tamanhoCodigo; $i++) {
                $codigo .= $caracteres[random_int(0, strlen($caracteres) - 1)];
            }
            return $codigo;
        }

    }