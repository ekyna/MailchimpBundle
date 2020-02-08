<?php

namespace Ekyna\Bundle\MailchimpBundle\Form\Type;

use Ekyna\Bundle\AdminBundle\Form\Type\ResourceFormType;
use Ekyna\Bundle\CommerceBundle\Form\Type\Customer\CustomerGroupChoiceType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class AudienceType
 * @package Ekyna\Bundle\MailchimpBundle\Form\Type
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class AudienceType extends ResourceFormType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', Type\TextType::class, [
                'label' => 'ekyna_core.field.name',
            ])
            ->add('groups', CustomerGroupChoiceType::class, [
                'multiple' => true,
                'required' => false,
            ]);
    }
}
