<?php

namespace Webfactory\TestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * An example for a form type.
 */
class ContactType extends AbstractType
{
    /**
     * Defines the form structure.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', 'email');
        $builder->add('message', 'textarea');
    }

    /**
     * The name of the type. Must match the alias.
     *
     * @return string
     */
    public function getName()
    {
        return 'contact';
    }
}
