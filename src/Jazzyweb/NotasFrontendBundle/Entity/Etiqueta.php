<?php

namespace Jazzyweb\NotasFrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Jazzyweb\NotasFrontendBundle\Entity\Etiqueta
 *
 * @ORM\Table(name="etiquetas")
 * @ORM\Entity(repositoryClass="Jazzyweb\NotasFrontendBundle\Entity\EtiquetaRepository")
 */
class Etiqueta
{

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $texto
     *
     * @ORM\Column(name="texto", type="string", length=255)
     */
    private $texto;

    ////ASOCIACIONES////

    /**
     * @ORM\ManyToMany(targetEntity="Nota", mappedBy="etiquetas")
     */
    private $notas;

    ////FIN ASOCIACIONES////

    public function __construct()
    {
        $this->notas = new ArrayCollection();
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
     * Set texto
     *
     * @param string $texto
     */
    public function setTexto($texto)
    {
        $this->texto = $texto;
    }

    /**
     * Get texto
     *
     * @return string 
     */
    public function getTexto()
    {
        return $this->texto;
    }


    /**
     * Add notas
     *
     * @param Jazzyweb\NotasFrontendBundle\Entity\Nota $notas
     */
    public function addNota(\Jazzyweb\NotasFrontendBundle\Entity\Nota $notas)
    {
        $this->notas[] = $notas;
    }

    /**
     * Get notas
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getNotas()
    {
        return $this->notas;
    }

    /**
     * Set usuario
     *
     * @param Jazzyweb\NotasFrontendBundle\Entity\Usuario $usuario
     */
    public function setUsuario(\Jazzyweb\NotasFrontendBundle\Entity\Usuario $usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * Get usuario
     *
     * @return Jazzyweb\NotasFrontendBundle\Entity\Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Remove notas
     *
     * @param \Jazzyweb\NotasFrontendBundle\Entity\Nota $notas
     */
    public function removeNota(\Jazzyweb\NotasFrontendBundle\Entity\Nota $notas)
    {
        $this->notas->removeElement($notas);
    }
}