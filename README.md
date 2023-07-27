# symfony_produit_cat
Dans .en changer le numéro de port
symfony console doctrine:database:create
symfony console make:migration
symfony console doctrine:migrations:migrate

// s'il y a un problème supprimer le schema de la base et reprenez la config avec  :
symfony console doctrine:schema:drop -f --full-database
 rm migrations/*.php
symfony console make:migration
symfony console doctrine:migrations:migrate
