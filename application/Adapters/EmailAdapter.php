<?php

namespace Agencia\Close\Adapters;

use Agencia\Close\Helpers\Result;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailAdapter
{
    private PHPMailer $mail;
    private Result $result;

    const Host = MAIL_HOST;
    const Email = MAIL_EMAIL;
    const User = MAIL_USER;
    const Password = MAIL_PASSWORD;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->result = new Result();
        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();
        $this->mail->CharSet = 'UTF-8';
        $this->mail->Host = self::Host;
        $this->mail->SMTPAuth = true;
        $this->mail->Username = self::User;
        $this->mail->Password = self::Password;
        $this->mail->Port = defined('MAIL_PORT') ? (int) MAIL_PORT : 587;
        $this->mail->SMTPSecure = $this->resolveEncryption();
        $this->mail->Timeout = 15;
        $this->mail->SMTPKeepAlive = false;

        if ($this->mail->SMTPSecure === false) {
            // Sem criptografia (ex.: localhost:25). Desliga o STARTTLS automático do
            // PHPMailer 6, que falharia validando o certificado contra "localhost".
            $this->mail->SMTPAutoTLS = false;
        }

        $this->mail->setFrom(self::Email, NAME);
        $this->mail->isHTML(true);
    }

    private function resolveEncryption()
    {
        $encryption = defined('MAIL_ENCRYPTION') ? strtolower((string) MAIL_ENCRYPTION) : 'tls';

        if ($encryption === 'ssl' || $encryption === 'smtps') {
            return PHPMailer::ENCRYPTION_SMTPS;
        }

        if ($encryption === 'none' || $encryption === '') {
            return false;
        }

        return PHPMailer::ENCRYPTION_STARTTLS;
    }

    public function addAddress(string $email): void
    {
        $this->mail->addAddress($email);
    }

    public function setSubject($subject): void
    {
        $this->mail->Subject = $subject;
    }

    public function setBody(string $file, array $data = []): void
    {
        $template = new TemplateAdapter();
        $mail = $template->render($file, $data);
        $this->mail->Body = $mail;
        $altBody = strip_tags($mail);
        $altBody = html_entity_decode($altBody, ENT_QUOTES, 'UTF-8');
        $this->mail->AltBody = $altBody;
    }

    public function addAttachment(string $path, string $name = ''): void
    {
        $this->mail->addAttachment($path, $name);
    }

    public function send($result): void
    {
        try {
            $this->mail->send();
            $this->result->setError(false);
            $this->result->setMessage($result);
        } catch (Exception $e) {
            $this->result->setError(true);
            $this->result->setMessage('Não foi possível enviar o e-mail. Tente novamente em instantes.');
            $this->result->setInfo([
                'message' => "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}",
            ]);
        }
    }

    public function getResult(): Result
    {
        return $this->result;
    }
}
