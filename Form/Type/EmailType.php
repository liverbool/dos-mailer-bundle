<?php

namespace DoS\MailerBundle\Form\Type;

use Sylius\Bundle\MailerBundle\Form\Type\EmailType as BaseEmailType;
use Symfony\Component\Form\FormBuilderInterface;

class EmailType extends BaseEmailType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        // FIXME: for now we have bug (SF2.7) with empty choice validate!
        $builder->remove('template');
    }
}
