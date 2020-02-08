<?php

namespace Ekyna\Bundle\MailchimpBundle\Form\Type;

use Ekyna\Bundle\AdminBundle\Form\Type\ResourceFormType;
use Ekyna\Bundle\AdminBundle\Form\Type\ResourceType;
use Ekyna\Bundle\MailchimpBundle\Entity\Audience;
use Ekyna\Bundle\MailchimpBundle\Model\MemberStatuses;
use Ekyna\Bundle\ResourceBundle\Form\Type\ConstantChoiceType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class MemberType
 * @package Ekyna\Bundle\MailchimpBundle\Form\Type
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class MemberType extends ResourceFormType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('audience', ResourceType::class, [
                'class' => Audience::class,
            ])
            ->add('emailAddress', Type\TextType::class, [
                'label'    => 'ekyna_core.field.email_address',
            ])
            ->add('status', ConstantChoiceType::class, [
                'class' => MemberStatuses::class,
            ]);
    }
}
