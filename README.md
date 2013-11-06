JazzyNotes
===========

A pedagogical project to dive into Symfony2.

Instalation
-----------

    git clone https://github.com/juanda/jazzynotes.git

    cd jazzynotes

    php composer.phar install

    php app/console doctrine:database:create

    php app/console doctrine:schema:create

    php app/console jwfrontend:fixtures:load

Point your browser to http://your.server/app_dev.php and enter with username 'abigail79' and password 'pruebas' 
(take a look a the database in order to see the available users)
