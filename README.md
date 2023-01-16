# Stock Management

Conversion d'une de mes premières applications desktop Windows en C# lors de mes débuts dans le développement informatique. A cette époque, seule la partie back avait été développer.

## Description

L'application est divisé en deux parties, une partie front pour les utilisateurs (qui sera développé en React) et une partie back (qui est cette application, elle sera développé en Symfony 5.4).

La partie back est une application de gestion de product et des stocks de la société, un ERP. Elle permets d'avoir un suivi sur les commandes en cours et de gérer le ravitaillement des produits par ces sociétés partenaires. Cette application n'a que pour but d'une utilisation interne.

La partie front sera une plateforme de vente de produit aux utilisateurs. C'est une application apart à la partie back mais pourtant connecté.

## Installation

Cette commande installera toutes les dépendances que cette solution web à besoin pour fonctionner.

```bash
    composer install
```

Cette commande est nécessaire pour mettre à jour toutes les dépendances. Elle est aussi très importante pour les mise à jours de sécurité.

```bash
    composer update
```

## Base de données

Créer la database :
```bash
    symfony console doctrine:database:create
```

Générer les tables (pour la database) :
```bash
    symfony console make:migration
```

Sauvegarder les modifications dans la database :
```bash
    symfony console doctrine:migrations:migrate
```

Mise à jour forcée de la base de données
```bash
    symfony console doctrine:schema:update --force
```

### Generation du mot de passe
```bash
    symfony console security:hash-password
```

## Configuration serveur

```bash
    composer require symfony/apache-pack 
```

Les configurations restantes (pour la mise en production) seront à faire à travers ce lien https://symfony.com/doc/current/setup/web_server_configuration.html

## Compression du style SASS en CSS

```bash
    sass --style compressed index.scss:index.css
```