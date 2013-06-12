<?php

namespace Sociedad\SociosBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        // add your custom field
        $builder->add('name');
        $builder->add('foto', 'file', array());
        $builder->add('dni');
        $builder->add('fechanacimiento', 'date', array(
                'widget' => 'choice',    ));
        $builder->add('numero_cuenta');
       
    }

    public function getName()
    {
        return 'sociedad_socios_registration';
    }
}
?>
