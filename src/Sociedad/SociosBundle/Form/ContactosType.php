<?php

namespace Sociedad\SociosBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sociedades_id')
            ->add('socios_id')
            ->add('email')
            ->add('nombre')
            ->add('fijo')
            ->add('movil')
            ->add('socios')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sociedad\SociosBundle\Entity\Contactos'
        ));
    }

    public function getName()
    {
        return 'sociedad_sociosbundle_contactostype';
    }
}
