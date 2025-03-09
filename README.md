# SAE - PHP Baratie

# Installation

Dans un premier temps il faut installer les extensions sqlite3 de php pour lancer l’application qui peut être fait à l’aide de cette commande :

```bash
sudo apt install php-sqlite3
```

Il faut ensuite installer composer :

```bash
composer install
```

Changer de répertoire pour se placer dans public : 

```bash
cd public
```

Puis lancer le serveur php

```bash
php -S localhost:8000
```

Vous devriez avoir la page du site qui s’ouvre sur votre navigateur.


# Visiteur
- Barre de recherche pour chercher un restaurant ainsi que filtrer selon certains critères.
- Consultation des informations sur les restaurant
- Consultation des avis/notes donné par les autres utilisateurs.
- Consultation du profil des autres utilisateurs pour voir leur différents avis/notes.

# Utilisateur
- Ajout de notes/commentaires
- Consultation de son profil avec toutes les notes/avis laissés.
