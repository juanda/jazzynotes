<?php

namespace Jazzyweb\NotasFrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/hello/{name}")
     * @Template()
     */
    public function indexAction($name)
    {
        return array('name' => $name);
    }

    /**
     * @Route("/consulta/con/find")
     */

    public function consultaConFind(){

        $doctrine = $this->getDoctrine();

        $usuarioRepo = $doctrine->getRepository('\Jazzyweb\NotasFrontendBundle\Entity\Usuario');

        $notaRepo     = $doctrine->getRepository('JazzywebNotasFrontendBundle:Nota');

        $etiquetaRepo = $doctrine->getRepository('JazzywebNotasFrontendBundle:Etiqueta');

        $usuario_1 = $usuarioRepo->find(1);
        $etiquetas = $etiquetaRepo->findAll();

        $notasUsuario_1 = $notaRepo->findByUsuario($usuario_1);



        ld($usuario_1);
        ld($etiquetas);
        ldd($notasUsuario_1);


    }
}
