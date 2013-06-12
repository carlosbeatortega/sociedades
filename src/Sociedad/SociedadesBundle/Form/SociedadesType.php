<?php

namespace Sociedad\SociedadesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

// en foto podriase tener que añadir
//                'data_class' => null

class SociedadesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', 'text', array(
                'label' => 'Nombre',
                'attr' => array(
                    'class' => 'input-large',
                    'placeholder' => 'nombre de sociedad',)))
            ->add('direccion')
            ->add('telefono')
            ->add('poblacion')
            ->add('foto', 'file', array( 'required'  => false))
            ->add('fechaalta', 'date', array(
                'widget' => 'choice',    ))
            ->add('fechacreacion', 'date', array(
                'widget' => 'choice',    ))
            ->add('numero_cuenta')
            ->add('notas')
            ->add('email', 'text', array(
                'label' => 'Dirección Gmail',
                'attr' => array(
                    'class' => 'input-large',
                    'placeholder' => 'Cuenta Gmail',)))
            ->add('password')
            ->add('calendario')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sociedad\SociedadesBundle\Entity\Sociedades'
        ));
    }

    public function getName()
    {
        return 'registro_de_sociedades';
    }
}
