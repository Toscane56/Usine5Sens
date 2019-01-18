# Installation

## API

Dupliquer le fichier `api/config.sample.php` en `api/config.php` puis modifier les identifiants MySQL dans le fichier créé.

Importer le contenu du fichier `api/database.sql` dans votre base de données MySQL.

## App

Exécuter `npm install` dans le sous-dossier `app`.

Dupliquer le fichier `app/src/config.sample.js` en `app/src/config.js` puis modifier l'URL vers l'API dans le fichier créé.

# Tests

Exécuter le fichier `api/tests/index.php` depuis la console ou le navigateur web.

# Documentation

Rendez-vous sur l'URL `api/public/index.php` pour voir la doc de l'API.

# Exécution

Faire un `npm start` dans le sous-dossier `app` pour lancer le projet !