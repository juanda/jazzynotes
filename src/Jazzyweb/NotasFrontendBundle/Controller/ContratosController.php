<?php

namespace Jazzyweb\NotasFrontendBundle\Controller;

use Jazzyweb\NotasFrontendBundle\Entity\Etiqueta;
use Jazzyweb\NotasFrontendBundle\Entity\Nota;
use Jazzyweb\NotasFrontendBundle\Entity\Usuario;
use Jazzyweb\NotasFrontendBundle\Form\Type\NotaType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;

class NotasController extends Controller {

    /**
     * @Route("/contratar", name="jamn_contratar")
     */
    public function contratarAction(){

    }
}