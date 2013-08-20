<?php

namespace Sociedad\ReservasBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PlantasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('planta',null,array('label' => 'planta'))
            ->add('foto', 'file', array('label' => 'foto', 'required'  => false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sociedad\ReservasBundle\Entity\Plantas'
        ));
    }

    public function getName()
    {
        return 'sociedad_reservasbundle_plantastype';
    }
}
