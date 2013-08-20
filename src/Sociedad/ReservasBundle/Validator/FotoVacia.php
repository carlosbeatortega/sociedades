<?php
namespace Sociedad\ReservasBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class FotoVacia extends Constraint
{
    public $message = 'El valor no puede ser Vacío';
}