[![CI - Tests & Quality](https://github.com/leon38/recipe-sm/actions/workflows/ci.yaml/badge.svg)](https://github.com/leon38/recipe-sm/actions/workflows/ci.yaml)
[![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![Symfony](https://img.shields.io/badge/Symfony-8.1-000000?logo=symfony&logoColor=white)](https://symfony.com/)
[![PHPUnit](https://img.shields.io/badge/PHPUnit-13-3C9CD7?logo=php&logoColor=white)](https://phpunit.de/)
[![PHPStan](https://img.shields.io/badge/PHPStan-enabled-42B883)](https://phpstan.org/)
[![Deptrac](https://img.shields.io/badge/Deptrac-enabled-42B883)](https://deptrac.github.io/deptrac/)

# Recipe API

Une API REST moderne développée avec **Symfony 8** permettant d'importer, gérer et rechercher des recettes de cuisine.

Le projet a été conçu comme un démonstrateur de bonnes pratiques d'architecture backend en s'appuyant sur les principes du **Domain-Driven Design (DDD)** et du **CQRS (Command Query Responsibility Segregation)**.

## Objectifs

* Importer des recettes depuis différentes sources.
* Gérer le cycle de vie complet des recettes.
* Offrir une API performante et facilement extensible.
* Mettre en œuvre une architecture maintenable et testable.

---

## Stack technique

* PHP 8.4
* Symfony 8
* Doctrine ORM
* MySQL
* Symfony Messenger
* PHPUnit
* Behat (à venir)
* PHPStan
* Deptrac
* PHP-CS-Fixer

---

# Architecture

Le projet suit une architecture inspirée de la Clean Architecture.

```
src
├── Application
├── Domain
├── Infrastructure
└── UI
```

Chaque couche possède une responsabilité clairement définie.

## Domain

Le domaine contient toute la logique métier.

On y retrouve notamment :

* les agrégats
* les entités
* les Value Objects
* les interfaces des repositories
* les services métier
* les règles métier

Cette couche est totalement indépendante de Symfony ou de Doctrine.

---

## Application

La couche Application orchestre les cas d'utilisation.

Elle contient notamment :

* Commands
* Command Handlers
* Queries
* Query Handlers
* DTO
* Response Models
* Factories
* Mappers

La couche Application ne contient aucune logique métier mais coordonne les différents composants du domaine.

---

## Infrastructure

La couche Infrastructure contient les implémentations techniques.

Par exemple :

* Doctrine
* Repositories
* Persistance
* Stockage des images
* Bus de commandes
* Bus de requêtes

Le domaine ne dépend jamais de cette couche.

---

## UI

La couche UI expose l'application.

Actuellement :

* API REST Symfony

Cette couche se limite à recevoir les requêtes HTTP et à appeler les handlers appropriés.

---

# CQRS

Le projet applique le pattern CQRS.

Les commandes modifient l'état de l'application.

```
HTTP
    ↓
Controller
    ↓
Command
    ↓
CommandBus
    ↓
CommandHandler
    ↓
Repository
```

Les requêtes ne modifient jamais l'état.

```
HTTP
    ↓
Controller
    ↓
Query
    ↓
QueryBus
    ↓
QueryHandler
    ↓
Repository
    ↓
Response DTO
```

Les contrôleurs restent extrêmement légers et ne contiennent aucune logique métier.

---

# Domain-Driven Design

Le modèle métier est au centre de l'application.

Les principales entités sont notamment :

* Recipe
* Ingredient
* RecipeIngredient
* Step
* Tag
* Category

Les identifiants sont représentés par un **Value Object** (`ValueId`) afin d'éviter la manipulation de chaînes de caractères dans le domaine.

---

# Import de recettes

L'import repose sur une chaîne de traitement composée de plusieurs composants spécialisés.

```
Source
      ↓
Parser
      ↓
DTO
      ↓
Factories
      ↓
Domain
      ↓
Repository
```

Cette approche facilite l'ajout de nouvelles sources de données sans impacter le reste de l'application.

---

# Gestion des images

Les images sont stockées sur le système de fichiers.

Lors de l'import :

```
Image Base64
        ↓
FilesystemImageStorage
        ↓
WebP
        ↓
public/uploads/recipes
```

La base de données ne stocke que l'URL publique de l'image.

Cette approche réduit fortement l'utilisation mémoire et améliore les performances.

---

# Réponses API

L'API ne retourne jamais directement les entités Doctrine.

Chaque endpoint renvoie des **Response DTO**.

```
Entity
    ↓
Mapper
    ↓
Response DTO
    ↓
JSON
```

Cela permet :

* de découpler le domaine de l'API
* d'éviter les problèmes de sérialisation
* de maîtriser précisément les données exposées.

---

# Tests

Le projet est conçu pour être facilement testable.

Les tests s'appuient notamment sur :

* Builders
* Fixtures
* ApplicationTestCase
* Tests unitaires
* Tests fonctionnels

Les services sont injectés via des interfaces afin de faciliter leur isolation lors des tests.

---

# Analyse de code

La qualité du code est contrôlée automatiquement grâce à plusieurs outils :

* **PHPStan** : analyse statique.
* **Deptrac** : validation des dépendances entre les couches.
* **PHP-CS-Fixer** : respect des conventions de codage.

---

# Intégration continue

Une pipeline GitHub Actions valide automatiquement chaque Pull Request.

Les étapes comprennent notamment :

* installation des dépendances
* analyse statique
* vérification de l'architecture
* contrôle du style de code
* exécution des tests PHPUnit
* exécution des tests Behat (à venir).

---

# Évolutions prévues

* ~~Authentification JWT.~~
* ~~Gestion des utilisateurs.~~
* Favoris.
* Planification de repas.
* Génération automatique de listes de courses.
* Recherche avancée (ingrédients, catégories, tags).
* Recherche full-text.
* Import depuis plusieurs plateformes.
* Stockage des images sur S3 ou stockage compatible.
* Cache Redis.
* Documentation OpenAPI complète.

---

# Philosophie

L'objectif de ce projet est autant fonctionnel que technique.

Il sert de support pour expérimenter une architecture moderne autour de Symfony, en mettant l'accent sur :

* la séparation des responsabilités
* un domaine métier riche
* une forte testabilité
* la maintenabilité
* l'évolutivité
* la qualité du code.
