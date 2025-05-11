# PIDEV-3A65-Enchére-Web-Symfony : projet académique

## 📝 Présentation

Cette application de bureau a été développée dans le cadre du module PIDEV 3A à Esprit.
une application web et desktop dédiés à l'achat de fruits et légumes frais en ligne, intégrant une fonctionnalité d'enchèreslication et un site web dédiés à l'achat de fruits et légumes frais en ligne, intégrant une fonctionnalité d'enchères
## 🎯 Fonctionnalités

- Gestion des entités : utilisateurs, réclamations, réponses , categories , panier
- Gestion des utilisateurs
- Système d’enchères
- Gestion des réclamations et réponse
- Gestion de produit et catégorie
- Gestion de commandes et de panier
- Gestion des livraisons
- API REST en format JSON
- Utilisation de Doctrine ORM pour la persistance
- Système d’authentification des utilisateurs avec rôles
- Relations entre entités (User, Reclamation, Reponse)

## 🧰 Technologies utilisées

- PHP 8.2  
- Symfony 6.4  
- Doctrine ORM  
- MySQL  
- API Platform 

## ▶️ Lancement

```bash
git clone https://github.com/toumi98/Projet-PII-integration.git
cd Projet-PII-integration
composer install
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
symfony serve
