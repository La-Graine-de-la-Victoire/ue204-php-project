# Mini-projet UE204 - Groupe 6

## Synopsis

Le projet JoueTopia a été créé dans le cadre du mini-projet
dans l'**UE 204** du parcours **CVtic** à l'Université de **Limoges**.

Il se comporte d'un système d'authentification avec une confirmation
de compte par TOKEN, un système de panier avec une gestion
basique du stock de chaque produit.

## Connexion à la base de données

| Field    | Value                 |
|----------|-----------------------|
| Host     | localhost / 127.0.0.1 |
| DB Name  | ludotheque            |
| User     | root                  |
| Password | selon la consigne il n'est pas divulgué                  |
| Port     | 8889                  |

**Les informations de connexion à la base de données sont modifiables dans le fichier :**

``/config.php``

## Informations importantes

Si le serveur n'est pas configuré pour l'envoi de mails, ou
que le système d'exploitation bloque l'envoi pour réaliser la
confirmation du compte, suivez la procédure suivante :

````
- Se connecter à la BDD via PhpMyAdmin / autre moyen

- Dans la BDD "ludotheque" aller dans la table "users"

- A la ligne correspondant à votre e-mail  : mettre la colonne "confirmationToken" à NULL

=> Votre compte est maintenant accessible
````