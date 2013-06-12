<?php

namespace Sociedad\ReservasBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Sociedad\ReservasBundle\Entity\Plantas
 *
 * @ORM\Table(name="plantas")
 * @ORM\Table(name="Plantas",indexes={@ORM\index(name="customer_idx", columns={"sociedades_id"})})
 * @ORM\Entity(repositoryClass="Sociedad\ReservasBundle\Entity\PlantasRepository")
 */
class Plantas
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
     * @var string $planta
     *
     * @ORM\Column(name="planta", type="string", length=255)
     */
    protected $planta;

    /**
     * @var string $foto
     *
     * @ORM\Column(name="foto", type="string", length=255)
     */
    protected $foto;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->mesas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Plantas
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
     * Set planta
     *
     * @param string $planta
     * @return Plantas
     */
    public function setPlanta($planta)
    {
        $this->planta = $planta;
    
        return $this;
    }

    /**
     * Get planta
     *
     * @return string 
     */
    public function getPlanta()
    {
        return $this->planta;
    }

    /**
     * Set foto
     *
     * @param string $foto
     * @return Plantas
     */
    public function setFoto($foto)
    {
        $this->foto = $foto;
    
        return $this;
    }

    /**
     * Get foto
     *
     * @return string 
     */
    public function getFoto()
    {
        return $this->foto;
    }

    public function subirFoto($directorioDestino)
    {
        if (null === $this->foto) {
        return;
        }
        $nombreArchivoFoto = uniqid('plantas-').'-foto1.jpg';
        $this->foto->move($directorioDestino, $nombreArchivoFoto);
        $directorioDestino= substr($directorioDestino, strpos($directorioDestino, 'web')+3); //'/bundles/sociedad/uploads/images/';
        $this->setFoto($directorioDestino.$nombreArchivoFoto);
    }    
}