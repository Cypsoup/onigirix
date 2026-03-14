# TODO

## User

- Check force du mdp

## Dashboard Admin

## Code Friendly

- Commenter chaque code ?

## Arborescence

Onigirix/
|   .DS_Store
|   commandeUser.php
|   create_order.php
|   dashboardAdmin.php
|   dashboardUser.php
|   index.php       # Page principal qui pose la structure (sidebar, appel à d'autres pages via GET, ...)
|   notes.txt
|   README.md
|   todo.md
|   update_status.php
|
+---actions     # Logique de traitement - Actions sur la base de données : pas de Render
|       archiveRecipe.php
|       checkTrigramme.php
|       deleteRecipe.php
|       processCreateUser.php
|       processEditPassword.php
|       processEditUser.php
|       processLogin.php
|       processLogout.php
|       saveRecipe.php
|
+---api
|       submit-order.php
|
+---archive
|       sidebar.php
|
+---config
|       db.php
|
+---css
|       style.css
|
+---db
|       .DS_Store
|       onigirix.sql
|
+---images
|   |   .DS_Store
|   |   logo.jpg
|   |   logo.png
|   |   onigiri.png
|   |
|   \---recipeImages
+---includes
|       functions.php
|
+---js      # Tous les fichiers JS - Tout passe par des listeners
|       formHandler.js      # Gestion des formulaires pour éviter de recharger la page (unicité du trigramme et mots de passe)
|       listeners.js        # Initialisation de tous les listeners
|       main.js             # Fichier central
|       orderHandler.js     # Gestion des commandes pour ne pas recharger la page (ouverture du panel admin pour commander)
|       recipe.js           # Gestion de la page des recettes (Archivage)
|       utils.js
|
+---orders
|       order.php
|       orderRenderer.php
|
+---pages
|       page_createUser.php
|       page_dashboardAdmin.php
|       page_editRecipe.php
|       page_editUser.php
|       page_errorAccess.php
|       page_errorPage.php
|       page_home.php
|       page_login.php
|       page_menu.php
|       page_userProfile.php
|
+---recipes
|       recipe.php
|       recipeRenderer.php
|
+---users
|       printForms.php
|       users.php
|       usersRenderer.php
|
\---utils
        flash.php
        pageGeneration.php
