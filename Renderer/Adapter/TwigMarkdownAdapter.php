<?php

namespace DoS\MailerBundle\Renderer\Adapter;

use Knp\Bundle\MarkdownBundle\Helper\MarkdownHelper;
use Sylius\Bundle\MailerBundle\Renderer\Adapter\TwigAdapter;
use Sylius\Component\Mailer\Model\EmailInterface;

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

        parent::render($mail, $data);
    }
}
