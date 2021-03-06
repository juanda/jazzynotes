<?php

namespace Jazzyweb\NotasFrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Jazzyweb\NotasFrontendBundle\Entity\Usuario
 *
 * @ORM\Table(name="usuarios")
 * @ORM\Entity(repositoryClass="Jazzyweb\NotasFrontendBundle\Entity\UsuarioRepository")
 * 
 * @UniqueEntity("email")
 * @UniqueEntity("username")
 */
class Usuario implements AdvancedUserInterface, \Serializable, EquatableInterface {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $nombre
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     * 
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    private $nombre;

    /**
     * @var string $apellidos
     *
     * @ORM\Column(name="apellidos", type="string", length=255)
     */
    private $apellidos;

    /**
     * @var string $salt
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=true)
     * 
     * @Assert\Length(max="255")
     */
    private $salt;

    /**
     * @var string $username
     *
     * @ORM\Column(name="username", type="string", length=255)
     * 
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     * @Assert\Regex(
     *     pattern="/^[\w-]+$/",
     *     message="El nombre de usuario no puede contener más que caracteres alfanuméricos y guiones")
     * 
     */
    private $username;

    /**
     * @var string $password
     *
     * @ORM\Column(name="password", type="string", length=255)
     * 
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     * @Assert\Regex(
     *     pattern="/^[\w-]+$/",
     *     message="El password no puede contener más que caracteres alfanuméricos y guiones")
     */
    private $password;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=255)
     * 
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     * @Assert\Email(
     *     message = "La dirección '{{ value }}' no es válida.")
     */
    private $email;

    /**
     * @var boolean $isActive
     *
     * @ORM\Column(name="isActive", type="boolean")
     * 
     * @Assert\Type(type="bool", message="El valor {{ value }} debe ser {{ type }}.")
     */
    private $isActive;

    /**
     * @var string $tokenRegistro
     *
     * @ORM\Column(name="tokenRegistro", type="string", length=255, nullable=true)
     */
    private $tokenRegistro;

    ////ASOCIACIONES////

    /**
     * @ORM\OneToMany(targetEntity="Nota", mappedBy="usuario")
     */
    private $notas;

    /**
     * @ORM\OneToMany(targetEntity="Contrato", mappedBy="usuario")
     */
    private $contratos;

    /**
     * @ORM\ManyToMany(targetEntity="Grupo", inversedBy="usuarios")
     */
    private $grupos;

    ////FIN ASOCIACIONES////

    public function __construct() {
        $this->notas = new ArrayCollection();
        $this->contratos = new ArrayCollection();
        $this->grupos = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Set apellidos
     *
     * @param string $apellidos
     */
    public function setApellidos($apellidos) {
        $this->apellidos = $apellidos;
    }

    /**
     * Get apellidos
     *
     * @return string 
     */
    public function getApellidos() {
        return $this->apellidos;
    }

    /**
     * Set salt
     *
     * @param string $salt
     */
    public function setSalt($salt) {
        $this->salt = $salt;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt() {
        return $this->salt;
    }

    /**
     * Set username
     *
     * @param string $username
     */
    public function setUsername($username) {
        $this->username = $username;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     */
    public function setIsActive($isActive) {
        $this->isActive = $isActive;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive() {
        return $this->isActive;
    }

    /**
     * Set tokenRegistro
     *
     * @param string $tokenRegistro
     */
    public function setTokenRegistro($tokenRegistro) {
        $this->tokenRegistro = $tokenRegistro;
    }

    /**
     * Get tokenRegistro
     *
     * @return string 
     */
    public function getTokenRegistro() {
        return $this->tokenRegistro;
    }

    /**
     * Add notas
     *
     * @param Jazzyweb\NotasFrontendBundle\Entity\Nota $notas
     */
    public function addNota(\Jazzyweb\NotasFrontendBundle\Entity\Nota $notas) {
        $this->notas[] = $notas;
    }

    /**
     * Get notas
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getNotas() {
        return $this->notas;
    }

    /**
     * Add contratos
     *
     * @param Jazzyweb\NotasFrontendBundle\Entity\Contrato $contratos
     */
    public function addContrato(\Jazzyweb\NotasFrontendBundle\Entity\Contrato $contratos) {
        $this->contratos[] = $contratos;
    }

    /**
     * Get contratos
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getContratos() {
        return $this->contratos;
    }

    /**
     * Add grupos
     *
     * @param Jazzyweb\NotasFrontendBundle\Entity\Grupo $grupos
     */
    public function addGrupo(\Jazzyweb\NotasFrontendBundle\Entity\Grupo $grupos) {
        $this->grupos[] = $grupos;
    }

    /**
     * Get grupos
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getGrupos() {
        return $this->grupos;
    }

    /**
     * Remove notas
     *
     * @param \Jazzyweb\NotasFrontendBundle\Entity\Nota $notas
     */
    public function removeNota(\Jazzyweb\NotasFrontendBundle\Entity\Nota $notas) {
        $this->notas->removeElement($notas);
    }

    /**
     * Remove contratos
     *
     * @param \Jazzyweb\NotasFrontendBundle\Entity\Contrato $contratos
     */
    public function removeContrato(\Jazzyweb\NotasFrontendBundle\Entity\Contrato $contratos) {
        $this->contratos->removeElement($contratos);
    }

    /**
     * Remove etiquetas
     *
     * @param \Jazzyweb\NotasFrontendBundle\Entity\Etiqueta $etiquetas
     */
    public function removeEtiqueta(\Jazzyweb\NotasFrontendBundle\Entity\Etiqueta $etiquetas) {
        $this->etiquetas->removeElement($etiquetas);
    }

    /**
     * Remove grupos
     *
     * @param \Jazzyweb\NotasFrontendBundle\Entity\Grupo $grupos
     */
    public function removeGrupo(\Jazzyweb\NotasFrontendBundle\Entity\Grupo $grupos) {
        $this->grupos->removeElement($grupos);
    }

    public function eraseCredentials() {

    }

    public function getRoles() {
        $roles = array();
        foreach ($this->grupos as $g) {
            $roles[] = $g->getRol();
        }

        return $roles;
    }

    public function isAccountNonExpired() {
        return true;
    }

    public function isAccountNonLocked() {
        return true;
    }

    public function isCredentialsNonExpired() {
        return true;
    }

    public function isEnabled() {
        return $this->isActive;
    }

    public function __toString() {
        return $this->getNombre() . ' ' . $this->getApellidos();
    }


    /**
     * @see \Serializable::serialize()
     */
    public function serialize() {
        return serialize(array(
            $this->id,
                ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized) {
        list (
                $this->id,
                ) = unserialize($serialized);
    }

    public function isEqualTo(UserInterface $user) {
        return $this->username === $user->getUsername();
    }

}