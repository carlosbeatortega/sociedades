<?php

namespace Sociedad\ReservasBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MesasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('planta')
            ->add('sala')
            ->add('comensales')
            ->add('foto', 'file', array())
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sociedad\ReservasBundle\Entity\Mesas'
        ));
    }

    public function getName()
    {
        return 'sociedad_reservasbundle_mesastype';
    }
}
