<?php

namespace Nozyczki\ShortenerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AliasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('alias', 'text', array(
                'label' => false,
                'required'  => false,
                'attr'  => array(
                    'placeholder' => 'alias',
                )
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Nozyczki\ShortenerBundle\Document\Alias',
        ));
    }

    public function getName()
    {
        return 'alias';
    }
}