<?php

namespace Sociedad\SociosBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SociedadSociosBundle extends Bundle
{
 public function getParent()
    {
        return 'FOSUserBundle';
    }
    
}
