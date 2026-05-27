<?php

namespace app\models;

class CrudAcessos extends Connection
{
    public function recuperaDados()
    {
        @$id_usuario = $_SESSION['id'];
        $pdo = $this->connect();

        // Consulta segura: dados do usuario logado sao usados no cabecalho/menu.
        $stmt = $pdo->prepare("SELECT id, nome_usu, email, nivel FROM usuarios WHERE id = :id LIMIT 1");
        $stmt->bindValue(':id', (int) $id_usuario, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function listarAcesso()
    {
        $pdo = $this->connect();

        // Lista com nomes legiveis: facilita auditoria das permissoes cadastradas.
        $query = $pdo->query("SELECT A.id_aces, A.usu_id, A.menu_id, A.acesso, A.nivel_aces,
            COALESCE(A.sub_menu, '') AS sub_menu, U.nome_usu, M.menu
            FROM acessos A
            LEFT JOIN usuarios U ON U.id = A.usu_id
            LEFT JOIN menu M ON M.id_menu = A.menu_id
            ORDER BY A.id_aces DESC");

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function listarUsu()
    {
        @session_start();

        @$id_usuario = $_SESSION['id'];

        $pdo = $this->connect();

        // Permissoes manuais por usuario: nao depende mais de nivel_aces, que podia bloquear acessos liberados.
        $query = $pdo->prepare("SELECT U.id, U.nome_usu, U.nivel, A.usu_id, A.menu_id,
            A.acesso, A.nivel_aces, M.menu, M.ativo
            FROM acessos AS A
            INNER JOIN usuarios AS U ON A.usu_id = U.id
            INNER JOIN menu AS M ON A.menu_id = M.id_menu
            WHERE U.id = :id_usuario
              AND LOWER(A.acesso) = 'sim'
            ORDER BY A.id_aces");
        $query->bindValue(':id_usuario', (int) $id_usuario, \PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function inserir()
    {
        $pagina = 'acessos';
        $campo1 = 'Nome';
        $campo2 = 'Menu';
        $campo3 = 'Nivel';
        $campo4 = 'Acesso';
        $campo5 = 'Sub_menu';

        $cp1 = filter_input(INPUT_POST, $campo1, FILTER_VALIDATE_INT);
        $cp2 = filter_input(INPUT_POST, $campo2, FILTER_VALIDATE_INT);
        $cp4 = filter_input(INPUT_POST, $campo4, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $cp3 = Permissoes::normalizarNivel(filter_input(INPUT_POST, $campo3, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $cp5 = filter_input(INPUT_POST, $campo5, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $cp4 = strtolower((string) $cp4) === 'sim' ? 'Sim' : 'Nao';

        if (!$cp1 || !$cp2 || $cp3 === '') {
            echo 'Preencha usuario, menu e nivel.';
            exit();
        }

        $pdo = $this->connect();

        if (!$id) {
            // Insert seguro: acesso manual continua disponivel, mas segue o mesmo padrao de "Sim/Nao".
            $query = $pdo->prepare("INSERT INTO $pagina SET usu_id = :campo1,
                menu_id = :campo2, acesso = :campo4,
                nivel_aces = :campo3, sub_menu = :campo5");
        } else {
            // Update seguro: id validado evita alteracao indevida por parametro manipulado.
            $query = $pdo->prepare("UPDATE $pagina SET usu_id = :campo1,
                menu_id = :campo2, acesso = :campo4, nivel_aces = :campo3,
                sub_menu = :campo5 WHERE id_aces = :id");
            $query->bindValue(':id', $id, \PDO::PARAM_INT);
        }

        $query->bindValue(':campo1', $cp1, \PDO::PARAM_INT);
        $query->bindValue(':campo2', $cp2, \PDO::PARAM_INT);
        $query->bindValue(':campo4', $cp4);
        $query->bindValue(':campo3', $cp3);
        $query->bindValue(':campo5', $cp5 ?: null);
        $query->execute();

        echo 'Salvo com Sucesso';

        return $query;
    }

    public function excluir()
    {
        $id = filter_input(INPUT_POST, 'id-excluir', FILTER_VALIDATE_INT);

        if (!$id) {
            echo 'Registro invalido';
            exit();
        }

        $pdo = $this->connect();

        // Correcao critica: excluir permissao nao pode apagar usuario.
        $query = $pdo->prepare('DELETE FROM acessos WHERE id_aces = :id');
        $query->bindValue(':id', $id, \PDO::PARAM_INT);
        $query->execute();

        echo 'Excluido com Sucesso';

        return $query;
    }
}
