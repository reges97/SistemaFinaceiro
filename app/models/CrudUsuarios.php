<?php

namespace app\models;

class CrudUsuarios extends Connection
{
    private function filtrarTexto($campo)
    {
        $valor = filter_input(INPUT_POST, $campo, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        return is_string($valor) ? trim($valor) : '';
    }

    private function senhaValida($senhaDigitada, $senhaSalva)
    {
        if ($senhaSalva === null || $senhaSalva === '') {
            return false;
        }

        if (password_verify($senhaDigitada, $senhaSalva)) {
            return true;
        }

        return hash_equals($senhaSalva, md5($senhaDigitada)) || hash_equals($senhaSalva, $senhaDigitada);
    }

    private function atualizarSenhaSeNecessario($id, $senhaDigitada, $senhaSalva)
    {
        if (password_verify($senhaDigitada, $senhaSalva) && !password_needs_rehash($senhaSalva, PASSWORD_DEFAULT)) {
            return;
        }

        $pdo = $this->connect();
        $query = $pdo->prepare('UPDATE usuarios SET senha = :senha WHERE id = :id');
        $query->bindValue(':senha', password_hash($senhaDigitada, PASSWORD_DEFAULT));
        $query->bindValue(':id', $id, \PDO::PARAM_INT);
        $query->execute();
    }

    public function cadastrar()
    {
        $pdo = $this->connect();
        $stmt = $pdo->query("SELECT * FROM usuarios WHERE nivel IN ('Administrador', 'Adm')");
        $dados = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (count($dados) === 0) {
            $emailAdm = getenv('ADMIN_EMAIL') ?: 'admin@sistema.local';
            $nomeAdmin = getenv('ADMIN_NAME') ?: 'Administrador';
            $senhaAdmin = getenv('ADMIN_PASSWORD') ?: 'admin123';

            $stmt = $pdo->prepare("INSERT INTO usuarios SET nome_usu = :nome, email = :email, senha = :senha, nivel = 'Administrador'");
            $stmt->bindValue(':nome', $nomeAdmin);
            $stmt->bindValue(':email', $emailAdm);
            $stmt->bindValue(':senha', password_hash($senhaAdmin, PASSWORD_DEFAULT));
            $stmt->execute();
        }

        return $stmt;
    }

    public function logar()
    {
        $email = $this->filtrarTexto('email');
        $senha = $this->filtrarTexto('senha');

        if ($email === '' || $senha === '') {
            return [];
        }

        $pdo = $this->connect();
        $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE email = :email LIMIT 1');
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$usuario || !$this->senhaValida($senha, $usuario['senha'])) {
            return [];
        }

        $this->atualizarSenhaSeNecessario($usuario['id'], $senha, $usuario['senha']);
        unset($usuario['senha']);

        return [$usuario];
    }

