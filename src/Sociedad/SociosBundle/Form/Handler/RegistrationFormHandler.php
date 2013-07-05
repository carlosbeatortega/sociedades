<?php

namespace Sociedad\SociosBundle\Form\Handler;

use FOS\UserBundle\Form\Handler\RegistrationFormHandler as BaseHandler;
use FOS\UserBundle\Model\UserInterface;

class RegistrationFormHandler extends BaseHandler
{
    protected $sociedades;
    protected $directorioImagenes;
    protected $imagenpordefecto;
    protected function onSuccess(UserInterface $user, $confirmation)
    {
        // Note: if you plan on modifying the user then do it before calling the
        // parent method as the parent method will flush the changes

        parent::onSuccess($user, $confirmation);

        // otherwise add your functionality here
    }
    
    public function process($confirmation = false)
    {
        $user = $this->userManager->createUser();
        $this->form->setData($user);

        if ('POST' == $this->request->getMethod()) {
            $this->form->bind($this->request);
            if ($this->form->isValid()) {

                $user->subirFoto($this->directorioImagenes,$this->imagenpordefecto);
                $user->addRole("ROLE_USER" );
                $user->setSociedades($this->sociedades);
                $user->setFechaalta(new \DateTime());
                $this->userManager->updateUser($user);

                return true;
            }
        }

        return false;
    }
    public function SetSociedades($sociedades){
        $this->sociedades=$sociedades;
    }
    public function SetDirImagenes($directorio){
        $this->directorioImagenes=$directorio;
    }
    public function SetImagenporDefecto($imagen){
        $this->imagenpordefecto=$imagen;
    }
}
?>
