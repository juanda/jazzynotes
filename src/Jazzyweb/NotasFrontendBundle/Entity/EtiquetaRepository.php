<?php

namespace Jazzyweb\NotasFrontendBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * EtiquetaRepository
 */
class EtiquetaRepository extends EntityRepository {

    public function findByUsuarioOrderedByTexto($username) {
        $query = $this->getEntityManager()->createQuery(
                        "SELECT e FROM Jazzyweb\NotasFrontendBundle\Entity\Etiqueta e
                      JOIN  e.notas n JOIN n.usuario u where u.username = :username ORDER BY e.texto ASC")
                ->setParameters(array('username' => $username));

        return $query->getResult();
    }

    public function findOneByTextoAndUsuario($tag, $username) {
        $query = $this->getEntityManager()->createQuery(
                        "SELECT e FROM Jazzyweb\NotasFrontendBundle\Entity\Etiqueta e JOIN e.usuario u where u.username = :username AND e.texto=:texto")
                ->setParameters(array('texto' => $tag, 'username' => $username));

        $result = $query->getResult();

        if (count($result) > 0) {
            return $result[0];
        } else {
            return null;
        }
    }

}
