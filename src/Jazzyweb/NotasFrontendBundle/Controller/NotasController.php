<?php

namespace Jazzyweb\NotasFrontendBundle\Controller;

use Jazzyweb\NotasFrontendBundle\Entity\Nota;
use Jazzyweb\NotasFrontendBundle\Entity\Usuario;
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

    protected function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
            ;
    }

    protected function dameEtiquetasYNotas() {
        $session = $this->get('session');
        $em = $this->getDoctrine()->getManager();

//        $usuario = $this->get('security.context')->getToken()->getUser();
        $username = 'abigail79';

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

}