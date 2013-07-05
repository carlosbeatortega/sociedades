<?php

namespace Sociedad\SociosBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SociosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username',null,array('label'=>'Usuario'))
            ->add('usernameCanonical')
            ->add('email')
            ->add('emailCanonical')
            ->add('passwordCanonical','password')
            ->add('enabled',null,array('label' => 'Activo','required'=>false))
            ->add('salt', 'hidden')
            ->add('password', 'hidden')
            ->add('lastLogin',null,array('format' => 'dd-MM-yyyy HH:mm','read_only'=> true,'label'=>'Última Visita','widget' => 'single_text','required'=>false))
            ->add('locked',null,array('label' => 'Bloqueado','required'=>false))
            ->add('expired',null,array('label' => 'Expirado','required'=>false))
            ->add('expiresAt','hidden')
            ->add('confirmationToken', 'hidden')
            ->add('passwordRequestedAt', 'hidden')
            ->add('roles', 'choice', array(
            'label' => 'Roles',
            'choices' => array('ROLE_USER'=>'ROLE_USER','ROLE_ADMIN'=>'ROLE_ADMIN','ROLE_SUPER_ADMIN'=>'ROLE_SUPER_ADMIN'),'multiple'=>true))
            ->add('credentialsExpired','hidden')
            ->add('credentialsExpireAt','hidden')
            ->add('name',null,array('label' => 'Nombre'))
            ->add('sociedades_id', 'hidden')
            ->add('foto', 'file', array( 'required'  => false))
            ->add('dni')
            ->add('fechaalta',null,array('format' => 'dd-MM-yyyy HH:mm','read_only'=> true,'label'=>'Fecha de Alta','widget' => 'single_text'))
            ->add('fechanacimiento',null,array('format' => 'dd-MM-yyyy','label'=>'Fecha de Nacimiento','widget' => 'single_text'))
            ->add('numero_cuenta')
            ->add('sociedades');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sociedad\SociosBundle\Entity\Socios'
        ));
    }

    public function getName()
    {
        return 'sociedad_sociosbundle_sociostype';
    }
}
