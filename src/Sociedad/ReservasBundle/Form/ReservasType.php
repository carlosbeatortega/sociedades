<?php

namespace Sociedad\ReservasBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReservasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sociedades_id', 'hidden')
            ->add('socios_id', 'hidden')
            ->add('fechadesde',null,array('format' => 'dd-MM-yyyy','label'=>'Fecha','read_only'=> true,'widget' => 'single_text'))
            ->add('comida',null,array('label'=>'Turno','read_only'=> true))
            ->add('comensales')
            ->add('calendarid', 'hidden')
            ->add('calendario', 'hidden')
            ->add('socio',null,array('read_only'=> true))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sociedad\ReservasBundle\Entity\Reservas'
        ));
    }

    public function getName()
    {
        return 'sociedad_reservasbundle_reservastype';
    }
}
