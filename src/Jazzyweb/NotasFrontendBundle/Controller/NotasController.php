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
     * @Route("/", name="jamn_homepage")
     * @Route("/conetiqueta/{etiqueta}", name="jamn_conetiqueta")
     * @Route("/buscar", name="jamn_buscar")
     * @Route("nota/{id}", name="jamn_nota")
     * @Template()
     */
    public function indexAction()
    {
        $request = $this->getRequest(); // equivalente a $this->get('request');
        $session = $this->get('session');

        $ruta = $request->get('_route');

        switch ($ruta) {
            case 'jamn_homepage':

                break;

            case 'jamn_conetiqueta':
                $session->set('busqueda.tipo', 'por_etiqueta');
                $session->set('busqueda.valor', $request->get('etiqueta'));
                $session->set('nota.seleccionada.id', '');

                break;

            case 'jamn_buscar':
                $session->set('busqueda.tipo', 'por_termino');
                $session->set('busqueda.valor', $request->get('termino'));
                $session->set('nota.seleccionada.id', '');

                break;
            case 'jamn_nota':
                $session->set('nota.seleccionada.id', $request->get('id'));
                break;
        }

        list($etiquetas, $notas, $notaSeleccionada) = $this->dameEtiquetasYNotas();

        // creamos un formulario para borrar la nota
        if ($notaSeleccionada instanceof Nota) {
            $deleteForm = $this->createDeleteForm($notaSeleccionada->getId())->createView();
        } else {
            $deleteForm = null;
        }

        return
            array(
                'etiquetas' => $etiquetas,
                'notas' => $notas,
                'nota_seleccionada' => $notaSeleccionada,
                'delete_form' => $deleteForm,
            );
    }


    /**
     * @Route("/nueva", name="jamn_nueva")
     * @Template("JazzywebNotasFrontendBundle:Notas:crearOEditar.html.twig")
     */
    public function nuevaNotaAction(){

        $request = $this->getRequest();

        list($etiquetas, $notas, $nota_seleccionada) = $this->dameEtiquetasYNotas();

        $em = $this->getDoctrine()->getManager();

        $nota = new Nota();
        $newForm = $this->createForm(new NotaType(), $nota);

        if ($request->getMethod() == "POST") {

            $newForm->bind($request);

            if ($newForm->isValid()) {
                $usuario = $this->get('security.context')->getToken()->getUser();

                $item = $request->get('item');
                $this->actualizaEtiquetas($nota, $item['tags'], $usuario);

                $nota->setUsuario($usuario);
                $nota->setFecha(new \DateTime());

                if ($newForm['file']->getData() != '')
                    $nota->upload($usuario->getUsername());

                $em->persist($nota);

                $em->flush();

                return $this->redirect($this->generateUrl('jamn_homepage'));
            }
        }

        return  array(
            'etiquetas' => $etiquetas,
            'notas' => $notas,
            'nota_seleccionada' => $nota,
            'new_form' => $newForm->createView(),
            'edita' => false,
        );
    }

    /**
     * @Route("/editar/{id}", name="jamn_editar", requirements={"id" = "\d+"})
     */
    public function editarNotaAction(){
        $request = $this->getRequest();
        $id = $request->get('id');
        list($etiquetas, $notas, $nota_seleccionada) = $this->dameEtiquetasYNotas();

        $em = $this->getDoctrine()->getManager();

        $nota = $em->getRepository('JazzywebNotasFrontendBundle:Nota')->find($id);

        if (!$nota) {
            throw $this->createNotFoundException('No se ha podido encontrar esa nota');
        }

        $editForm = $this->createForm(new NotaType(), $nota);
        $deleteForm = $this->createDeleteForm($id);

        if ($this->getRequest()->getMethod() == "POST") {

            $editForm->bind($request);

            if ($editForm->isValid()) {
                $usuario = $this->get('security.context')->getToken()->getUser();

                $item = $request->get('item');
                $this->actualizaEtiquetas($nota, $item['tags'], $usuario);

                $nota->setFecha(new \DateTime());

                if ($editForm['file']->getData() != '')
                    $nota->upload($usuario->getUsername());

                $em->persist($nota);

                $em->flush();

                return $this->redirect($this->generateUrl('jamn_homepage'));
            }
        }

        return $this->render('JazzywebNotasFrontendBundle:Notas:crearOEditar.html.twig', array(
            'etiquetas' => $etiquetas,
            'notas' => $notas,
            'nota_seleccionada' => $nota,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'edita' => true,
        ));
    }

    /**
     * @Route("borrar/{id}", name="jamn_borrar", requirements={"id" = "\d+"})
     */
    public function borrarNotaAction(){
        $request = $this->getRequest();
        $session = $this->get('session');
        $form = $this->createDeleteForm($request->get('id'));

        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JazzywebNotasFrontendBundle:Nota')->find($request->get('id'));

            if (!$entity) {
                throw $this->createNotFoundException('Esa nota no existe.');
            }

            $em->remove($entity);
            $em->flush();

            $session->set('nota.seleccionada.id', '');
        }

        return $this->redirect($this->generateUrl('jamn_homepage'));
    }

    /**
     * @Route("/miespacio", name="jamn_espacio_premium")
     */
    public function miEspacionAction(){

    }

    /**
     * @Route("/contratar", name="jamn_contratar")
     */
    public function contratarAction(){

    }

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

    protected function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
            ;
    }

    protected function dameEtiquetasYNotas() {
        $session = $this->get('session');
        $em = $this->getDoctrine()->getManager();

        $usuario = $this->get('security.context')->getToken()->getUser();
        $username = $usuario->getUsername();

        $busqueda_tipo = $session->get('busqueda.tipo');

        $busqueda_valor = $session->get('busqueda.valor');

        // Etiquetas. Se pillan todas
        $etiquetas = $em->getRepository('JazzywebNotasFrontendBundle:Etiqueta')->
            findByUsuarioOrderedByTexto($username);

        // Notas. Se pillan según el filtro almacenado en la sesión
        if ($busqueda_tipo == 'por_etiqueta' && $busqueda_valor != 'todas') {
            $notas = $em->getRepository('JazzywebNotasFrontendBundle:Nota')->
                findByUsuarioAndEtiqueta($username, $busqueda_valor);
        } elseif ($busqueda_tipo == 'por_termino') {
            $notas = $em->getRepository('JazzywebNotasFrontendBundle:Nota')->
                findByUsuarioAndTermino($username, $busqueda_valor);
        } else {
            $notas = $em->getRepository('JazzywebNotasFrontendBundle:Nota')->
                findByUsuarioOrderedByFecha($username);
        }

        $nota_seleccionada = null;
        if (count($notas) > 0) {
            $nota_selecionada_id = $session->get('nota.seleccionada.id');
            if (!is_null($nota_selecionada_id) && $nota_selecionada_id != '') {
                $nota_seleccionada = $em->getRepository('JazzywebNotasFrontendBundle:Nota')->
                    findOneById($nota_selecionada_id);
            } else {
                $nota_seleccionada = $notas[0];
            }
        }

        return array($etiquetas, $notas, $nota_seleccionada);
    }

    protected function actualizaEtiquetas($nota, $tags, $usuario) {

        if (count($tags) == 0) {
            $tags = array();
        }
        $em = $this->getDoctrine()->getManager();

        $nota->getEtiquetas()->clear();

        foreach ($tags as $tag) {
            $etiqueta = $em->getRepository('JazzywebNotasFrontendBundle:Etiqueta')->findOneByTexto($tag);

            if (!$etiqueta instanceof Etiqueta) {
                $etiqueta = new Etiqueta();
                $etiqueta->setTexto($tag);
                $em->persist($etiqueta);
            }

            $nota->addEtiqueta($etiqueta);
        }

        $em->flush();
    }

}