    public function recuperaDados()
    {
        $idUsuario = filter_var(@$_SESSION['id'], FILTER_VALIDATE_INT);

        if (!$idUsuario) {
            return [];
        }

        $pdo = $this->connect();
        $stmt = $pdo->prepare('SELECT id, nome_usu, email, nivel FROM usuarios WHERE id = :id LIMIT 1');
        $stmt->bindValue(':id', $idUsuario, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function update()
    {
        $nome = $this->filtrarTexto('nome-usuario');
        $email = $this->filtrarTexto('email-usuario');
        $senha = $this->filtrarTexto('senha-usuario');
        $id = filter_input(INPUT_POST, 'id-usuario', FILTER_VALIDATE_INT);

        if (!$id || $nome === '' || $email === '') {
            echo 'Dados obrigatorios nao informados';
            exit();
        }

        $pdo = $this->connect();
        $query = $pdo->prepare('SELECT id, nome_usu FROM usuarios WHERE email = :email LIMIT 1');
        $query->bindValue(':email', $email);
        $query->execute();
        $res = $query->fetch(\PDO::FETCH_ASSOC);

        if ($res && (int) $res['id'] !== (int) $id) {
            echo 'Este email ja esta cadastrado para o usuario ' . $res['nome_usu'] . ', escolha outro email!';
            exit();
        }

        if ($senha !== '') {
            $query = $pdo->prepare('UPDATE usuarios SET nome_usu = :nome, email = :email, senha = :senha WHERE id = :id');
            $query->bindValue(':senha', password_hash($senha, PASSWORD_DEFAULT));
        } else {
            $query = $pdo->prepare('UPDATE usuarios SET nome_usu = :nome, email = :email WHERE id = :id');
        }

        $query->bindValue(':nome', $nome);
        $query->bindValue(':email', $email);
        $query->bindValue(':id', $id, \PDO::PARAM_INT);
        $query->execute();

        return $query;
    }

    public function selecao()
    {
        $pdo = $this->connect();
        $query = $pdo->query('SELECT * FROM niveis ORDER BY nivel ASC');

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function listarNiv()
    {
        $pdo = $this->connect();
        $query = $pdo->query('SELECT * FROM niveis ORDER BY id DESC');

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function listarUsu()
    {
        $pdo = $this->connect();
        $query = $pdo->query('SELECT id, nome_usu, email, nivel FROM usuarios ORDER BY id DESC');

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function inserir()
    {
        $pagina = 'usuarios';
        $cp1 = $this->filtrarTexto('Nome');
        $cp2 = $this->filtrarTexto('Email');
        $cp3 = $this->filtrarTexto('Senha');
        // Perfil normalizado: evita duplicidade entre Comum/Usuario e Adm/Administrador.
        $cp4 = Permissoes::normalizarNivel($this->filtrarTexto('Nivel'));
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if ($cp1 === '' || $cp2 === '' || $cp4 === '') {
            echo 'Preencha os campos obrigatorios';
            exit();
        }

        if (!$id && $cp3 === '') {
            echo 'Informe uma senha para o novo usuario';
            exit();
        }

        $pdo = $this->connect();
        $query = $pdo->prepare("SELECT id FROM {$pagina} WHERE email = :email LIMIT 1");
        $query->bindValue(':email', $cp2);
        $query->execute();
        $res = $query->fetch(\PDO::FETCH_ASSOC);

        if ($res && (!$id || (int) $res['id'] !== (int) $id)) {
            echo 'Este registro ja esta cadastrado!';
            exit();
        }

        if (!$id) {
            $query = $pdo->prepare("INSERT INTO {$pagina} SET nome_usu = :campo1, email = :campo2, senha = :campo3, nivel = :campo4");
            $query->bindValue(':campo3', password_hash($cp3, PASSWORD_DEFAULT));
        } elseif ($cp3 !== '') {
            $query = $pdo->prepare("UPDATE {$pagina} SET nome_usu = :campo1, email = :campo2, senha = :campo3, nivel = :campo4 WHERE id = :id");
            $query->bindValue(':campo3', password_hash($cp3, PASSWORD_DEFAULT));
            $query->bindValue(':id', $id, \PDO::PARAM_INT);
        } else {
            $query = $pdo->prepare("UPDATE {$pagina} SET nome_usu = :campo1, email = :campo2, nivel = :campo4 WHERE id = :id");
            $query->bindValue(':id', $id, \PDO::PARAM_INT);
        }

        $query->bindValue(':campo1', $cp1);
        $query->bindValue(':campo2', $cp2);
        $query->bindValue(':campo4', $cp4);
        $query->execute();

        // Garantia de cadastro: cria o nivel escolhido caso o banco ainda nao tenha esse perfil padrao.
        $this->garantirNivel($pdo, $cp4);

        echo 'Salvo com Sucesso';

        return $query;
    }

    private function garantirNivel(\PDO $pdo, $nivel)
    {
        $stmt = $pdo->prepare('SELECT id FROM niveis WHERE nivel = :nivel LIMIT 1');
        $stmt->bindValue(':nivel', $nivel);
        $stmt->execute();

        if ($stmt->fetchColumn()) {
            return;
        }

        $stmt = $pdo->prepare('INSERT INTO niveis SET nivel = :nivel');
        $stmt->bindValue(':nivel', $nivel);
        $stmt->execute();
    }

    public function excluir()
    {
        $id = filter_input(INPUT_POST, 'id-excluir', FILTER_VALIDATE_INT);

        if (!$id) {
            echo 'Registro invalido';
            exit();
        }

        $pdo = $this->connect();
        $query = $pdo->prepare('DELETE FROM usuarios WHERE id = :id');
        $query->bindValue(':id', $id, \PDO::PARAM_INT);
        $query->execute();

        echo 'Excluido com Sucesso';

        return $query;
    }
}
