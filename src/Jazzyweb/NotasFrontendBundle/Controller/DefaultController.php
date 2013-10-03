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
     * @Route("/consultas/con/find")
     */

    public function consultasConFind(){

        $doctrine = $this->getDoctrine();

        $usuarioRepo = $doctrine->getRepository('\Jazzyweb\NotasFrontendBundle\Entity\Usuario');

        $notaRepo     = $doctrine->getRepository('JazzywebNotasFrontendBundle:Nota');

        $etiquetaRepo = $doctrine->getRepository('JazzywebNotasFrontendBundle:Etiqueta');

        $usuario_1 = $usuarioRepo->find(1);
        $unUsuario = $usuarioRepo->findOneByPassword('pruebas');
        $muchosUsuarios = $usuarioRepo->findByPassword('pruebas');
        $etiquetas = $etiquetaRepo->findAll();
        $notasUsuario_1 = $notaRepo->findByUsuario($usuario_1);

        ld($usuario_1);
        ld($unUsuario);
        ld($muchosUsuarios);
        ld($etiquetas);
        ldd($notasUsuario_1);
    }

    /**
     * @Route("/consultas/con/DQL")
     */
    public function consultasConDQL(){

        $em = $this->getDoctrine()->getManager();

        $queryUsuarios = $em->createQuery(
            'SELECT u FROM JazzywebNotasFrontendBundle:Usuario u WHERE u.nombre LIKE :patron'
        )->setParameter('patron', '%F%');

        $usuarios = $queryUsuarios->getResult();

        $queryNotasFecha = $em->createQuery(
            "SELECT n FROM Jazzyweb\NotasFrontendBundle\Entity\Nota n
              JOIN n.usuario u
              WHERE  u.username=:username ORDER BY n.fecha DESC")
            ->setParameters(array('username' => 'camila66'));

        $notas_1 =  $queryNotasFecha->getResult();

        $queryNotasUsuarioEtiquetas = $em->createQuery(
            "SELECT n FROM Jazzyweb\NotasFrontendBundle\Entity\Nota n
             JOIN  n.etiquetas e
             JOIN n.usuario u
             WHERE e.id = :id_etiqueta and u.username=:username")
            ->setParameters(array('username' => 'camila66', 'id_etiqueta' => 81));

        $notas_2 = $queryNotasUsuarioEtiquetas->getResult();





        ld($usuarios);
        ld($notas_1);
        ldd($notas_2);
    }

    /**
     * @Route("/consultas/con/QueryBuilder")
     */
    public function consultasConQueryBuilder(){

        $em = $this->getDoctrine()->getManager();

        $queryUsuarios = $em->createQueryBuilder()
            ->select('u')
            ->from('JazzywebNotasFrontendBundle:Usuario', 'u')
            ->where('u.nombre LIKE :patron')
            ->setParameter('patron', '%F%')
            ->getQuery();

        $usuarios = $queryUsuarios->getResult();

        $queryNotasFecha = $em->createQueryBuilder()
            ->select('n')
            ->from('JazzywebNotasFrontendBundle:Nota', 'n')
            ->leftJoin('n.usuario', 'u')
            ->where('u.username=:username')
            ->orderBy('n.fecha','DESC')
            ->setParameter('username', 'camila66')
            ->getQuery();

        $notas_1 = $queryNotasFecha->getResult();

        $queryNotasUsuarioEtiquetas = $em->createQueryBuilder()
            ->select('n')
            ->from('JazzywebNotasFrontendBundle:Nota', 'n')
            ->leftJoin('n.etiquetas', 'e')
            ->leftJoin('n.usuario', 'u')
            ->where('e.id = :id_etiqueta and u.username=:username')
            ->setParameters(array('id_etiqueta' => 81, 'username' => 'camila66'))
            ->getQuery();

        $notas_2 = $queryNotasUsuarioEtiquetas->getResult();

        ld($usuarios);
        ld($notas_1);
        ldd($notas_2);


    }
}
