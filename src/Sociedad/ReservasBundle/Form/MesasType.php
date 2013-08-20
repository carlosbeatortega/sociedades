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
            ->add('nombre',null,array('label' => 'nombre'))
            ->add('planta',null,array('label' => 'planta'))
            ->add('sala',null,array('label' => 'sala'))
            ->add('comensales',null,array('label' => 'comensales'))
            ->add('foto', 'file', array('label' => 'foto','required'=>false))
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
