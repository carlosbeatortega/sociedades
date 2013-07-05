<?php


namespace Sociedad\SociosBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Sociedad\SociedadesBundle\Entity\Sociedades;
use Symfony\Component\Validator\Constraints as Assert;
use Sociedad\SociosBundle\Entity\Contactos;
use Sociedad\ReservasBundle\Entity\Reservas;
use Doctrine\ORM\Mapping as ORM;

/**
 * Sociedad\SociosBundle\Entity\Socios
 * 
 * @ORM\Entity
 * @ORM\Table(name="socios")
 * @ORM\Entity(repositoryClass="Sociedad\SociosBundle\Entity\SociosRepository")
 */
class Socios extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

/**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="Introduce Nombre.", groups={"Registration", "Profile"})
     * @Assert\MinLength(limit="3", message="Nombre demasiado corto.", groups={"Registration", "Profile"})
     * @Assert\MaxLength(limit="255", message="Nombre demasiado largo.", groups={"Registration", "Profile"})
     */
    protected $name;
    
    /**
     * @var integer $sociedades_id
     *
     * @ORM\Column(name="sociedades_id", type="integer", nullable=false)
     */
    protected $sociedades_id;
    
    /**
     * @var string $foto
     *
     * @ORM\Column(name="foto", type="string", length=255, nullable=true)
     */
    protected $foto;

    /**
     * @var string $dni
     *
     * @ORM\Column(name="dni", type="string", length=10)
     */
    protected $dni;

    /**
     * @var string $fechaalta
     *
     * @ORM\Column(name="fechaalta", type="datetime", nullable=false)
     */
    protected $fechaalta;

    /**
     * @var string $fechanacimiento
     *
     * @ORM\Column(name="fechanacimiento", type="datetime", nullable=false)
     */
    protected $fechanacimiento;

    /**
     * @var string $numero_cuenta
     *
     * @ORM\Column(name="numero_cuenta", type="string", length=20,nullable=true)
     */
    protected $numero_cuenta;

    /**
     * @var string $passwordCanonical
     *
     * @ORM\Column(name="passwordCanonical", type="string", length=100,nullable=true)
     */
    protected $passwordCanonical;
    
    /** @ORM\ManyToOne(targetEntity="Sociedad\SociedadesBundle\Entity\Sociedades") */
    protected $sociedades;

    /** @ORM\OneToMany(targetEntity="Sociedad\SociosBundle\Entity\Contactos", mappedBy="socios", cascade={"remove"}) */
    protected $contactos;

    /** @ORM\OneToMany(targetEntity="Sociedad\ReservasBundle\Entity\Reservas", mappedBy="socio", cascade={"remove"}) */
    protected $reservas;
    
    public function __construct()
    {
        parent::__construct();
        // your own logic
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
     * @return Socios
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
     * Set foto
     *
     * @param string $foto
     * @return Socios
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
     * Set dni
     *
     * @param string $dni
     * @return Socios
     */
    public function setDni($dni)
    {
        $this->dni = $dni;
    
        return $this;
    }

    /**
     * Get dni
     *
     * @return string 
     */
    public function getDni()
    {
        return $this->dni;
    }

    /**
     * Set fechaalta
     *
     * @param \DateTime $fechaalta
     * @return Socios
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
     * Set fechanacimiento
     *
     * @param \DateTime $fechanacimiento
     * @return Socios
     */
    public function setFechanacimiento($fechanacimiento)
    {
        $this->fechanacimiento = $fechanacimiento;
    
        return $this;
    }

    /**
     * Get fechanacimiento
     *
     * @return \DateTime 
     */
    public function getFechanacimiento()
    {
        return $this->fechanacimiento;
    }

    /**
     * Set numero_cuenta
     *
     * @param string $numeroCuenta
     * @return Socios
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
     * Set sociedades
     *
     * @param Sociedad\SociedadesBundle\Entity\Sociedades $sociedades
     * @return Socios
     */
    public function setSociedades(\Sociedad\SociedadesBundle\Entity\Sociedades $sociedades = null)
    {
        $this->sociedades = $sociedades;
    
        return $this;
    }

    /**
     * Get sociedades
     *
     * @return Sociedad\SociedadesBundle\Entity\Sociedades 
     */
    public function getSociedades()
    {
        return $this->sociedades;
    }

    /**
     * Add contactos
     *
     * @param Sociedad\SociosBundle\Entity\Contactos $contactos
     * @return Socios
     */
    public function addContacto(\Sociedad\SociosBundle\Entity\Contactos $contactos)
    {
        $this->contactos[] = $contactos;
    
        return $this;
    }

    /**
     * Remove contactos
     *
     * @param Sociedad\SociosBundle\Entity\Contactos $contactos
     */
    public function removeContacto(\Sociedad\SociosBundle\Entity\Contactos $contactos)
    {
        $this->contactos->removeElement($contactos);
    }

    /**
     * Get contactos
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getContactos()
    {
        return $this->contactos;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Socios
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Get expiresat
     *
     * @return \DateTime
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }
    
    /**
     * Get getcredentialsexpiresat
     *
     * @return \DateTime
     */
    public function getCredentialsExpireAt()
    {
        return $this->credentialsExpireAt;
    }
    
    public function subirFoto($directorioDestino,$imagendefecto)
    {
        if (null === $this->foto) {
            $this->setFoto($imagendefecto);
            return;
        }
        $nombreArchivoFoto = uniqid('socio-').'-foto1.jpg';
        $this->foto->move($directorioDestino, $nombreArchivoFoto);
        $directorioDestino= substr($directorioDestino, strpos($directorioDestino, 'web')+3); //'/bundles/sociedad/uploads/images/';
        $this->setFoto($directorioDestino.$nombreArchivoFoto);
    }    

    /**
     * Add reservas
     *
     * @param \Sociedad\ReservasBundle\Entity\Reservas $reservas
     * @return Socios
     */
    public function addReserva(\Sociedad\ReservasBundle\Entity\Reservas $reservas)
    {
        $this->reservas[] = $reservas;
    
        return $this;
    }

    /**
     * Remove reservas
     *
     * @param \Sociedad\ReservasBundle\Entity\Reservas $reservas
     */
    public function removeReserva(\Sociedad\ReservasBundle\Entity\Reservas $reservas)
    {
        $this->reservas->removeElement($reservas);
    }

    /**
     * Get reservas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReservas()
    {
        return $this->reservas;
    }

    /**
     * Set passwordCanonical
     *
     * @param string $passwordCanonical
     * @return Socios
     */
    public function setPasswordCanonical($passwordCanonical)
    {
        $this->passwordCanonical = $passwordCanonical;
    
        return $this;
    }

    /**
     * Get passwordCanonical
     *
     * @return string 
     */
    public function getPasswordCanonical()
    {
        return $this->passwordCanonical;
    }
}