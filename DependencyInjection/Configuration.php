<?php

namespace DoS\MailerBundle\DependencyInjection;

use DoS\ResourceBundle\DependencyInjection\AbstractResourceConfiguration;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration extends AbstractResourceConfiguration
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('dos_mailer');

        $this->addDefaults($rootNode, 'doctrine/orm');

        $rootNode
            ->children()
                ->scalarNode('sender_adapter')->defaultValue('sylius.email_sender.adapter.swiftmailer')->end()
                ->scalarNode('renderer_adapter')->defaultValue('dos.mailer.renderer_adapter')->end()
            ->end()
        ;

        $this->setDefaults($rootNode, array(
            'classes' => array(
                'email' => array(
                    'model' => 'DoS\MailerBundle\Model\Email',
                    'form' => array(
                        'default' => 'DoS\MailerBundle\Form\Type\EmailType',
                    ),
                ),
            ),
        ));

        $this->addEmailsSection($rootNode);

        return $treeBuilder;
    }

    /**
     * @param ArrayNodeDefinition $node
     *
     * @return ArrayNodeDefinition
     */
    protected function addEmailsSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('sender')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('name')->defaultValue('%dos.mailer.sender.name%')->end()
                        ->scalarNode('address')->defaultValue('%dos.mailer.sender.address%')->end()
                    ->end()
                ->end()
                ->arrayNode('emails')
                    ->useAttributeAsKey('code')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('subject')->cannotBeEmpty()->end()
                            ->scalarNode('template')->cannotBeEmpty()->end()
                            ->booleanNode('enabled')->defaultTrue()->end()
                            ->arrayNode('sender')
                                ->children()
                                    ->scalarNode('name')->end()
                                    ->scalarNode('address')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('templates')
                    ->prototype('scalar')->end()
                ->end()
            ->end()
        ;
    }
}
