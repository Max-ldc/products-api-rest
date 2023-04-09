## Premier commit assez tard

Après être parti d'un CRUD codé sur index.php avec une chaîne de if et de switch, j'ai refactorisé en classe ProductsCrud, puis je t'ai montré ça (le jeudi 06/04 en distanciel).
Tu m'as proposé d'essayer de continuer la refactorisation en créant des classes Controller, j'ai donc créé ProductController (renommée ProductsApiCrudController avec ta correction).

Avec ta correction j'ai également créé les classes DbInitializer, ExceptionHandlerInitializer, ResponseCode, afin de bien dissocier les fonctionnalités.
J'ai tenté de créer une classe APIException, pour lancer une Exception globale à qui je rentre un message d'erreur voulu et le ResponseCode correspondant. Je récupère cette erreur tout en haut du code, dans ExceptionHandlerInitializer.


Après discussion avec toi avant la fin du cours Vendredi, je sais qu'il faudrait que je gère mieux les erreurs pour les attraper le plus tôt possible, et laisser ExceptionHandlerInitializer pour toute erreur non prévue.
Autre point d'amélioration : Que le controlleur retourne quelque chose à l'index, et qu'on se charge d'afficher/retourner le response code dans index.

A faire également : gérer une 2è ressource. *Facultatif : Essayer de faire une ressource liée aux produits (catégories ?)*

## Deuxième commit : Gestion d'erreurs

**Création de classes adaptées pour chaque Erreur**. Dans le construct de chaque, je définis son code avec la constante ResponseCode correspondante. Je récupère aussi le message (j'ai l'impression qu'en définissant un construct, le construct de base de Exception n'est plus hérité du tout : je dois donc remettre que je récupère le message)
**Création de la classe ExceptionsHandler** et de sa méthode statique sendError pour retourner en JSON l'erreur, son code et son message.

**Dans ProductsCrud** : Lancement d'erreur. J'ai hésité mais j'ai choisi de lancer des erreurs InternalServerError car en cas d'erreur du client, l'erreur serait repérée plus haut. En cas d'erreur dans le crud, il s'agirait plutôt d'un problème de BDD ou de code

