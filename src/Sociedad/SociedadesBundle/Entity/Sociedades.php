<?php

namespace Sociedad\SociedadesBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Sociedad\SociosBundle\Entity\Socios;
use Doctrine\ORM\Mapping as ORM;

/**
 * Sociedad\SociedadesBundle\Entity\Sociedades
 *
 * @ORM\Table(name="sociedades")
 * @ORM\Entity(repositoryClass="Sociedad\SociedadesBundle\Entity\SociedadesRepository")
 */
class Sociedades
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
     * @var string $nombre
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    protected $nombre;

    /**
     * @var string $direccion
     *
     * @ORM\Column(name="direccion", type="string", length=255)
     */
    protected $direccion;

    /**
     * @var string $telefono
     *
     * @ORM\Column(name="telefono", type="string", length=255)
     */
    protected $telefono;
    
    /**
     * @var string $poblacion
     *
     * @ORM\Column(name="poblacion", type="string", length=255)
     */
    protected $poblacion;

    /**
     * @var string $foto
     *
     * @ORM\Column(name="foto", type="string", length=255, nullable=true)
     */
    protected $foto;
    
    /**
     * @var string $fechaalta
     *
     * @ORM\Column(name="fechaalta", type="datetime", nullable=false)
     */
    protected $fechaalta;

    /**
     * @var string $fechacreacion
     *
     * @ORM\Column(name="fechacreacion", type="datetime", nullable=false)
     */
    protected $fechacreacion;
    
    /**
     * @var string $numero_cuenta
     *
     * @ORM\Column(name="numero_cuenta", type="string", length=20)
     */
    protected $numero_cuenta;

    /**
     * @var string $notas
     *
     * @ORM\Column(name="notas", type="text")
     */
    protected $notas;    

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=100)
     * @Assert\Email(message = "El email '{{ value }}' no es válido.")
     */
     protected $email;

     /**
     * @var string $password
     *
     * @ORM\Column(name="password", type="string", length=100)
     */
    protected $password;

    /**
     * @var string $calendario
     *
     * @ORM\Column(name="calendario", type="string", length=100)
     */
    protected $calendario;

    /** @ORM\OneToMany(targetEntity="Sociedad\SociosBundle\Entity\Socios", mappedBy="sociedades", cascade={"remove"})
     */
    protected $socios;    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->socios = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nombre
     *
     * @param string $nombre
     * @return Sociedades
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
     * Set direccion
     *
     * @param string $direccion
     * @return Sociedades
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    
        return $this;
    }

    /**
     * Get direccion
     *
     * @return string 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     * @return Sociedades
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    
        return $this;
    }

    /**
     * Get telefono
     *
     * @return string 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set poblacion
     *
     * @param string $poblacion
     * @return Sociedades
     */
    public function setPoblacion($poblacion)
    {
        $this->poblacion = $poblacion;
    
        return $this;
    }

    /**
     * Get poblacion
     *
     * @return string 
     */
    public function getPoblacion()
    {
        return $this->poblacion;
    }

    /**
     * Set foto
     *
     * @param string $foto
     * @return Sociedades
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
     * Set fechaalta
     *
     * @param \DateTime $fechaalta
     * @return Sociedades
     */
    public function setFechaalta($fechaalta)
    {
        $this->fechaalta = $fechaalta;
    
        return $this;
    }

    /**
     * Get fechaalta
     *
     * @return \DateTime 
     */
    public function getFechaalta()
    {
        return $this->fechaalta;
    }

    /**
     * Set fechacreacion
     *
     * @param \DateTime $fechacreacion
     * @return Sociedades
     */
    public function setFechacreacion($fechacreacion)
    {
        $this->fechacreacion = $fechacreacion;
    
        return $this;
    }

    /**
     * Get fechacreacion
     *
     * @return \DateTime 
     */
    public function getFechacreacion()
    {
        return $this->fechacreacion;
    }

    /**
     * Set numero_cuenta
     *
     * @param string $numeroCuenta
     * @return Sociedades
     */
    public function setNumeroCuenta($numeroCuenta)
    {
        $this->numero_cuenta = $numeroCuenta;
    
        return $this;
    }

    /**
     * Get numero_cuenta
     *
     * @return string 
     */
    public function getNumeroCuenta()
    {
        return $this->numero_cuenta;
    }

    /**
     * Set notas
     *
     * @param string $notas
     * @return Sociedades
     */
    public function setNotas($notas)
    {
        $this->notas = $notas;
    
        return $this;
    }

    /**
     * Get notas
     *
     * @return string 
     */
    public function getNotas()
    {
        return $this->notas;
    }

    /**
     * Add socios
     *
     * @param Sociedad\SociosBundle\Entity\Socios $socios
     * @return Sociedades
     */
    public function addSocio(\Sociedad\SociosBundle\Entity\Socios $socios)
    {
        $this->socios[] = $socios;
    
        return $this;
    }

    /**
     * Remove socios
     *
     * @param Sociedad\SociosBundle\Entity\Socios $socios
     */
    public function removeSocio(\Sociedad\SociosBundle\Entity\Socios $socios)
    {
        $this->socios->removeElement($socios);
    }

    /**
     * Get socios
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getSocios()
    {
        return $this->socios;
    }

    public function subirFoto($directorioDestino)
    {
        if (null === $this->foto) {
        return;
        }
        $nombreArchivoFoto = uniqid('sociedad-').'-foto1.jpg';
        $this->foto->move($directorioDestino, $nombreArchivoFoto);
        $directorioDestino= substr($directorioDestino, strpos($directorioDestino, 'web')+3); //'/bundles/sociedad/uploads/images/';
        $this->setFoto($directorioDestino.$nombreArchivoFoto);
    }    
    

    /**
     * Set email
     *
     * @param string $email
     * @return Sociedades
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
     * Set password
     *
     * @param string $password
     * @return Sociedades
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set calendario
     *
     * @param string $calendario
     * @return Sociedades
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
    public function __toString()
    {
        return (string) $this->getNombre();
    }
}