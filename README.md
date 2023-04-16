## [Premier commit assez tard](https://github.com/Max-ldc/products-api-rest/tree/aed15e31288be71f700db512789ff6300ffb7deb)

Après être parti d'un CRUD codé sur index.php avec une chaîne de if et de switch, j'ai refactorisé en classe ProductsCrud, puis je t'ai montré ça *(le jeudi 06/04 en distanciel)*.
Tu m'as proposé d'essayer de continuer la refactorisation en créant des classes Controller, j'ai donc créé ProductController *(renommée ProductsApiCrudController avec ta correction)*.

Avec ta correction j'ai également créé les classes DbInitializer, ExceptionHandlerInitializer, ResponseCode, afin de bien dissocier les fonctionnalités.
J'ai tenté de créer une classe APIException, pour lancer une Exception globale à qui je rentre un message d'erreur voulu et le ResponseCode correspondant. Je récupère cette erreur tout en haut du code, dans ExceptionHandlerInitializer.


Après discussion avec toi avant la fin du cours Vendredi, je sais qu'il faudrait que je gère mieux les erreurs pour les attraper le plus tôt possible, et laisser ExceptionHandlerInitializer pour toute erreur non prévue.
Autre point d'amélioration : Que le controlleur retourne quelque chose à l'index, et qu'on se charge d'afficher/retourner le response code dans index.

A faire également : gérer une 2è ressource. *Facultatif : Essayer de faire une ressource liée aux produits (catégories ?)*

## [Deuxième commit : Gestion d'erreurs](https://github.com/Max-ldc/products-api-rest/tree/2dd60b8b221a5b172f3502ccb038b7a3e8b5f504)

**Création de classes adaptées pour chaque Erreur**. Dans le construct de chaque, je définis son code avec la constante ResponseCode correspondante. Je récupère aussi le message (j'ai l'impression qu'en définissant un construct, le construct de base de Exception n'est plus hérité du tout : je dois donc remettre que je récupère le message)
**Création de la classe ExceptionsHandler** et de sa méthode statique sendError pour retourner en JSON l'erreur, son code et son message.

**Dans ProductsCrud** : Lancement d'erreur. J'ai hésité mais j'ai choisi de lancer des erreurs InternalServerError car en cas d'erreur du client, l'erreur serait repérée plus haut. En cas d'erreur dans le crud, il s'agirait plutôt d'un problème de BDD ou de code

## [3è commit : Gestion d'erreurs](https://github.com/Max-ldc/products-api-rest/tree/8bfd46ea647bd465f61078f62e7f416449ba034d)

**Dans ProductsApiCrudController**, ajout des try-catch à chaque tentative d'accès à la BDD. Amélioration du checkHttpMethod -> division en 2 parties, après avoir vérifié si il s'agissait d'une opération sur la collection ou sur une ressource

Retour de **ExceptionHandlerInitializer** en Gestion d'erreur globale pour une erreur non prévue

## [4è commit : Héritage Crud et Controller](https://github.com/Max-ldc/products-api-rest/tree/8a09a237d52a91fe6b1cc3ee08ac8e5d6d2b16f4)

Ajout d'une classe abstraite ApiCrud et d'une classe abstraite ApiCrudController pour préparer l'arrivée d'autres ressources. J'ai remonté les méthodes de check d'infos dans ApiCrudController pour qu'elles soient disponibles pour toutes les ressources.
Les autres méthodes ont été mis en abstraites pour qu'elles soient bien définies dans les classes enfants de ApiCrud et ApiCrudController.

## [5è commit : Ajout d'une ressource categories](https://github.com/Max-ldc/products-api-rest/tree/e97ef98966f785b9f71f0806c2facc5722dcb914)

**Ajout de la gestion d'une ressource categories**, avec un id, un nom et une description nullable.
Création de CategoriesApiCrudController, héritant de ApiCrudController, et de ApiCategoriesCrud, héritant de ApiCrud.
Gestion des 2 ressources sur index.php et envoi d'erreur si ça n'est pas l'une d'elles

*Prochaines étapes :* 
- Tenter la refactorisation des méthodes handle qui se ressemblent beaucoup entre les ressources *(il y a juste la condition d'URI qui change)*
- Lier les 2 ressources : chaque produit peut avoir 0 ou 1 catégories. Et une catégorie peut avoir 0 ou plusieurs produits (clé étrangère côté produits)
