# Meeting — Guide de déploiement (Production)

Application Symfony conteneurisée (PHP + Apache, MySQL, phpMyAdmin).

## Prérequis serveur

- Docker et Docker Compose installés
- Nom de domaine pointant vers le serveur (DNS configuré)
- Ports 80/443 ouverts (reverse proxy / certificat SSL à gérer en amont, ex. Nginx + Certbot, ou Traefik)

## 1. Récupérer le projet

```bash
git clone https://github.com/sakagnir/Projet-CDA
cd meeting
```

## 2. Configuration des variables d'environnement

### `.env` (racine du projet, lu par Docker Compose)

Ne jamais committer ce fichier en production — créer un fichier propre au serveur :

```
MYSQL_ROOT_PASSWORD=sCwxID70KkbZKEXznsIa
MYSQL_DATABASE=meeting
```

### `.env.local` (lu par Symfony)

```
APP_ENV=prod
APP_SECRET=<secret-généré>
DATABASE_URL="mysql://root:<mot-de-passe-fort>@database:3306/meeting?serverVersion=8.0&charset=utf8mb4"
```

Générer un `APP_SECRET` :
```bash
php -r "echo bin2hex(random_bytes(16));"
```

## 3. Adapter le `docker-compose.yml` pour la prod

Points à changer par rapport à l'environnement de dev :

- Ne pas monter tout le projet en volume (`./:/var/www/html`) : construire une image figée avec le code inclus (via le `Dockerfile`), pour éviter d'exposer les fichiers sources modifiables en direct.
- Ne pas exposer publiquement le port MySQL (`3306:3306`) ni phpMyAdmin, sauf besoin ponctuel — retirer ces mappings de ports ou les restreindre à une IP interne.
- Passer `APP_ENV=prod` dans les variables d'environnement du service `php`.

Exemple simplifié :

```yaml
services:
  php:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: symfony_app
    restart: unless-stopped
    depends_on:
      - database
    environment:
      APP_ENV: prod
      DATABASE_URL: "mysql://root:${MYSQL_ROOT_PASSWORD}@database:3306/meeting?serverVersion=8.0&charset=utf8mb4"

  database:
    image: mysql:8.0
    container_name: symfony_db
    restart: unless-stopped
    environment:
      MYSQL_ROOT_PASSWORD: ${MYSQL_ROOT_PASSWORD}
      MYSQL_DATABASE: meeting
    volumes:
      - db_data:/var/lib/mysql

volumes:
  db_data:
```

## 4. Build et lancement

```bash
docker-compose up -d --build
```

## 5. Installation des dépendances et préparation de l'application

```bash
docker-compose exec php composer install --no-dev --optimize-autoloader
docker-compose exec php php bin/console doctrine:migrations:migrate --no-interaction
docker-compose exec php php bin/console cache:clear --env=prod
docker-compose exec php php bin/console assets:install public --env=prod
```

Si le projet utilise Webpack Encore, builder les assets avant ou pendant le build de l'image :
```bash
npm install
npm run build
```

## 6. Reverse proxy et HTTPS

Placer un reverse proxy (Nginx, Traefik, Caddy...) devant le conteneur `php` pour :
- gérer le certificat SSL (Let's Encrypt),
- rediriger le port 80/443 public vers le port interne du conteneur.

## 7. Vérifications post-déploiement

- Le site répond bien en HTTPS sur le nom de domaine
- Les migrations sont bien passées : `docker-compose exec php php bin/console doctrine:migrations:status`
- Les logs ne remontent pas d'erreur : `docker-compose logs -f php`

## Commandes utiles

| Action | Commande |
|---|---|
| Voir les logs | `docker-compose logs -f` |
| Redémarrer un service | `docker-compose restart php` |
| Arrêter le projet | `docker-compose down` |
| Sauvegarder la base | `docker-compose exec database mysqldump -u root -p meeting > backup.sql` |
