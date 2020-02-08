<?php

namespace Ekyna\Bundle\MailchimpBundle\Table\Type;

use Ekyna\Bundle\AdminBundle\Table\Type\ResourceTableType;
use Ekyna\Bundle\MailchimpBundle\Model\MemberStatuses;
use Ekyna\Bundle\ResourceBundle\Table\Column\ConstantChoiceType;
use Ekyna\Bundle\TableBundle\Extension\Type as BType;
use Ekyna\Component\Table\TableBuilderInterface;

/**
 * Class MemberType
 * @package Ekyna\Bundle\MailchimpBundle\Table\Type
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class MemberType extends ResourceTableType
{
    /**
     * @inheritDoc
     */
    public function buildTable(TableBuilderInterface $builder, array $options)
    {
        $builder
            ->addColumn('emailAddress', BType\Column\AnchorType::class, [
                'label'                => 'ekyna_core.field.email_address',
                'route_name'           => 'ekyna_mailchimp_member_admin_show',
                'route_parameters_map' => [
                    'memberId' => 'id',
                ],
            ])
            ->addColumn('status', ConstantChoiceType::class, [
                'label' => 'ekyna_core.field.status',
                'class' => MemberStatuses::class,
                'theme' => true,
            ])
            ->addColumn('actions', BType\Column\ActionsType::class, [
                'buttons' => [
                    [
                        'label'                => 'ekyna_core.button.edit',
                        'icon'                 => 'pencil',
                        'class'                => 'warning',
                        'route_name'           => 'ekyna_mailchimp_member_admin_edit',
                        'route_parameters_map' => [
                            'memberId' => 'id',
                        ],
                        'permission'           => 'edit',
                    ],
                    [
                        'label'                => 'ekyna_core.button.remove',
                        'icon'                 => 'trash',
                        'class'                => 'danger',
                        'route_name'           => 'ekyna_mailchimp_member_admin_remove',
                        'route_parameters_map' => [
                            'memberId' => 'id',
                        ],
                        'permission'           => 'delete',
                    ],
                ],
            ]);
    }
}
