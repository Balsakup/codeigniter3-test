# Installation du projet

```shell
docker compose up -d

# Installation des dépendances
composer install --ignore-platform-reqs --no-scripts

# Lancer les migrations
docker compose exec php php index.php database migrate

# Seeder la base de données
# Ajoute 10000 utilisateurs sur la bdd
docker compose exec php php index.php database seed
```

# Suppression des utilisateurs

Le cron supprime les utilisateurs ne s'étant jamais connecté et ayant créé leur compte il y a plus de 36 mois OU les
utilisateurs s'étant connecté pour la dernière fois il y a plus de 36 mois.

A ajouter dans le crontab

```shell
0 3 * * * php /app/index.php cron delete_old_users > /app/cron.log
```

# API Specs

- Admin
    - [Utilisateurs](/doc/admin/api/v1/users)
- Public
    - [Utilisateurs](/doc/public/api/v1/users)
