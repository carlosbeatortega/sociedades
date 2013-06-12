<?php

namespace Sociedad\SociosBundle\Entity;
use Sociedad\SociosBundle\Entity\Socios;
use Doctrine\ORM\Mapping as ORM;

/**
 * Sociedad\SociosBundle\Entity\Contactos
 *
 * @ORM\Table(name="contactos")
 * @ORM\Entity(repositoryClass="Sociedad\SociosBundle\Entity\ContactosRepository")
 */

class Contactos {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var integer $sociedadesId
     *
     * @ORM\Column(name="sociedades_id", type="integer", nullable=false)
     */
    protected $sociedades_id;

    /**
     * @var integer $sociosId
     *
     * @ORM\Column(name="socios_id", type="integer", nullable=false)
     */
    protected $socios_id;

    /**
     * @var string $email
     * @ORM\Column(name="email", type="string", length=255)
     */
    protected $email;

    /**
     * @var string $nombre
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    protected $nombre;
    
    /**
     * @var string $fijo
     * @ORM\Column(name="fijo", type="string", length=9)
     */
    protected $fijo;
    
    /**
     * @var string $movil
     * @ORM\Column(name="movil", type="string", length=9)
     */
    protected $movil;

    /** 
     *  @ORM\ManyToOne(targetEntity="Socios")
     *  @ORM\JoinColumn(name="socios_id", referencedColumnName="id")
     */
    protected $socios;
    

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
     * @return Contactos
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
     * @return Contactos
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
     * Set email
     *
     * @param string $email
     * @return Contactos
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Contactos
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    
        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set fijo
     *
     * @param string $fijo
     * @return Contactos
     */
    public function setFijo($fijo)
    {
        $this->fijo = $fijo;
    
        return $this;
    }

    /**
     * Get fijo
     *
     * @return string 
     */
    public function getFijo()
    {
        return $this->fijo;
    }

    /**
     * Set movil
     *
     * @param string $movil
     * @return Contactos
     */
    public function setMovil($movil)
    {
        $this->movil = $movil;
    
        return $this;
    }

    /**
     * Get movil
     *
     * @return string 
     */
    public function getMovil()
    {
        return $this->movil;
    }

    /**
     * Set socios
     *
     * @param Sociedad\SociosBundle\Entity\Socios $socios
     * @return Contactos
     */
    public function setSocios(\Sociedad\SociosBundle\Entity\Socios $socios = null)
    {
        $this->socios = $socios;
    
        return $this;
    }

    /**
     * Get socios
     *
     * @return Sociedad\SociosBundle\Entity\Socios 
     */
    public function getSocios()
    {
        return $this->socios;
    }
}