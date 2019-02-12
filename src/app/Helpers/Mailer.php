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

use SlimFacades\App;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class Mailer
{
    protected $mailer;
    protected $test_mode;
    protected $test_email;
    protected $error;

    public function __construct()
    {
        $this->error = '';
        $settings = App::getContainer()->settings;
        $mail_settings = $settings['mailer'];

        try {
            $mailer = new PHPMailer(true);
            $mailer->setFrom($mail_settings['from']['address'], $mail_settings['from']['name']);
        } catch (Exception $e) {
            $this->error = 'Mailer Error: ' . $this->mailer->ErrorInfo;
        }

        $this->mailer = $mailer;
        $this->test_mode = $settings['test_mode'];
        $this->test_email = $mail_settings['test_email'];
        $this->template_path = $mail_settings['template_path'];
    }

    public function getError()
    {
        return $this->error !== ''? $this->error : false;
    }

    public function setRecipients($recipients, $ignoreTest = false)
    {
        // $recipients: string or array of recipients where recipient format is ['name' => 'john', 'email' =>'john@doe.com']
        if(!is_array($recipients) && is_string($recipients))
            $recipients = [['email' => $recipients, 'name' => $recipients]];
        if(is_array($recipients))
            if(!$this->test_mode || $ignoreTest)
                foreach($recipients as $recipient)
                    $this->mailer->addAddress($recipient['email'], $recipient['name']);
            else
                $this->setTestRecipients();
        else
            $this->error = 'Recipients Error: failed to set recipients';
    }

    private function setTestRecipients()
    {
        $this->mailer->addAddress($this->test_email, $this->test_email);
    }

    private function loadTemplate($path, $format, $vars)
    {
        // header
        $header_path = $this->template_path . DIRECTORY_SEPARATOR . 'header' . $format;
        $header = @file_get_contents($header_path);
        // footer
        $footer_path = $this->template_path . DIRECTORY_SEPARATOR . 'footer' . $format;
        $footer = @file_get_contents($footer_path);
        // template
        $template = @file_get_contents($path . $format);
        if(!$template) {
            $this->error = 'Mailer error: no template selected';
            $template = '';
        }
        $template = $header . $template . $footer;
        foreach($vars as $k => $v)
            $template = str_replace('{'.$k.'}', $v, $template);
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
        } catch (Exception $e) {
            $this->error = 'Mailer Error: ' . $this->mailer->ErrorInfo;
        }
    }

    public function send()
    {
        try {
            if($this->error === '')
                if(!$this->mailer->send())
                    $this->error = 'Mailer Error: Error while sending the email';
        } catch (Exception $e) {
            $this->error = 'Mailer Error: ' . $this->mailer->ErrorInfo;
        }
        return $this->error === '';
    }
}
