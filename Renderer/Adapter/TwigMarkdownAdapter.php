<?php

namespace DoS\MailerBundle\Renderer\Adapter;

use Knp\Bundle\MarkdownBundle\Helper\MarkdownHelper;
use Sylius\Bundle\MailerBundle\Renderer\Adapter\TwigAdapter;
use Sylius\Component\Mailer\Event\EmailRenderEvent;
use Sylius\Component\Mailer\Model\EmailInterface;
use Sylius\Component\Mailer\Renderer\RenderedEmail;
use Sylius\Component\Mailer\SyliusMailerEvents;

/** @deprecated Use SyliusMailerEvents::EMAIL_PRE_RENDER event */
class TwigMarkdownAdapter extends TwigAdapter
{
    /**
     * @var MarkdownHelper
     */
    protected $markdownHelper;

    /**
     * @param MarkdownHelper $markdownHelper
     */
    public function setMarkdownHelper(MarkdownHelper $markdownHelper)
    {
        $this->markdownHelper = $markdownHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function render(EmailInterface $email, array $data = array())
    {
        $mail = $email;

        // lazy code ;) use only $email is readOnly
        if ($this->markdownHelper) {
            $mail = clone $email;
            $mail->setContent($this->markdownHelper->transform($mail->getContent()));
        }

        if (null !== $email->getTemplate()) {
            $data = $this->twig->mergeGlobals($data);

            /** @var \Twig_Template $template */
            $template = $this->twig->loadTemplate($email->getTemplate());

            $subject = $template->renderBlock('subject', $data);
            $body = $template->renderBlock('body', $data);
        } else {
            // Need evironment to parse twig functions, filters, etc ...
            $twig = $this->twig; //new \Twig_Environment(new \Twig_Loader_Array(array()));

            $subjectTemplate = $twig->createTemplate($email->getSubject());
            $bodyTemplate = $twig->createTemplate($email->getContent());

            $subject = $subjectTemplate->render($data);
            $body = $bodyTemplate->render($data);
        }

        /** @var EmailRenderEvent $event */
        $event = $this->dispatcher->dispatch(
            SyliusMailerEvents::EMAIL_PRE_RENDER,
            new EmailRenderEvent(new RenderedEmail($subject, $body))
        );

        return $event->getRenderedEmail();
    }
}
