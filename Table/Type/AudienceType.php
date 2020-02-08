<?php

namespace Ekyna\Bundle\MailchimpBundle\Table\Type;

use Ekyna\Bundle\AdminBundle\Table\Type\ResourceTableType;
use Ekyna\Bundle\TableBundle\Extension\Type as BType;
use Ekyna\Component\Table\TableBuilderInterface;

/**
 * Class AudienceType
 * @package Ekyna\Bundle\MailchimpBundle\Table\Type
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class AudienceType extends ResourceTableType
{
    /**
     * @inheritDoc
     */
    public function buildTable(TableBuilderInterface $builder, array $options)
    {
        $builder
            ->addColumn('name', BType\Column\AnchorType::class, [
                'label'                => 'ekyna_core.field.name',
                'route_name'           => 'ekyna_mailchimp_audience_admin_show',
                'route_parameters_map' => [
                    'audienceId' => 'id',
                ],
            ])
            ->addColumn('actions', BType\Column\ActionsType::class, [
                'buttons' => [
                    [
                        'label'                => 'ekyna_core.button.edit',
                        'icon'                 => 'pencil',
                        'class'                => 'warning',
                        'route_name'           => 'ekyna_mailchimp_audience_admin_edit',
                        'route_parameters_map' => [
                            'audienceId' => 'id',
                        ],
                        'permission'           => 'edit',
                    ],
                    [
                        'label'                => 'ekyna_core.button.remove',
                        'icon'                 => 'trash',
                        'class'                => 'danger',
                        'route_name'           => 'ekyna_mailchimp_audience_admin_remove',
                        'route_parameters_map' => [
                            'audienceId' => 'id',
                        ],
                        'permission'           => 'delete',
                    ],
                ],
            ]);
    }
}
