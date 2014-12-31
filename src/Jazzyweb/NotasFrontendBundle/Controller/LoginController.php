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

class LoginController extends Controller {

    /**
     * @Route("/login", name="jamn_login")
     */
    public function loginAction() {
        $request = $this->getRequest();
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render(
            'JazzywebNotasFrontendBundle:Login:login.html.twig', array(
                // last username entered by the user
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error' => $error,
            )
        );
    }

    /**
     * @Route("/login_check", name="jamn_login_check")
     */
    public function loginCheck(){

    }

    /**
     * @Route("/logout", name="jamn_logout")
     */
    public function logoutAction(){

    }
}