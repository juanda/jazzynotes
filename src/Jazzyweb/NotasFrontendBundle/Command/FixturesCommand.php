<?php

namespace Jazzyweb\NotasFrontendBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FixturesCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
            ->setName('jwfrontend:fixtures:load')
            ->setDescription('Load example data in database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $dialog = $this->getHelperSet()->get('dialog');

        if (!$dialog->askConfirmation(
            $output,
            '<question>Proceed to load fixtures? (All data in database will be lost)</question>',
            false
        )) {
            $output->writeln('<error>Action aborted</error>');
            return;
        }

        try {
            $this->deleteAllData();
            $doctrine = $this->getContainer()->get('doctrine');

            $em = $doctrine->getEntityManager();

            $output->writeln("Loading fixtures ...");

            $objects = \Nelmio\Alice\Fixtures::load(__DIR__ . '/../Fixtures/Fixtures.yml', $em, array('locale' => 'es_AR'));

            $output->writeln("Fixtures have been loaded");
        } catch (\Exception $e) {
            $output->writeln("<error>" . $e->getMessage() . "</error>");
        }
    }

    protected function deleteAllData(){
        $doctrine = $this->getContainer()->get('doctrine');

        $em = $doctrine->getEntityManager();

        $query = $em->createQuery('DELETE FROM Jazzyweb\NotasFrontendBundle\Entity\Banner b');
        $query->execute();
        $query = $em->createQuery('DELETE FROM Jazzyweb\NotasFrontendBundle\Entity\Nota n');
        $query->execute();
        $query = $em->createQuery('DELETE FROM Jazzyweb\NotasFrontendBundle\Entity\Etiqueta e');
        $query->execute();
        $query = $em->createQuery('DELETE FROM Jazzyweb\NotasFrontendBundle\Entity\Contrato c');
        $query->execute();
        $query = $em->createQuery('DELETE FROM Jazzyweb\NotasFrontendBundle\Entity\Tarifa t');
        $query->execute();
        $query = $em->createQuery('DELETE FROM Jazzyweb\NotasFrontendBundle\Entity\Usuario u');
        $query->execute();
        $query = $em->createQuery('DELETE FROM Jazzyweb\NotasFrontendBundle\Entity\Grupo g');
        $query->execute();

        $em->flush();
    }

}
