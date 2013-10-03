<?php

namespace Jazzyweb\NotasFrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class NotasController extends Controller {

    /**
     * @Route("/", name="jamn_homepage")
     * @Route("/conetiqueta/{etiqueta}", name="jamn_conetiqueta")
     * @Route("/buscar", name="jamn_buscar")
     * @Template()
     */
    public function indexAction()
    {
        $etiquetas = null;
        $notas = null;
        $notaSeleccionada = null;
        $deleteForm = null;

        return
            array(
                'etiquetas' => $etiquetas,
                'notas' => $notas,
                'nota_seleccionada' => $notaSeleccionada,
                'delete_form' => $deleteForm,
            );
    }

    /**
     * @Route("/nota/{id}", name="jamn_nota")
     */
    public function notaAction(){

    }

    /**
     * @Route("/nueva", name="jamn_nueva")
     */
    public function nuevaNotaAction(){

    }

    /**
     * @Route("/editar/{id}", name="jamn_editar", requirements={"id" = "\d+"})
     */
    public function editarNotaAction(){

    }

    /**
     * @Route("borrar/{id}", name="jamn_borrar", requirements={"id" = "\d+"})
     */
    public function borrarNotaAction(){

    }

    /**
     * @Route("/contratar", name="jamn_contratar")
     */
    public function contratarAction(){

    }

    /**
     * @Route("/logout", name="jamn_logout")
     */
    public function logoutAction(){

    }
}