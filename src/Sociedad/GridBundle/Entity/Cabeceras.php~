<?php
namespace Sociedad\GridBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Sociedad\GridBundle\Entity\Cabeceras
 *
 * @ORM\Table(name="cabeceras")
 * @ORM\Table(name="cabeceras",indexes={@ORM\index(name="customer_idx", columns={"id_usuarios"}),
 *                                      @ORM\index(name="cabecera_idx", columns={"nombre"})})
 * @ORM\Entity(repositoryClass="Sociedad\GridBundle\Entity\CabecerasRepository")
 */
class Cabeceras
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer $usuariosId
     *
     * @ORM\Column(name="usuarios_id", type="integer", nullable=true)
     */
    private $usuarios_id;

    /**
     * @var string $nombre
     *
     * @ORM\Column(name="nombre", type="string", length=50, nullable=false)
     * @Assert\NotBlank()
     */
    private $nombre;

    /**
     * @var integer id_usuarios
     *
     * @ORM\Column(name="id_usuarios", type="integer", nullable=false )
     */
    private $id_usuarios;

    /**
     * @var string $campo
     *
     * @ORM\Column(name="campo", type="string", length=50, nullable=false)
     * @Assert\NotBlank()
     */
    private $campo;

    /**
     * @var string $orden
     *
     * @ORM\Column(name="orden", type="integer", length=50, nullable=false)
     * @Assert\NotBlank()
     */
    private $orden;

    /**
     * @var string $titulo
     *
     * @ORM\Column(name="titulo", type="string", length=400, nullable=false)
     * @Assert\NotBlank()
     */
    private $titulo;
    /**
     * @var string $condicion
     *
     * @ORM\Column(name="condicion", type="string", length=200, nullable=true)
     * @Assert\NotBlank()
     */
    private $condicion;
    
    
    /**
     * @ORM\OneToMany(targetEntity="Sociedad\GridBundle\Entity\Propiedades", mappedBy="cabeceras", cascade={"remove"})
     */
    private $propiedades;
    
    /**
     * @ORM\ManyToOne(targetEntity="Sociedad\SociosBundle\Entity\Socios", inversedBy="cabeceras")
     * @ORM\JoinColumn(name="usuarios_id", referencedColumnName="id")
     */
    protected $usuarios;
   
    public function __toString()
    {
        return $this->getNombre();
    }

    public function __construct()
    {
        $this->propiedades = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set usuarios_id
     *
     * @param integer $usuariosId
     */
    public function setUsuariosId($usuariosId)
    {
        $this->usuarios_id = $usuariosId;
    }

    /**
     * Get usuarios_id
     *
     * @return integer 
     */
    public function getUsuariosId()
    {
        return $this->usuarios_id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
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
     * Set id_usuarios
     *
     * @param integer $idUsuarios
     */
    public function setIdUsuarios($idUsuarios)
    {
        $this->id_usuarios = $idUsuarios;
    }

    /**
     * Get id_usuarios
     *
     * @return integer 
     */
    public function getIdUsuarios()
    {
        return $this->id_usuarios;
    }

    /**
     * Set campo
     *
     * @param string $campo
     */
    public function setCampo($campo)
    {
        $this->campo = $campo;
    }

    /**
     * Get campo
     *
     * @return string 
     */
    public function getCampo()
    {
        return $this->campo;
    }

    /**
     * Set orden
     *
     * @param integer $orden
     */
    public function setOrden($orden)
    {
        $this->orden = $orden;
    }

    /**
     * Get orden
     *
     * @return integer 
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * Set titulo
     *
     * @param string $titulo
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    /**
     * Get titulo
     *
     * @return string 
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Add propiedades
     *
     * @param Sociedad\GridBundle\Entity\Propiedades $propiedades
     */
    public function addPropiedades(\Sociedad\GridBundle\Entity\Propiedades $propiedades)
    {
        $this->propiedades[] = $propiedades;
    }

    /**
     * Get propiedades
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPropiedades()
    {
        return $this->propiedades;
    }

    /**
     * Set usuarios
     *
     * @param Sociedad\SociosBundle\Entity\Socios $usuarios
     */
    public function setUsuarios(\Sociedad\SociosBundle\Entity\Socios $usuarios)
    {
        $this->usuarios = $usuarios;
    }

    /**
     * Get usuarios
     *
     * @return Sociedad\SociosBundle\Entity\Socios 
     */
    public function getUsuarios()
    {
        return $this->usuarios;
    }

    /**
     * Set condicion
     *
     * @param string $condicion
     */
    public function setCondicion($condicion)
    {
        $this->condicion = $condicion;
    }

    /**
     * Get condicion
     *
     * @return string 
     */
    public function getCondicion()
    {
        return $this->condicion;
    }

    /**
     * Add propiedades
     *
     * @param \Sociedad\GridBundle\Entity\Propiedades $propiedades
     * @return Cabeceras
     */
    public function addPropiedade(\Sociedad\GridBundle\Entity\Propiedades $propiedades)
    {
        $this->propiedades[] = $propiedades;
    
        return $this;
    }

    /**
     * Remove propiedades
     *
     * @param \Sociedad\GridBundle\Entity\Propiedades $propiedades
     */
    public function removePropiedade(\Sociedad\GridBundle\Entity\Propiedades $propiedades)
    {
        $this->propiedades->removeElement($propiedades);
    }
}