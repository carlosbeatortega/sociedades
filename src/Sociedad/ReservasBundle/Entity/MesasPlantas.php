<?php

namespace Sociedad\ReservasBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Sociedad\ReservasBundle\Entity\MesasReservadas;
use Doctrine\ORM\Mapping as ORM;

/**
 * Sociedad\ReservasBundle\Entity\MesasPlantas
 *
 * @ORM\Table(name="mesasplantas")
 * @ORM\Table(name="mesasplantas",indexes={@ORM\index(name="customer_idx", columns={"sociedades_id"})})
 * @ORM\Entity(repositoryClass="Sociedad\ReservasBundle\Entity\MesasPlantasRepository")
 */
class MesasPlantas
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
     * @var integer $plantas_id
     *
     * @ORM\Column(name="plantas_id", type="integer", nullable=false)
     */
    protected $plantas_id;

    /**
     * @var integer $mesas_id
     *
     * @ORM\Column(name="mesas_id", type="integer", nullable=false)
     */
    protected $mesas_id;
    /**
     * @var integer $posx
     *
     * @ORM\Column(name="posx", type="integer", length=6)
     */
    protected $posx;
    /**
     * @var integer $posy
     *
     * @ORM\Column(name="posy", type="integer", length=6)
     */
    protected $posy;

    /**
     * @ORM\OneToMany(targetEntity="Sociedad\ReservasBundle\Entity\MesasReservadas", mappedBy="MesasPlantas")
     */
    private $mesareservadas;

    /**
     * @ORM\ManyToOne(targetEntity="Sociedad\ReservasBundle\Entity\Plantas", inversedBy="mesasplantas")
     * @ORM\JoinColumn(name="plantas_id", referencedColumnName="id")
     */
    protected $plantas;    

    /**
     * @ORM\ManyToOne(targetEntity="Sociedad\ReservasBundle\Entity\Mesas", inversedBy="mesasplantas")
     * @ORM\JoinColumn(name="mesas_id", referencedColumnName="id")
     */
    protected $mesas;    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->mesareservadas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return MesasPlantas
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
     * Set plantas_id
     *
     * @param integer $plantasId
     * @return MesasPlantas
     */
    public function setPlantasId($plantasId)
    {
        $this->plantas_id = $plantasId;
    
        return $this;
    }

    /**
     * Get plantas_id
     *
     * @return integer 
     */
    public function getPlantasId()
    {
        return $this->plantas_id;
    }

    /**
     * Set mesas_id
     *
     * @param integer $mesasId
     * @return MesasPlantas
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
     * Set posx
     *
     * @param integer $posx
     * @return MesasPlantas
     */
    public function setPosx($posx)
    {
        $this->posx = $posx;
    
        return $this;
    }

    /**
     * Get posx
     *
     * @return integer 
     */
    public function getPosx()
    {
        return $this->posx;
    }

    /**
     * Set posy
     *
     * @param integer $posy
     * @return MesasPlantas
     */
    public function setPosy($posy)
    {
        $this->posy = $posy;
    
        return $this;
    }

    /**
     * Get posy
     *
     * @return integer 
     */
    public function getPosy()
    {
        return $this->posy;
    }

    /**
     * Add mesareservadas
     *
     * @param \Sociedad\ReservasBundle\Entity\MesasReservadas $mesareservadas
     * @return MesasPlantas
     */
    public function addMesareservada(\Sociedad\ReservasBundle\Entity\MesasReservadas $mesareservadas)
    {
        $this->mesareservadas[] = $mesareservadas;
    
        return $this;
    }

    /**
     * Remove mesareservadas
     *
     * @param \Sociedad\ReservasBundle\Entity\MesasReservadas $mesareservadas
     */
    public function removeMesareservada(\Sociedad\ReservasBundle\Entity\MesasReservadas $mesareservadas)
    {
        $this->mesareservadas->removeElement($mesareservadas);
    }

    /**
     * Get mesareservadas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMesareservadas()
    {
        return $this->mesareservadas;
    }

    /**
     * Set plantas
     *
     * @param \Sociedad\ReservasBundle\Entity\Plantas $plantas
     * @return MesasPlantas
     */
    public function setPlantas(\Sociedad\ReservasBundle\Entity\Plantas $plantas = null)
    {
        $this->plantas = $plantas;
    
        return $this;
    }

    /**
     * Get plantas
     *
     * @return \Sociedad\ReservasBundle\Entity\Plantas 
     */
    public function getPlantas()
    {
        return $this->plantas;
    }

    /**
     * Set mesas
     *
     * @param \Sociedad\ReservasBundle\Entity\Mesas $mesas
     * @return MesasPlantas
     */
    public function setMesas(\Sociedad\ReservasBundle\Entity\Mesas $mesas = null)
    {
        $this->mesas = $mesas;
    
        return $this;
    }

    /**
     * Get mesas
     *
     * @return \Sociedad\ReservasBundle\Entity\Mesas 
     */
    public function getMesas()
    {
        return $this->mesas;
    }
}