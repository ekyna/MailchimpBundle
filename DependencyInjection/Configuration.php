<?php

namespace Ekyna\Bundle\MailchimpBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package Ekyna\Bundle\MailchimpBundle\DependencyInjection
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ekyna_mailchimp');

        $this->addApiSection($rootNode);
        $this->addPoolsSection($rootNode);

        return $treeBuilder;
    }

    /**
     * Adds `api` sections.
     *
     * @param ArrayNodeDefinition $node
     */
    private function addApiSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('api')
                    ->children()
                        ->scalarNode('key')->isRequired()->cannotBeEmpty()->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * Adds admin pool sections.
     *
     * @param ArrayNodeDefinition $node
     */
    private function addPoolsSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('pools')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('audience')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('templates')->defaultValue('@EkynaMailchimp/Admin/Audience')->end()
                                ->scalarNode('entity')->defaultValue('Ekyna\Bundle\MailchimpBundle\Entity\Audience')->end()
                                ->scalarNode('controller')->defaultValue('Ekyna\Bundle\MailchimpBundle\Controller\Admin\AudienceController')->end()
                                ->scalarNode('operator')->end()
                                ->scalarNode('repository')->defaultValue('Ekyna\Bundle\MailchimpBundle\Repository\AudienceRepository')->end()
                                ->scalarNode('form')->defaultValue('Ekyna\Bundle\MailchimpBundle\Form\Type\AudienceType')->end()
                                ->scalarNode('table')->defaultValue('Ekyna\Bundle\MailchimpBundle\Table\Type\AudienceType')->end()
                                ->scalarNode('event')->end()
                                ->scalarNode('parent')->end()
                            ->end()
                        ->end()
                        ->arrayNode('member')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('templates')->defaultValue([
                                    '_form.html' => '@EkynaMailchimp/Admin/Member/_form.html',
                                    'show.html'  => '@EkynaMailchimp/Admin/Member/show.html',
                                ])->end()
                                ->scalarNode('entity')->defaultValue('Ekyna\Bundle\MailchimpBundle\Entity\Member')->end()
                                ->scalarNode('controller')->end()
                                ->scalarNode('operator')->end()
                                ->scalarNode('repository')->defaultValue('Ekyna\Bundle\MailchimpBundle\Repository\MemberRepository')->end()
                                ->scalarNode('form')->defaultValue('Ekyna\Bundle\MailchimpBundle\Form\Type\MemberType')->end()
                                ->scalarNode('table')->defaultValue('Ekyna\Bundle\MailchimpBundle\Table\Type\MemberType')->end()
                                ->scalarNode('event')->end()
                                ->scalarNode('parent')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
