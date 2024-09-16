# Lister les utilisateurs

## Paramètres

`GET /admin/api/v1/users`

| Paramètre   | Valeur               | Description                                                  |
|-------------|----------------------|--------------------------------------------------------------|
| `page`      | Entier               | Numéro de la page à requêter                                 |
| `per_page`  | Entier               | Nombre d'élément à afficher par page                         |
| `order_by`  | Chaîne de caractères | Colonne sur laquelle trier les résultats                     |
| `order_dir` | Chaîne de caractères | Sens de tri (ASC ou DESC)                                    |
| `search`    | Chaîne de caractères | Texte à rechercher sur la table (firstname, lastname, email) |

<details>
<summary>Exemple CURL</summary>

```shell
curl --request GET \
  --url 'http://localhost/admin/api/v1/users?page=2&order_by=email&order_dir=DESC&search=John'
```

</details>

## Réponse (example)

```json
{
    "success": true,
    "total": 10001,
    "page_count": 1001,
    "next_page_url": "admin/api/v1/users?page=2",
    "prev_page_url": null,
    "items": [
        {
            "id": "1",
            "firstname": "Nathalie",
            "lastname": "Berger",
            "email": "hdossantos@joubert.fr",
            "address_street": "boulevard Théodore Royer",
            "address_postcode": "85005",
            "address_city": "Charles",
            "address_country": "São Tomé et Príncipe (Rép.)",
            "status": "professional",
            "last_connection": "2019-10-27 06:04:13",
            "created_at": "2019-07-27 06:04:13",
            "updated_at": "2024-09-15 14:47:08"
        },
        ...
    ]
}
```

# Ajouter un utilisateur

`POST /admin/api/v1/users`

| Paramètre          | Valeur               | Description                                                          |
|--------------------|----------------------|----------------------------------------------------------------------|
| `firstname`        | Chaîne de caractères | Prénom de l'utilisateur                                              |
| `lastname`         | Chaîne de caractères | NOM de l'utilisateur                                                 |
| `email`            | Chaîne de caractères | Adresse email de l'utilisateur (doit être un email valide et unique) |
| `address_street`   | Chaîne de caractères | Rue de l'adresse de l'utilisateur                                    |
| `address_postcode` | Chaîne de caractères | Code postal de l'adresse de l'utilisateur                            |
| `address_city`     | Chaîne de caractères | Ville de l'adresse de l'utilisateur                                  |
| `address_country`  | Chaîne de caractères | Pays de l'adresse de l'utilisateur                                   |
| `status`           | Chaîne de caractères | Statut de l'utilisateur (doit être `individual` ou `professional`)   |

<details>
<summary>Exemple CURL</summary>

```shell
curl --request POST \
  --url http://localhost/api/v1/users \
  --header 'Content-Type: application/x-www-form-urlencoded' \
  --data firstname=John \
  --data lastname=DOE \
  --data email=john.doe@example.com \
  --data 'address_street=Château de la Turmelière' \
  --data address_postcode=49530 \
  --data 'address_city=Orée d'\''Anjou' \
  --data address_country=France \
  --data status=individual
```

</details>

## Réponses

### Erreur de validation

Code HTTP : `400`

```json
{
    "success": false,
    "message": "Validation error.",
    "errors": {
        "firstname": "The Firstname field is required.",
        "lastname": "The Lastname field is required.",
        "email": "The Email field is required.",
        "address_street": "The Address street field is required.",
        "address_postcode": "The Address postcode field is required.",
        "address_city": "The Address city field is required.",
        "address_country": "The Address country field is required.",
        "status": "The Status field is required."
    }
}
```

### Utilisateur ajouté

Code HTTP : `200`

```json
{
    "success": true
}
```

# Modifier un utilisateur

`PUT /admin/api/v1/users/<id>`

| Paramètre          | Valeur               | Description                                                          |
|--------------------|----------------------|----------------------------------------------------------------------|
| `firstname`        | Chaîne de caractères | Prénom de l'utilisateur                                              |
| `lastname`         | Chaîne de caractères | NOM de l'utilisateur                                                 |
| `email`            | Chaîne de caractères | Adresse email de l'utilisateur (doit être un email valide et unique) |
| `address_street`   | Chaîne de caractères | Rue de l'adresse de l'utilisateur                                    |
| `address_postcode` | Chaîne de caractères | Code postal de l'adresse de l'utilisateur                            |
| `address_city`     | Chaîne de caractères | Ville de l'adresse de l'utilisateur                                  |
| `address_country`  | Chaîne de caractères | Pays de l'adresse de l'utilisateur                                   |
| `status`           | Chaîne de caractères | Statut de l'utilisateur (doit être `individual` ou `professional`)   |

<details>
<summary>Exemple CURL</summary>

```shell
curl --request PUT \
  --url http://localhost/admin/api/v1/users/10001 \
  --header 'Content-Type: application/x-www-form-urlencoded' \
  --data email=john.doe@example.com
```

</details>

## Réponses

### Utilisateur non existant

Code HTTP : `404`

```json
{
    "success": false,
    "message": "User '10002' not found."
}
```

### Erreur de validation

Code HTTP : `400`

```json
{
    "success": false,
    "message": "Validation error.",
    "errors": {
        "email": "The Email field must contain a unique value."
    }
}
```

### Utilisateur modifié

Code HTTP : `200`

```json
{
    "success": true
}
```
