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
        $builder
            ->add('code', 'text', array(
                'label' => 'sylius.form.email.code',
            ))
            ->add('enabled', 'checkbox', array(
                'required' => false,
                'label'    => 'sylius.form.email.enabled',
            ))
            ->add('senderName', 'text', array(
                'required' => false,
                'label' => 'sylius.form.email.sender_name',
            ))
            ->add('senderAddress', 'email', array(
                'required' => false,
                'label' => 'sylius.form.email.sender_address',
            ))
            ->add('subject', 'text', array(
                'label' => 'sylius.form.email.subject',
            ))
            ->add('content', 'textarea', array(
                'required' => false,
                'label' => 'sylius.form.email.content',
            ))
            // FIXME: for now we have bug (SF2.7) with empty choice validate!
//            ->add('template', 'sylius_email_template_choice', array(
//                'label'       => 'sylius.form.email.template',
//                'required'    => false,
//                'empty_value' => 'sylius.form.email.no_template',
//            ))
        ;
    }
}
