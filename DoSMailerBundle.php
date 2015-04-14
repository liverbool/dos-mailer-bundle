<?php

namespace DoS\MailerBundle;

use DoS\ResourceBundle\DependencyInjection\AbstractResourceBundle;

class DoSMailerBundle extends AbstractResourceBundle
{
    /**
     * {@inheritdoc}
     */
    protected function getDependencyBundles()
    {
        return array(
            new \Sylius\Bundle\MailerBundle\SyliusMailerBundle(),
        );
    }
}
