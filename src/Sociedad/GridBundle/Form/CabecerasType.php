<?php

namespace Sociedad\GridBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CabecerasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('usuarios_id')
            ->add('nombre')
            ->add('id_usuarios')
            ->add('campo')
            ->add('orden')
            ->add('titulo')
            ->add('condicion')
            ->add('usuarios')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sociedad\GridBundle\Entity\Cabeceras'
        ));
    }

    public function getName()
    {
        return 'sociedad_gridbundle_cabecerastype';
    }
}
