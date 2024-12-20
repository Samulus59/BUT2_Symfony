Projet Symfony de LARANGE Samuel

Les fonctionnalités disponibles sont :
- Création d'article
- Modification d'article
- Suppression d'article
- Création d'utilisateur
- Connexion / Déconnexion
- Affichage d'article en grand

Infos :
- La création, modification et suppression d'article n'est disponible que si un utilisateur est connecté. Dans le cas contraire, seul l'affichage en grand est possible, les boutons concernés n'apparaissent pas, et il est impossible d'accéder aux pages concernées même par l'URL car le chemin est protégé.
- Si non connecté : les boutons de création d'utilisateur, de connexion, et d'affichage d'article apparaissent.
- Si connecté : création utilisateur et connexion disparaissent, et le bouton de déconnexion apparait. Tous les boutons d'actions sur les articles apparaissent également

Utiliser le projet :
- Il faut accéder à la page principale d'article : https://localhost/article
- Un bouton pour accéder à la liste d'articles, qui sert de hub pour toutes les actions est disponible sur la page
- Le volet à gauche sert à naviguer entre les différentes actions facilement.
- Un utilisateur de test a été créé : 
                                        login : email.test@test.com
                                        mot de passe : 123456