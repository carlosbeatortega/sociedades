<?php

namespace Sociedad\ReservasBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Sociedad\SociosBundle\Entity\Socios;
use Sociedad\ReservasBundle\Entity\MesasReservadas;
use Sociedad\ReservasBundle\Entity\Invitados;
use Doctrine\ORM\Mapping as ORM;

/**
 * Sociedad\ReservasBundle\Entity\Reservas
 *
 * @ORM\Table(name="reservas")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="reservas",indexes={@ORM\index(name="sociedades_idx", columns={"sociedades_id"}),@ORM\index(name="calendario_idx", columns={"calendarid"})})
 * @ORM\Entity(repositoryClass="Sociedad\ReservasBundle\Entity\ReservasRepository")
 */
class Reservas
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
     * @var string $fechadesde
     *
     * @ORM\Column(name="fechadesde", type="date", nullable=false)
     */
    protected $fechadesde;
    /**
     * @var string $fechahasta
     *
     * @ORM\Column(name="fechahasta", type="date", nullable=false)
     */
    protected $fechahasta;
    /**
     * @var string $fechamodi
     *
     * @ORM\Column(name="fechamodi", type="datetime", nullable=false)
     */
    protected $fechamodi;

    /**
     * @var string $comida
     *
     * @ORM\Column(name="comida", type="string", length=20, nullable=false)
     */
    protected $comida;
    
    /**
     * @var integer $comensales
     *
     * @ORM\Column(name="comensales", type="integer", length=3)
     */
    protected $comensales;

    /**
     * @var string $calendarid
     *
     * @ORM\Column(name="calendarid", type="string", length=50, nullable=true)
     */
    protected $calendarid;

    /**
     * @var string $calendario
     *
     * @ORM\Column(name="calendario", type="string", length=50, nullable=false)
     */
    protected $calendario;
     /**
     * @ORM\ManyToOne(targetEntity="Sociedad\SociosBundle\Entity\Socios", inversedBy="reservas")
     * @ORM\JoinColumn(name="socios_id", referencedColumnName="id")
     */
    protected $socio;    
    
    /**
     * @ORM\OneToMany(targetEntity="Sociedad\ReservasBundle\Entity\MesasReservadas", mappedBy="reserva", cascade={"remove"})
     */
    private $mesasreservadas;

    /**
     * @ORM\OneToMany(targetEntity="Sociedad\ReservasBundle\Entity\Invitados", mappedBy="reserva", cascade={"remove"})
     */
    private $invitados;
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->mesasreservadas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fechamodi= new \DateTime();
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
     * Set socios_id
     *
     * @param integer $sociosId
     * @return Reservas
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
     * Set fechadesde
     *
     * @param \DateTime $fechadesde
     * @return Reservas
     */
    public function setFechadesde($fechadesde)
    {
        $this->fechadesde = $fechadesde;
    
        return $this;
    }

    /**
     * Get fechadesde
     *
     * @return \DateTime 
     */
    public function getFechadesde()
    {
        return $this->fechadesde;
    }

    /**
     * Set fechahasta
     *
     * @param \DateTime $fechahasta
     * @return Reservas
     */
    public function setFechahasta($fechahasta)
    {
        $this->fechahasta = $fechahasta;
    
        return $this;
    }

    /**
     * Get fechahasta
     *
     * @return \DateTime 
     */
    public function getFechahasta()
    {
        return $this->fechahasta;
    }

    /**
     * Set comida
     *
     * @param string $comida
     * @return Reservas
     */
    public function setComida($comida)
    {
        $this->comida = $comida;
    
        return $this;
    }

    /**
     * Get comida
     *
     * @return string 
     */
    public function getComida()
    {
        return $this->comida;
    }

    /**
     * Set comensales
     *
     * @param integer $comensales
     * @return Reservas
     */
    public function setComensales($comensales)
    {
        $this->comensales = $comensales;
    
        return $this;
    }

    /**
     * Get comensales
     *
     * @return integer 
     */
    public function getComensales()
    {
        return $this->comensales;
    }

    /**
     * Set calendarid
     *
     * @param string $calendarid
     * @return Reservas
     */
    public function setCalendarid($calendarid)
    {
        $this->calendarid = $calendarid;
    
        return $this;
    }

    /**
     * Get calendarid
     *
     * @return string 
     */
    public function getCalendarid()
    {
        return $this->calendarid;
    }

    /**
     * Set calendario
     *
     * @param string $calendario
     * @return Reservas
     */
    public function setCalendario($calendario)
    {
        $this->calendario = $calendario;
    
        return $this;
    }

    /**
     * Get calendario
     *
     * @return string 
     */
    public function getCalendario()
    {
        return $this->calendario;
    }

    /**
     * Set socio
     *
     * @param \Sociedad\SociosBundle\Entity\Socios $socio
     * @return Reservas
     */
    public function setSocio(\Sociedad\SociosBundle\Entity\Socios $socio = null)
    {
        $this->socio = $socio;
    
        return $this;
    }

    /**
     * Get socio
     *
     * @return \Sociedad\SociosBundle\Entity\Socios 
     */
    public function getSocio()
    {
        return $this->socio;
    }

    /**
     * Add mesasreservadas
     *
     * @param \Sociedad\ReservasBundle\Entity\MesasReservadas $mesasreservadas
     * @return Reservas
     */
    public function addMesasreservada(\Sociedad\ReservasBundle\Entity\MesasReservadas $mesasreservadas)
    {
        $this->mesasreservadas[] = $mesasreservadas;
    
        return $this;
    }

    /**
     * Remove mesasreservadas
     *
     * @param \Sociedad\ReservasBundle\Entity\MesasReservadas $mesasreservadas
     */
    public function removeMesasreservada(\Sociedad\ReservasBundle\Entity\MesasReservadas $mesasreservadas)
    {
        $this->mesasreservadas->removeElement($mesasreservadas);
    }

    /**
     * Get mesasreservadas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMesasreservadas()
    {
        return $this->mesasreservadas;
    }

    /**
     * Set sociedades_id
     *
     * @param integer $sociedadesId
     * @return Reservas
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
     * Add invitados
     *
     * @param \Sociedad\ReservasBundle\Entity\Invitados $invitados
     * @return Reservas
     */
    public function addInvitado(\Sociedad\ReservasBundle\Entity\Invitados $invitados)
    {
        $this->invitados[] = $invitados;
    
        return $this;
    }

    /**
     * Remove invitados
     *
     * @param \Sociedad\ReservasBundle\Entity\Invitados $invitados
     */
    public function removeInvitado(\Sociedad\ReservasBundle\Entity\Invitados $invitados)
    {
        $this->invitados->removeElement($invitados);
    }

    /**
     * Get invitados
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInvitados()
    {
        return $this->invitados;
    }
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setFechamodiValue()
    {
        date_default_timezone_set('Europe/Madrid');     
        $this->fechamodi= new \DateTime(date(DATE_ATOM, strtotime(date('Y-m-d H:i:s'))));
    }    

    /**
     * Set fechamodi
     *
     * @param \DateTime $fechamodi
     * @return Reservas
     */
    public function setFechamodi($fechamodi)
    {
        $this->fechamodi = $fechamodi;
    
        return $this;
    }

    /**
     * Get fechamodi
     *
     * @return \DateTime 
     */
    public function getFechamodi()
    {
        return $this->fechamodi;
    }
}