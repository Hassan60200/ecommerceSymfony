# ecommerceSymfony
Installation de l'application:

Clonez le dépot où vous voulez

Installez les dépendances : composer install

Créez votre propre base de donnée sur le .env et par la suite sur le terminale tapez: doctrine:database:create

Jouez les migrations : php bin/console doctrine:migrations:migrate

Jouez les fixtures : php bin/console d:f:l --no-interaction

Lancez le server : symfony serve ou php -S localhost:3000 -t public
