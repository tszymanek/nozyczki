<?php

namespace Nozyczki\ShortenerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShortenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uri', 'text', array(
                'attr' => array(
                    'placeholder' => 'Url',
                )))
            ->add('aliases', 'collection', array(
                'type' => new AliasType(),
                'options'  => array(
                    'label'  => false),
                ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Nozyczki\ShortenerBundle\Document\Link',
        ));
    }

    public function getName()
    {
        return 'link';
    }
}
