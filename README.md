ToDoList
========

# p-8 Projet Symfony OpenClasssroom
Openclasroom project, TODO created with symfony


## Installation du projet

Cloner le projet dans le dossier www de votre server local 

Cmd:
```text
Git Clone 'lien ssh ou https'
```
 Une fois le dossier telechargé ouvrer celui ci dans un terminal et installer les dépendances composer et les updater
 
 Cmd:
 ```text
composer update
```

Une fois les fichiers composer mis à jour, mettre à jour les éléments yarn

 Cmd:
 ```text
yarn upgrade
```

## Installation de la Base de donnée

A la base du projet ouvrer le fichier `.env`

Selectionner le système de gestion de base de données de votre serveur local (SQLite,PostgreSQL,Mysql) et completer les informations requisent en supprimant le '#'.

Exemple Mysql:
 ```text
DATABASE_URL=mysql://root:password@127.0.0.1:3306/nom_database?serverVersion=5.7
```

Les information du fichier `.env` complété, Créer la base de donnée ⚠ Utiliser le même nom dans le fichier `.env` et la commande

Cmd:
 ```text
php bin/console doctrine:database:create nom_database
```

Injecter les entités dans votre base de donnée

Cmd:
 ```text
php bin/console doctrine:migrations:migrate
```

Hydrater les entités avec les fixtures

Cmd:
```text
php bin/console doctrine:fixtures:load
```

## Configurer l'envoit d'email

Le projet est configuré pour fonctionner avec Gmail, ⚠ il est déconseillé de le conserver en production voir la documention 
<a href="https://symfony.com/doc/current/mailer.html">Doc Symfony</a>

Compléter les informations dans le fichier '.env'  dans la partie Mailer
