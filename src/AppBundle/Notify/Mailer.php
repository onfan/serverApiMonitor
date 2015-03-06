<?php

namespace Onfan\MailerBundle\Mailer;


/**
 * Send emails rendered from templates.
 */
class Mailer
{
    protected $mailer;
    protected $templating;

    public function __construct($mailer, $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    public function send($subject, $template, $parameters = array())
    {

        $body = $this->templating->render($template, $parameters);

        $message = \Swift_Message::newInstance()
            ->setFrom(array('info@onfan.com' => 'Onfan'))
            ->setSender(array('info@onfan.com' => 'Onfan'))
            ->setReplyTo(array('info@onfan.com' => 'Onfan'))
            ->setSubject($subject)
            ->setBody($body)
            ->setContentType('text/html');

        $message->setTo(array('joan@laiogurtera.com' => 'joanteixi'));

        $this->mailer->send($message);
    }


}
