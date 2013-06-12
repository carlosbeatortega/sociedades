<?php

namespace Sociedad\ReservasBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Sociedad\ReservasBundle\Entity\Mesas
 *
 * @ORM\Table(name="mesas")
 * @ORM\Table(name="mesas",indexes={@ORM\index(name="customer_idx", columns={"sociedades_id"})})
 * @ORM\Entity(repositoryClass="Sociedad\ReservasBundle\Entity\MesasRepository")
 */
class Mesas
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
     * @var string $nombre
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    protected $nombre;

    /**
     * @var string $planta
     *
     * @ORM\Column(name="planta", type="string", length=255)
     */
    protected $planta;

    /**
     * @var string $sala
     *
     * @ORM\Column(name="sala", type="string", length=255)
     */
    protected $sala;
    
    /**
     * @var integer $comensales
     *
     * @ORM\Column(name="comensales", type="integer", length=3)
     */
    protected $comensales;

    /**
     * @var string $foto
     *
     * @ORM\Column(name="foto", type="string", length=255)
     */
    protected $foto;

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
     * Set nombre
     *
     * @param string $nombre
     * @return Mesas
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
     * Set planta
     *
     * @param string $planta
     * @return Mesas
     */
    public function setPlanta($planta)
    {
        $this->planta = $planta;
    
        return $this;
    }

    /**
     * Get plantas
     *
     * @return string 
     */
    public function getPlanta()
    {
        return $this->planta;
    }

    /**
     * Set sala
     *
     * @param string $sala
     * @return Mesas
     */
    public function setSala($sala)
    {
        $this->sala = $sala;
    
        return $this;
    }

    /**
     * Get sala
     *
     * @return string 
     */
    public function getSala()
    {
        return $this->sala;
    }

    /**
     * Set comensales
     *
     * @param integer $comensales
     * @return Mesas
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
     * Set foto
     *
     * @param string $foto
     * @return Mesas
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

    /**
     * Set sociedades_id
     *
     * @param integer $sociedadesId
     * @return Mesas
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

    public function subirFoto($directorioDestino)
    {
        if (null === $this->foto) {
        return;
        }
        $nombreArchivoFoto = uniqid('mesas-').'-foto1.jpg';
        $this->foto->move($directorioDestino, $nombreArchivoFoto);
        $directorioDestino= substr($directorioDestino, strpos($directorioDestino, 'web')+3); //'/bundles/sociedad/uploads/images/';
        $this->setFoto($directorioDestino.$nombreArchivoFoto);
    }    
}