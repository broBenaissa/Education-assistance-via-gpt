<?php

$user_input = $_POST['user_input'] ?? '';
$data_list = $_POST['dataArray'] ?? '';
$exo = $_POST['exo'] ?? '';
$myArray=array();
$myArray = json_decode($data_list);

$chatHistory_system = [
    ["role" => "user", "content" => ". Notes: Réponses courtes.Rependre en francais.
    Evitez donner la reponse direct des question de l éxercice donnez. 
    Encouragez et motivez l étudiant à trouver la solution il même a l aides des questions".$exo ]
];

$blockedWords = ["exercice corrigé", "réponse exacte", "réponse directe", 
"résoudre ce problème", "donne-moi la solution", "solution d'exercice", 
"solution directe", "résoudre exercice", "problème résolu", "réponse précise", 
"exercice résolu", "solution immédiate", "réponse rapide", "trouver solution", 
"résoudre rapidement", "exercice terminé", "solution rapide", "trouver réponse",
"résolution facile", "exercice difficile resol", "problème résolu", "réponse claire",
"résoudre énigme", "trouver solution rapide", "exercice complexe", "solution efficace",
"résoudre rapidement exercice", "problème compliqué", "réponse précise",
"solution immédiate", "trouver solution", "résoudre facilement", "exercice terminé", 
"solution pratique", "résoudre problème rapidement", "problème résolu", "réponse directe",
"solution rapide", "trouver réponse", "résoudre équation", "exercice corrigé", 
"solution immédiate", "trouver solution", "résoudre rapidement", "exercice terminé", 
"problème résolu", "réponse précise", "solution facile", "résoudre problème", 
"trouver réponse", "exercice résolu", "solution pratique", "résoudre rapidement exercice",
"problème complexe", "réponse directe", "solution immédiate", "trouver solution",
"résoudre facilement", "exercice terminé", "solution rapide", "résoudre énigme",
"problème résolu", "réponse claire derict", "trouver réponse", "exercice complexe", 
"solution efficace", "résoudre rapidement exercice", "problème compliqué", 
"réponse précise", "solution pratique", "trouver solution rapide", 
"résoudre facilement", "exercice terminé", "problème résolu", "solution rapide", 
"trouver réponse", "résoudre équation", "exercice corrigé", "solution immédiate",
"trouver solution", "résoudre rapidement", "exercice terminé", "problème résolu",
"réponse précise", "solution facile", "résoudre problème", "trouver réponse",
"exercice résolu", "solution pratique", "résoudre rapidement exercice", 
"problème complexe", "réponse directe", "solution immédiate", "trouver solution",
"résoudre facilement", "exercice terminé", "solution rapide", "résoudre énigme",
"problème résolu", "réponse claire", "trouver réponse", "exercice complexe",
"solution efficace", "résoudre rapidement exercice", "problème compliqué", 
"réponse précise", "solution pratique", "trouver solution rapide", 
"résoudre facilement", "exercice terminé", "problème résolu", 
"solution rapide", "trouver réponse", "résoudre équation", "exercice corrigé",
"solution immédiate", "trouver solution", "résoudre rapidement", 
"exercice resolu", "problème résolu", "réponse précise", "solution facile",
"résoudre problème", "trouver réponse", "exercice résolu", "solution pratique",
"résoudre rapidement exercice", "problème complexe", "réponse directe", 
"solution immédiate", "trouver solution", "résoudre facilement", 
"exercice solvé", "solution rapide", "résoudre énigme", "problème résolu",
"réponse derict", "trouver réponse", "exercice complexe", "solution efficace",
"résoudre rapidement exercice", "problème compliqué", "réponse précise", 
"solution pratique", "trouver solution rapide", "résoudre facilement", 
"exercice a reponse complet", "problème résolu", "solution rapide", "trouver réponse", 
"résoudre équation","resoudr exercice","résolution final",
"résolution de problème","résolution de programme",
"résolution exercice","donne solution","resoudr cet exercice",
"resoudr ce problem","solution simple", "solution d'equation" , 
"solution finale","solution d'un problème",
"solution de forme","solution d exercice","solv this","solution derict",
"solution globale","solution final",
"solution informatique","exercice résolu","exercice corrigé","réponse aux question",
"réponse à cette question",
"réponse exacte","réponse derict","réponse linéaire","solution mere"
];
$allowedWords=["comment resoudr","aidez moi","j ai pas compri",
"j ai de mal a comprendr",
"explique plus","esseyez d expliquer","comment resoudr",
"aidez moi","j ai pas compri",
"j ai de mal a comprendr","explique plus",
"esseyez d expliquer","Comment résoudre ce problème ?",
"Aidez-moi s'il vous plaît.",
"Je n'ai pas compris, pouvez-vous m'expliquer ?",
"J'ai du mal à comprendre cette partie.",
"Pourriez-vous expliquer plus en détail ?",
"Pouvez-vous essayer de m'expliquer à nouveau ?",
"Je suis perdu, pouvez-vous me guider ?",
"Je ne sais pas comment aborder cet exercice.",
"Est-ce que vous pouvez me montrer la marche à suivre ?",
"J'ai besoin d'aide pour résoudre cette équation.",
"Je ne comprends pas cette formule, pouvez-vous l'expliquer ?",
"Comment trouver la solution finale ?",
"Pourriez-vous m'aider à trouver la réponse exacte ?",
"Je ne parviens pas à trouver la réponse.",
"J'ai essayé plusieurs approches, mais aucune ne fonctionne.",
"Je suis bloqué, pouvez-vous me donner un indice ?",
"Comment puis-je résoudre cet exercice mathématique ?",
"J'ai du mal à comprendre .",
"Pouvez-vous m'expliquer la résolution de ce problème ?",
"Je suis confus, pouvez-vous m'aider à résoudre cet exercice ?",
"J'ai besoin d'une méthode de résolution plus efficace.",
"Comment obtenir la solution du problème en utilisant un ordinateur ?",
"Est-ce qu'il y a une solution plus simple à cet exercice ?",
"J'ai trouvé une solution partielle, mais je ne sais pas comment continuer.",
"Pouvez-vous me montrer un exercice résolu similaire ?",
"Je voudrais voir un exercice corrigé pour mieux comprendre.",
"Quelle est la réponse aux questions supplémentaires ?",
"J'ai besoin de la réponse à cette question spécifique.",
"Est-ce qu'il y a une réponse exacte ou cela dépend du contexte ?",
"Pouvez-vous m'expliquer le type de réponse attendu ?",
"Comment obtenir une solution graphique à ce problème ?",
"Je suis intéressé par la solution mathématique pure.",
"Comment obtenir une solution composite pour ce cas ?",
"Quelle est la solution finale recommandée ?",
"J'ai du mal à comprendre la solution informatique proposée.",
"Comment appliquer la méthode de résolution étape par étape ?",
"J'aimerais voir un exemple de résolution numérique.",
"Est-ce qu'il y a une résolution alternative pour cet exercice ?",
"Comment trouver une solution satisfaisante à ce problème ?",
"J'ai besoin de la solution finale pour valider mes calculs.",
"Pouvez-vous m'indiquer comment résoudre cet exercice pratique ?",
"Quelle est la solution idéale pour ce cas d'utilisation ?",
"J'ai besoin d'une résolution rapide pour cette équation complexe.",
"Comment déterminer la solution optimale à ce problème ?",
"Pouvez-vous m'aider à trouver une solution pratique ?"];


$random_messages_short_question=["Bonjour,esseyez de detailler votre question pour 
que je puisse vous aider",
"Salut, pourriez-vous fournir plus de détails sur votre question afin que 
je puisse mieux vous aider ?",
"Bonjour, pouvez-vous donner plus de précisions sur votre question afin que
 je puisse vous apporter l'aide nécessaire ?",
"Salut ! Pour une assistance optimale, pourriez-vous détailler votre question
 afin que je puisse mieux comprendre et vous aider ?",
"Bonjour, n'hésitez pas à fournir des informations supplémentaires sur votre 
question pour que je puisse vous offrir une meilleure aide."];

$random_messages_solution=["Ce n'est pas adéquat de donner directement
 la réponse finale ou la solution.
Mon but est de vous orienter afin que vous puissiez trouver la solution par vous-même.",
"Il n'est pas approprié de fournir la solution complète ou le résultat final.
Mon objectif est de vous accompagner pour que vous puissiez trouver la solution vous-même.",
"Donner immédiatement la réponse finale ou la solution n'est pas la meilleure approche.
Je suis là pour vous guider afin que vous puissiez trouver la solution par vos propres moyens.",
"Il n'est pas recommandé de divulguer directement la solution ou le résultat final.
Mon objectif est de vous aider à trouver la solution de manière autonome.",
"Fournir la solution complète ou le résultat final dès le départ n'est pas approprié.
Je suis là pour vous guider afin que vous puissiez trouver la solution par vous-même.",
"Il n'est pas approprié de vous fournir directement la réponse finale ou la solution. 
Je suis convaincu que vous pouvez le faire par vous-même.",
"Je ne peux pas vous donner directement la réponse finale ou la solution, 
car cela ne serait pas adéquat. Cependant, 
je crois en votre capacité à le faire par vous-même.",
"Je préfère ne pas vous donner directement la réponse finale ou la solution,
 car cela ne serait pas adéquat. Je suis sûr que vous êtes capable de le faire par vous-même.",
"Il ne serait pas approprié de vous fournir la réponse finale ou la solution immédiatement.
 J'ai confiance en votre capacité à résoudre cela par vous-même."];

$french_words="le de un être et à il avoir ne je que ce qui dans en pour par sur pas se plus au
avec tout faire son mettre aide moi bonjour la reponse tu ne faut  autre on dire oui non ou aussi très bien même notre mais leur sans 
si toutefois alors car après avant pendant maintenant ainsi encore jamais toujours peu beaucoup
trop loin près dedans dehors entre chez grâce à cause de depuis jusqu'à contre selon parmi 
certains plusieurs quelques tous chacun personne quelque chose rien quelquefois souvent
rarement toujours maintenant hier aujourd'hui demain bientôt déjà encore malheureusement
heureusement peut-être sûrement vraiment simplement rapidement lentement facilement
difficilement parfaitement totalement partiellement absolument probablement certainement
réellement évidemment apparemment normalement éventuellement essentiellement 
sérieusement d'abord ensuite finalement enfin en effet par exemple pourtant cependant
néanmoins toutefois donc ainsi alors c'est pourquoi en conséquence d'ailleurs à propos
pour cette raison surtout en général généralement fréquemment rarement 
occasionnellement parfois souvent toujours enfin donc ainsi donc parce que pour cette
raison en conséquence par conséquent néanmoins pourtant cependant toutefois d'un
autre côté par contre au contraire malgré en dépit de même si bien que alors que
pendant que puisque puisque étant donné que à condition que à moins que sauf si
plutôt que ou bien soit... soit non seulement... mais aussi tant... que que ce
soit... ou bien ni... ni aussi bien... que autant... que de même que ainsi que 
tel que alors que après que avant que jusqu'à ce que dès que en attendant pendant 
que depuis que quand même quoi que où que qui que lequel que quelque... que n'importe 
qui n'importe quoi n'importe où n'importe quel tout le monde tout ce qui partout tous 
les endroits tout moment tous les moments chaque personne chaque chose chaque endroit 
chaque moment quelque chose quelque part quelqu'un personne aucun tout le chaque quelque
plusieurs beaucoup quelques aucun tout rien certains plusieurs tous aucun autre même plusieurs
quelques-uns beaucoup peu trop tous les chaque un deux trois quatre cinq six sept huit neuf 
dix onze douze treize quatorze quinze seize dix-sept dix-huit dix-neuf vingt trente quarante 
cinquante soixante soixante-dix quatre-vingts quatre-vingt-dix cent mille";

$random_messages_language=[
"Je ne suis disponible qu'en français. Veuillez m'expliquer ce dont vous avez besoin pour
que je puisse vous aider.",
"Pour une meilleure compréhension, je peux vous assister uniquement en français.
Pouvez-vous me donner des détails sur votre requête ?",
"La communication se fera uniquement en français. 
Veuillez me fournir des informations sur votre demande afin que je puisse vous assister.",
"Il est préférable de m'expliquer votre besoin en français,
 car je ne comprends que cette langue.
Comment puis-je vous aider ?",
"Ma compréhension est limitée à la langue française. 
Pouvez-vous me décrire ce dont vous avez besoin pour que je puisse vous aider ?",
"Salut, je suis désolé, mais je peux seulement communiquer en français.
Pouvez-vous me parler de votre besoin pour que je puisse vous aider ?",
"Salut, je suis désolé, mais je peux seulement communiquer en français. 
Pouvez-vous me parler de votre besoin pour que je puisse vous aider ?",
"Salutations, je regrette de vous informer que je ne peux vous comprendre qu'en français. 
Pourriez-vous me fournir des informations sur votre demande pour que 
je puisse vous apporter mon assistance ?",
"Salut, désolé pour la limitation linguistique, je peux seulement répondre en français.
Pourriez-vous me donner plus d'informations sur votre demande afin que je puisse 
vous aider efficacement ?"
];

?>