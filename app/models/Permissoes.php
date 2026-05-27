<?php

namespace app\models;

class Permissoes extends Connection
{
    // Permissoes profissionais: centraliza a regra para menu, painel e bloqueio por URL.
    private static $perfis = [
        'Administrador' => [
            'Site' => ['home', 'homePainel', 'listar', 'editar', 'verificar'],
            '*' => ['*']
        ],
        'Financeiro' => [
            'Site' => ['home', 'homePainel', 'listar', 'editar', 'verificar'],
            'Clientes' => ['*'],
            'Bancos' => ['*'],
            'Banca' => ['*'],
            'CatDespesas' => ['*'],
            'Despesas' => ['*'],
            'Freq' => ['*'],
            'FormPgtos' => ['*'],
            'ContasPagar' => ['*'],
            'ContasReceber' => ['*'],
            'LancaDespesas' => ['*'],
            'Mov' => ['*'],
            'Caixa' => ['*'],
            'Saldo' => ['*'],
            'ControleCaixa' => ['*'],
            'Fluxo' => ['*'],
            'Conci' => ['*'],
            'Diario' => ['*'],
            'Vendas' => ['*'],
            'Configuracoes' => ['executarNotificacoes'],
            'Prod' => ['produtos', 'listar', 'relProdutos_class'],
            'Pagamento' => ['*']
        ],
        'Vendedor' => [
            'Site' => ['home', 'listar', 'editar', 'verificar'],
            'Clientes' => ['*'],
            'Prod' => ['produtos', 'listar', 'relProdutos_class'],
            'Vendas' => ['*']
        ],
        'Estoque' => [
            'Site' => ['home', 'listar', 'editar', 'verificar'],
            'Prod' => ['*'],
            'CatProd' => ['*'],
            'Forne' => ['*']
        ]
    ];

    // Mapa entre controllers e menus: permite que permissoes manuais da tabela acessos funcionem no backend.
    private static $menusPorController = [
        'Clientes' => [1],
        'Bancos' => [2],
        'Banca' => [3],
        'CatDespesas' => [4],
        'Despesas' => [5],
        'Freq' => [6],
        'FormPgtos' => [7],
        'Prod' => [8, 11, 26],
        'CatProd' => [9],
        'Forne' => [10],
        'ContasPagar' => [12],
        'ContasReceber' => [13],
        'LancaDespesas' => [14],
        'Saldo' => [15, 27],
        'Fluxo' => [16, 28],
        'Conci' => [17],
        'Diario' => [18],
        'Caixa' => [20],
        'ControleCaixa' => [21],
        'Vendas' => [22],
        'User' => [23],
        'Adm' => [24],
        'Acessos' => [25],
        // Configuracoes: reserva menus administrativos para SMTP, WhatsApp e rotina de avisos.
        'Configuracoes' => [29, 30]
    ];

    public static function normalizarNivel($nivel)
    {
        $nivel = trim((string) $nivel);
        $nivelNormalizado = mb_strtolower($nivel, 'UTF-8');

        // Compatibilidade: usuarios antigos continuam entrando no perfil correto.
        $aliases = [
            'Adm' => 'Administrador',
            'adm' => 'Administrador',
            'admin' => 'Administrador',
            'administrador' => 'Administrador',
            'Usuario' => 'Vendedor',
            'usuario' => 'Vendedor',
            'Comum' => 'Vendedor'
        ];

        return $aliases[$nivel] ?? $aliases[$nivelNormalizado] ?? $nivel;
    }

    public static function emailAdministradorTotal($email)
    {
        $email = mb_strtolower(trim((string) $email), 'UTF-8');

        // Administrador principal: garante acesso total mesmo quando a sessao antiga vier com nivel incorreto.
        return $email === 'reginaldo97.rr@gmail.com';
    }

    public static function perfisDisponiveis()
    {
        return ['Administrador', 'Financeiro', 'Vendedor', 'Estoque'];
    }

    public static function descricaoPerfil($perfil)
    {
        $descricoes = [
            'Administrador' => 'Acesso total ao sistema, usuarios, permissoes e financeiro.',
            'Financeiro' => 'Acesso aos valores, contas, caixa, conciliacao e painel financeiro.',
            'Vendedor' => 'Acesso a vendas, clientes e produtos sem visualizar saldos ou contas.',
            'Estoque' => 'Acesso a produtos, categorias, fornecedores e controle operacional de estoque.'
        ];

        return $descricoes[$perfil] ?? '';
    }

    public static function canAccess($nivel, $controller, $method = '')
    {
        $perfil = self::normalizarNivel($nivel);
        $controller = ucfirst((string) $controller);
        $method = (string) $method;

        if (!isset(self::$perfis[$perfil])) {
            return false;
        }

        $permissoes = self::$perfis[$perfil];

        if (isset($permissoes['*'])) {
            return self::methodAllowed($permissoes['*'], $method);
        }

        if (!isset($permissoes[$controller])) {
            return false;
        }

        return self::methodAllowed($permissoes[$controller], $method);
    }

    public static function canAccessUser($nivel, $controller, $method = '', $usuarioId = null)
    {
        if (self::canAccess($nivel, $controller, $method)) {
            return true;
        }

        if (!$usuarioId) {
            return false;
        }

        return self::usuarioTemAcessoManual((int) $usuarioId, $controller);
    }

    public static function usuarioTemAcessoManual($usuarioId, $controller)
    {
        $controller = ucfirst((string) $controller);

        if (!isset(self::$menusPorController[$controller])) {
            return false;
        }

        $instancia = new self();
        $pdo = $instancia->connect();

        // Permissao manual: respeita o usuario e o menu liberado, independente do nivel salvo em nivel_aces.
        $placeholders = implode(',', array_fill(0, count(self::$menusPorController[$controller]), '?'));
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM acessos
            WHERE usu_id = ?
              AND menu_id IN ($placeholders)
              AND LOWER(acesso) = 'sim'");
        $params = array_merge([$usuarioId], self::$menusPorController[$controller]);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn() > 0;
    }

    public static function menusPermitidosUsuario($usuarioId)
    {
        if (!$usuarioId) {
            return [];
        }

        $instancia = new self();
        $pdo = $instancia->connect();

        // Lista direta para o frontend: evita esconder grupos quando o acesso foi dado manualmente.
        $stmt = $pdo->prepare("SELECT DISTINCT menu_id FROM acessos
            WHERE usu_id = :usuario
              AND LOWER(acesso) = 'sim'");
        $stmt->bindValue(':usuario', (int) $usuarioId, \PDO::PARAM_INT);
        $stmt->execute();

        return array_map('intval', $stmt->fetchAll(\PDO::FETCH_COLUMN));
    }

    private static function methodAllowed(array $methods, $method)
    {
        return in_array('*', $methods, true) || $method === '' || in_array($method, $methods, true);
    }
}
