# symfony-blog
Consultation d'articles sur un mini blog. Possibilité de créer, modifier, supprimer ses propres articles après authentification. Interaction avec les autres utilisateurs par le biais de commentaires. 

## Installation

#### Récupération du projet
`git clone https://github.com/davidbreyault/symfony-blog.git`

#### Installation des dépendances
`composer install`

Copiez-collez le fichier `.env`, renommez la copie en `.env.local`

Conservez seulement la ligne 30

`DATABASE_URL="mysql://db_username:db_password@127.0.0.1:3306/db_name?serverVersion=5.7"`

Renseignez vos identifiants à phpMyAdmin, nommez la future base de données.

#### Création de la base de données
`symfony console doctrine:database:create`

#### Migration de la base de données vers votre phpMyAdmin
`symfony console doctrine:migrations:migrate`

Insertion du jeu de données dans votre base grâce au fichier __import.sql__

#### Installation de Symfony Encore

```
composer require symfony/webpack-encore-bundle
yarn install
yarn encore dev
```

#### Découvrez l'application :smiley:
`symfony serve`
