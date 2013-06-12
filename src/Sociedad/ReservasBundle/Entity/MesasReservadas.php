<?php

namespace Sociedad\ReservasBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Sociedad\ReservasBundle\Entity\MesasPlantas;
use Sociedad\ReservasBundle\Entity\Reservas;
use Doctrine\ORM\Mapping as ORM;

/**
 * Sociedad\ReservasBundle\Entity\MesasReservadas
 *
 * @ORM\Table(name="mesasreservadas")
 * @ORM\Entity(repositoryClass="Sociedad\ReservasBundle\Entity\MesasReservadasRepository")
 */
class MesasReservadas
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
     * @var integer $reservas_id
     *
     * @ORM\Column(name="reservas_id", type="integer", nullable=false)
     */
    protected $reservas_id;

    /**
     * @var integer $mesasplantas_id
     *
     * @ORM\Column(name="mesasplantas_id", type="integer", nullable=false)
     */
    protected $mesasplantas_id;

    /**
     * @ORM\ManyToOne(targetEntity="Sociedad\ReservasBundle\Entity\Reservas", inversedBy="mesasreservadas")
     * @ORM\JoinColumn(name="reservas_id", referencedColumnName="id")
     */
    protected $reserva;    

     /**
     * @ORM\ManyToOne(targetEntity="Sociedad\ReservasBundle\Entity\MesasPlantas", inversedBy="mesareservadas")
     * @ORM\JoinColumn(name="mesasplantas_id", referencedColumnName="id")
     */
    protected $mesaplanta;    
    

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
     * Set reservas_id
     *
     * @param integer $reservasId
     * @return MesasReservadas
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
     * Set mesas_id
     *
     * @param integer $mesasId
     * @return MesasReservadas
     */
    public function setMesasId($mesasId)
    {
        $this->mesas_id = $mesasId;
    
        return $this;
    }

    /**
     * Get mesas_id
     *
     * @return integer 
     */
    public function getMesasId()
    {
        return $this->mesas_id;
    }

    /**
     * Set reserva
     *
     * @param \Sociedad\ReservasBundle\Entity\Reservas $reserva
     * @return MesasReservadas
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
     * Set sociedades_id
     *
     * @param integer $sociedadesId
     * @return MesasReservadas
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
     * Set mesasplantas_id
     *
     * @param integer $mesasplantasId
     * @return MesasReservadas
     */
    public function setMesasplantasId($mesasplantasId)
    {
        $this->mesasplantas_id = $mesasplantasId;
    
        return $this;
    }

    /**
     * Get mesasplantas_id
     *
     * @return integer 
     */
    public function getMesasplantasId()
    {
        return $this->mesasplantas_id;
    }

    /**
     * Set mesaplanta
     *
     * @param \Sociedad\ReservasBundle\Entity\MesasPlantas $mesaplanta
     * @return MesasReservadas
     */
    public function setMesaplanta(\Sociedad\ReservasBundle\Entity\MesasPlantas $mesaplanta = null)
    {
        $this->mesaplanta = $mesaplanta;
    
        return $this;
    }

    /**
     * Get mesaplanta
     *
     * @return \Sociedad\ReservasBundle\Entity\MesasPlantas 
     */
    public function getMesaplanta()
    {
        return $this->mesaplanta;
    }
}