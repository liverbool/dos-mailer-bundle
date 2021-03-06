<?php

namespace DoS\MailerBundle\DependencyInjection;

use DoS\ResourceBundle\DependencyInjection\AbstractResourceExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Reference;

class DoSMailerExtension extends AbstractResourceExtension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        parent::load($config, $container);

        $this->setupMailgun($container);
    }

    /**
     * {@inheritdoc}
     */
    protected function getBundleConfiguration()
    {
        return new Configuration();
    }

    /**
     * @inheritdoc
     */
    public function prepend(ContainerBuilder $container)
    {
        $configs = $container->getExtensionConfig($this->getAlias());
        // use the Configuration class to generate a config array with
        $config = $this->processConfiguration(new Configuration(), $configs);

        // no sylius config
        $container->prependExtensionConfig('sylius_mailer', $config);
    }

    /**
     * @param ContainerBuilder $container
     */
    private function setupMailgun(ContainerBuilder $container)
    {
        $definitionDecorator = new DefinitionDecorator('swiftmailer.transport.eventdispatcher.abstract');
        $container->setDefinition('dos.mailer.transportor.mailgun.eventdispatcher', $definitionDecorator);
        $container->getDefinition('dos.mailer.transportor.mailgun')
            ->replaceArgument(0, new Reference('dos.mailer.transportor.mailgun.eventdispatcher'))
        ;

        //set some alias
        $container->setAlias('mailgun', 'dos.mailer.transportor.mailgun');
        $container->setAlias('swiftmailer.mailer.transport.mailgun', 'dos.mailer.transportor.mailgun');
    }
}
