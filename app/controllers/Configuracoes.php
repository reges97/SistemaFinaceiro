<?php

namespace app\controllers;

use app\models\CrudNotificacoes;

class Configuracoes extends CrudNotificacoes
{
    public function email()
    {
        // Tela de configuracao SMTP: centraliza os dados de envio de e-mail do sistema.
        require_once __DIR__ . '/../views/menu2.php';
        require_once __DIR__ . '/../views/configuracoes/email.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    public function whatsapp()
    {
        // Tela de configuracao WhatsApp/API: guarda provedor, URL e token usados nos avisos.
        require_once __DIR__ . '/../views/menu2.php';
        require_once __DIR__ . '/../views/configuracoes/whatsapp.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    public function layoutEmails()
    {
        // Tela de layouts: permite personalizar assunto, HTML e variaveis dos avisos financeiros.
        require_once __DIR__ . '/../views/menu2.php';
        require_once __DIR__ . '/../views/configuracoes/layout-emails.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    public function salvarEmail()
    {
        $this->salvarEmailConfig();
    }

    public function testarEmail()
    {
        $this->testarEmailConfig();
    }

    public function salvarLayoutEmail()
    {
        $this->salvarEmailLayout();
    }

    public function testarLayoutEmail()
    {
        $this->testarEmailLayout();
    }

    public function salvarWhatsapp()
    {
        $this->salvarWhatsappConfig();
    }

    public function testarWhatsapp()
    {
        $this->testarWhatsappConfig();
    }

    public function executarNotificacoes()
    {
        // Endpoint da rotina diaria: pode ser chamado por agendador para registrar avisos sem duplicidade.
        $this->processarAvisosAutomaticos();
    }
}
