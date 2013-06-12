<?php
// src/aner/posicionBundle/Entity/Propiedades.php

namespace Sociedad\GridBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Sociedad\GridBundle\Entity\Propiedades
 *
 * @ORM\Table(name="propiedades",indexes={@ORM\index(name="cabeceras", columns={"cabeceras_id"}),
 *                                        @ORM\index(name="customer", columns={"id_usuarios"})})
 * @ORM\Entity(repositoryClass="Sociedad\GridBundle\Entity\PropiedadesRepository")
 */
class Propiedades
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
     * @var integer cabecerasid
     *
     * @ORM\Column(name="cabeceras_id", type="integer", nullable=false )
     */
    private $cabeceras_id;
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
     * @var string $valor
     *
     * @ORM\Column(name="valor", type="string", length=200, nullable=true)
     * @Assert\NotBlank()
     */
    private $valor;
    /**

    /**
     * @var integer id_usuarios
     *
     * @ORM\Column(name="id_usuarios", type="integer", nullable=false )
     */
    private $id_usuarios;

    /**
     * @var string $ruta
     *
     * @ORM\Column(name="ruta", type="string", length=100, nullable=true)
     * @Assert\NotBlank()
     */
    private $ruta;
    /**
    /**
     * @var string $entity
     *
     * @ORM\Column(name="entity", type="string", length=100, nullable=true)
     * @Assert\NotBlank()
     */
    private $entity;
    /**
    /**
     * @var string $entity1
     *
     * @ORM\Column(name="entity1", type="string", length=100, nullable=true)
     * @Assert\NotBlank()
     */
    private $entity1;
    /**
    /**
     * @var string $td
     *
     * @ORM\Column(name="td", type="string", length=10, nullable=true)
     * @Assert\NotBlank()
     */
    private $td;

    /**
     * @var string $param
     *
     * @ORM\Column(name="param", type="string", length=100, nullable=true)
     * @Assert\NotBlank()
     */
    private $param;
    /**


    /**
     * @var string $param1
     *
     * @ORM\Column(name="param1", type="string", length=100, nullable=true)
     * @Assert\NotBlank()
     */
    private $param1;
    /**

    /**
     * @var string $condicion
     *
     * @ORM\Column(name="condicion", type="string", length=200, nullable=true)
     * @Assert\NotBlank()
     */
    private $condicion;

    
    /**
     * @ORM\ManyToOne(targetEntity="Sociedad\GridBundle\Entity\Cabeceras", inversedBy="propiedades")
     * @ORM\JoinColumn(name="cabeceras_id", referencedColumnName="id")
     */
    protected $cabeceras;
    
    
    public function __toString()
    {
        return $this->getNombre();
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
     * Set cabeceras_id
     *
     * @param integer $cabecerasId
     */
    public function setCabecerasId($cabecerasId)
    {
        $this->cabeceras_id = $cabecerasId;
    }

    /**
     * Get cabeceras_id
     *
     * @return integer 
     */
    public function getCabecerasId()
    {
        return $this->cabeceras_id;
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
     * Set valor
     *
     * @param string $valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * Get valor
     *
     * @return string 
     */
    public function getValor()
    {
        return $this->valor;
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
     * Set ruta
     *
     * @param string $ruta
     */
    public function setRuta($ruta)
    {
        $this->ruta = $ruta;
    }

    /**
     * Get ruta
     *
     * @return string 
     */
    public function getRuta()
    {
        return $this->ruta;
    }

    /**
     * Set entity
     *
     * @param string $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    /**
     * Get entity
     *
     * @return string 
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Set entity1
     *
     * @param string $entity1
     */
    public function setEntity1($entity1)
    {
        $this->entity1 = $entity1;
    }

    /**
     * Get entity1
     *
     * @return string 
     */
    public function getEntity1()
    {
        return $this->entity1;
    }

    /**
     * Set param
     *
     * @param string $param
     */
    public function setParam($param)
    {
        $this->param = $param;
    }

    /**
     * Get param
     *
     * @return string 
     */
    public function getParam()
    {
        return $this->param;
    }

    /**
     * Set param1
     *
     * @param string $param1
     */
    public function setParam1($param1)
    {
        $this->param1 = $param1;
    }

    /**
     * Get param1
     *
     * @return string 
     */
    public function getParam1()
    {
        return $this->param1;
    }

    /**
     * Set cabeceras
     *
     * @param Sociedad\GridBundle\Entity\Cabeceras $cabeceras
     */
    public function setCabeceras(\Sociedad\GridBundle\Entity\Cabeceras $cabeceras)
    {
        $this->cabeceras = $cabeceras;
    }

    /**
     * Get cabeceras
     *
     * @return Sociedad\GridBundle\Entity\Cabeceras 
     */
    public function getCabeceras()
    {
        return $this->cabeceras;
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
     * Set td
     *
     * @param string $td
     */
    public function setTd($td)
    {
        $this->td = $td;
    }

    /**
     * Get td
     *
     * @return string 
     */
    public function getTd()
    {
        return $this->td;
    }
}