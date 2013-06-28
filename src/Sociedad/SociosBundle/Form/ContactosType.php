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
            ->add('sociedades_id', 'hidden')
            ->add('socios_id', 'hidden')
            ->add('email')
            ->add('nombre')
            ->add('fijo')
            ->add('movil')
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
        return 'registro_de_contactos';
    }
}
