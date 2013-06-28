<?php

namespace Sociedad\ReservasBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Sociedad\SociosBundle\Entity\Socios;
use Sociedad\ReservasBundle\Entity\Reservas;
use Doctrine\ORM\Mapping as ORM;

/**
 * Sociedad\ReservasBundle\Entity\Invitados
 *
 * @ORM\Table(name="invitados")
 * @ORM\Table(name="invitados",indexes={@ORM\index(name="customer_idx", columns={"sociedades_id"})})
 * @ORM\Entity(repositoryClass="Sociedad\ReservasBundle\Entity\InvitadosRepository")
 */
class Invitados
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var integer $sociedades_id
     *
     * @ORM\Column(name="sociedades_id", type="integer", nullable=false)
     */
    protected $sociedades_id;

    /**
     * @var integer $socios_id
     *
     * @ORM\Column(name="socios_id", type="integer", nullable=false)
     */
    protected $socios_id;

    /**
     * @var integer $contactos_id
     *
     * @ORM\Column(name="contactos_id", type="integer", nullable=false)
     */
    protected $contactos_id;

    /**
     * @var integer $reservas_id
     *
     * @ORM\Column(name="reservas_id", type="integer", nullable=false)
     */
    protected $reservas_id;

    /**
     * @var boolean $acepta
     *
     * @ORM\Column(name="acepta", type="boolean", nullable=false)
     */
    protected $acepta;

     /**
     * @ORM\ManyToOne(targetEntity="Sociedad\ReservasBundle\Entity\Reservas", inversedBy="invitados", cascade={"remove"})
     * @ORM\JoinColumn(name="reservas_id", referencedColumnName="id")
     */
    protected $reserva;    
    
     /**
     * @ORM\ManyToOne(targetEntity="Sociedad\SociosBundle\Entity\Contactos", inversedBy="invitados", cascade={"remove"})
     * @ORM\JoinColumn(name="contactos_id", referencedColumnName="id")
     */
    protected $contacto;    
    
    public function __construct()
    {
        $this->acepta = false;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set sociedades_id
     *
     * @param integer $sociedadesId
     * @return Invitados
     */
    public function setSociedadesId($sociedadesId)
    {
        $this->sociedades_id = $sociedadesId;
    
        return $this;
    }

    /**
     * Get sociedades_id
     *
     * @return integer 
     */
    public function getSociedadesId()
    {
        return $this->sociedades_id;
    }

    /**
     * Set socios_id
     *
     * @param integer $sociosId
     * @return Invitados
     */
    public function setSociosId($sociosId)
    {
        $this->socios_id = $sociosId;
    
        return $this;
    }

    /**
     * Get socios_id
     *
     * @return integer 
     */
    public function getSociosId()
    {
        return $this->socios_id;
    }

    /**
     * Set contactos_id
     *
     * @param integer $contactosId
     * @return Invitados
     */
    public function setContactosId($contactosId)
    {
        $this->contactos_id = $contactosId;
    
        return $this;
    }

    /**
     * Get contactos_id
     *
     * @return integer 
     */
    public function getContactosId()
    {
        return $this->contactos_id;
    }

    /**
     * Set reservas_id
     *
     * @param integer $reservasId
     * @return Invitados
     */
    public function setReservasId($reservasId)
    {
        $this->reservas_id = $reservasId;
    
        return $this;
    }

    /**
     * Get reservas_id
     *
     * @return integer 
     */
    public function getReservasId()
    {
        return $this->reservas_id;
    }

    /**
     * Set reserva
     *
     * @param \Sociedad\ReservasBundle\Entity\Reservas $reserva
     * @return Invitados
     */
    public function setReserva(\Sociedad\ReservasBundle\Entity\Reservas $reserva = null)
    {
        $this->reserva = $reserva;
    
        return $this;
    }

    /**
     * Get reserva
     *
     * @return \Sociedad\ReservasBundle\Entity\Reservas 
     */
    public function getReserva()
    {
        return $this->reserva;
    }

    /**
     * Set contacto
     *
     * @param \Sociedad\SociosBundle\Entity\Contactos $contacto
     * @return Invitados
     */
    public function setContacto(\Sociedad\SociosBundle\Entity\Contactos $contacto = null)
    {
        $this->contacto = $contacto;
    
        return $this;
    }

    /**
     * Get contacto
     *
     * @return \Sociedad\SociosBundle\Entity\Contactos 
     */
    public function getContacto()
    {
        return $this->contacto;
    }

    

    /**
     * Set acepta
     *
     * @param boolean $acepta
     * @return Invitados
     */
    public function setAcepta($acepta)
    {
        $this->acepta = $acepta;
    
        return $this;
    }

    /**
     * Get acepta
     *
     * @return boolean 
     */
    public function getAcepta()
    {
        return $this->acepta;
    }
}