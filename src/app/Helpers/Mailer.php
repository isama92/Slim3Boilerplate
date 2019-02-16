<?php
/*
|--------------------------------------------------------------------------
| Mailer Helper
|--------------------------------------------------------------------------
|
| Mailer helper class
|
*/

namespace App\Helpers;

use Slim\Settings;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class Mailer
{
    protected $mailer;
    protected $debug_mode;
    protected $test_email;
    protected $errors;
    protected $recipients_ok;
    protected $templates_ok;

    public function __construct(Settings $settings)
    {
        $mail_settings = $settings['mailer'];
        $this->errors = [];
        $this->recipients_ok = false;
        $this->templates_ok = false;

        $this->error = '';

        try {
            $mailer = new PHPMailer(true);
            $mailer->setFrom($mail_settings['from']['address'], $mail_settings['from']['name']);
        } catch (Exception $e) {
            $this->errors[] = 'Mailer Error: ' . $this->mailer->ErrorInfo;
        }

        $this->mailer = $mailer;
        $this->debug_mode = $settings['debug_mode'];
        $this->test_email = $mail_settings['test_email'];
        $this->template_path = $mail_settings['template_path'];
    }

    public function getErrors()
    {
        return !empty($this->errors)? $this->errors : false;
    }

    public function addRecipients($param_recipients = null, $ignoreDebug = false)
    {
        $recipients = [];

        if($this->debug_mode && !$ignoreDebug)
            $recipients = $this->getTestRecipients();
        else {
            if(!is_array($param_recipients) && is_string($param_recipients))
                $recipients = [['email' => $param_recipients, 'name' => $param_recipients]];
            elseif(is_array($param_recipients)) {
                if(isset($param_recipients['name'], $param_recipients['email']))
                    $recipients[] = $param_recipients;
                else
                    $recipients = $param_recipients;
            }
        }

        if(!empty($recipients)) {
            foreach($recipients as $recipient)
                $this->mailer->addAddress($recipient['email'], $recipient['name']);
            $this->recipients_ok = true;
        } else
            $this->errors[] = 'Recipients Error: failed to set recipients';
    }

    public function addCC($param_recipients = null, $ignoreDebug = false)
    {
        $recipients = [];

        if($this->debug_mode && !$ignoreDebug)
            $recipients = $this->getTestRecipients();
        else {
            if(!is_array($param_recipients) && is_string($param_recipients))
                $recipients = [['email' => $param_recipients, 'name' => $param_recipients]];
            elseif(is_array($param_recipients)) {
                if(isset($param_recipients['name'], $param_recipients['email']))
                    $recipients[] = $param_recipients;
                else
                    $recipients = $param_recipients;
            }
        }

        if(!empty($recipients)) {
            foreach($recipients as $recipient)
                $this->mailer->addCC($recipient['email'], $recipient['name']);
            $this->recipients_ok = true;
        } else
            $this->errors[] = 'Recipients Error: failed to set recipients';
    }

    private function getTestRecipients()
    {
        return [
            ['name' => $this->test_email, 'email' => $this->test_email],
        ];
    }

    private function loadTemplate($path, $format, $vars)
    {
        // header
        $header_path = $this->template_path . DIRECTORY_SEPARATOR . 'partials' . DIRECTORY_SEPARATOR . 'header' . $format;
        $header = @file_get_contents($header_path);
        // footer
        $footer_path = $this->template_path . DIRECTORY_SEPARATOR . 'partials' . DIRECTORY_SEPARATOR . 'footer' . $format;
        $footer = @file_get_contents($footer_path);
        // template
        $template = @file_get_contents($path . $format);
        if(!$template) {
            $this->errors[] = 'Mailer error: no template selected';
            $template = '';
        }
        $template = $header . $template . $footer;
        foreach($vars as $k => $v)
            $template = str_replace('{{'.$k.'}}', $v, $template);
        return $template;
    }

    public function setTemplate($subject, $templateName, $vars = [])
    {
        $template_path = $this->template_path . DIRECTORY_SEPARATOR . $templateName;
        $bodyHtml = $this->loadTemplate($template_path, '.html', $vars);
        $bodyTxt = $this->loadTemplate($template_path, '.txt', $vars);

        try {
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $bodyHtml;
            $this->mailer->AltBody = $bodyTxt;
            $this->templates_ok = true;
        } catch (Exception $e) {
            $this->errors[] = 'Mailer Error: ' . $this->mailer->ErrorInfo;
        }
    }

    public function send()
    {
        try {
            if(empty($this->errors) || !$this->recipients_ok || !$this->templates_ok)
                if($this->mailer->send()) {
                    $this->recipients_ok = false;
                    $this->templates_ok = false;
                    $this->errors = [];
                } else
                    $this->errors[] = 'Mailer Error: Error while sending the email';
        } catch (Exception $e) {
            $this->errors[] = 'Mailer Error: ' . $this->mailer->ErrorInfo;
        }
        return empty($this->errors);
    }
}
