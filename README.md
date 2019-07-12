# Projet PromethIA

Le but initial de ce projet était d'apprendre le fonctionnement d'un réseau de neurones en le développant entièrement en PHP.
Mais pour qu'un projet de ce type porte ses fruits, il faut être confronté à des problématiques réelles. Il faut donc un sujet d'application concrète.
Le sujet choisi pour ce projet, et qui lui a donné son nom : la prédiction des incendies dans les Bouches-du-Rhône.
Et si une IA était capable d'apprendre à les anticiper ?


## Historique

L'histoire de ce projet débute le 11 juin 2019, à la réception d'un email sur ma boîte pro annonçant le concours innovation de l'été de l'agence : déveloper et entraîner une intelligence artificielle capable d'augmenter la résolution des images.

On entend beaucoup parler du concept d'intelligence artificielle, de _machine learning_ et de réseaux de neurones.
Personnellement, je ne maîtrise pas leurs principes de fonctionnement. Que se passe-t-il sous le capot ?
Je me dis alors que cela peut être une bonne occasion de monter en compétences sur le sujet ! L'idée de ce projet est donc née petit à petit, au cours de mes pauses repas ou pendant des temps libres.


### Prise d'info

Première étape, comprendre ce qu'est un réseau de neurones !
Je fais donc quelques recherches. Certaines plus fructueuses que d'autres.

Finalement, je tombe sur ces articles qui m'ont réellement aidé à comprendre ce qu'est un réseau de neurones et la façon dont il fonctionne :

- [Machine Learning for Beginners: An Introduction to Neural Networks](https://victorzhou.com/blog/intro-to-neural-networks/)
- [CNNs, Part 1: An Introduction to Convolutional Neural Networks](https://victorzhou.com/blog/intro-to-cnns-part-1/)
- [CNNs, Part 2: Training a Convolutional Neural Network](https://victorzhou.com/blog/intro-to-cnns-part-2/)

Lors de mes recherches, je découvre également les concepts suivants :

- SRCNN : Super Resolution Convolutional Network
- SRGAN : Super Resolution Generative Adversarial Network

Concepts qui collent pas mal au sujet du concours ! Qui collent tellement, que je finis par trouver beaucoup d'implémentations toutes faites, avec Tensorflow ou Keras, de ces architectures de réseaux de neurones...

Le résultat de mes recherches correspond finalement **trop** au sujet du concours, qui va se résumer à refaire quelque chose qui existe déjà. Je vais donc être très tenté de m'inspirer de l'existant. Mais est-ce que cela m'apportera réellement une meilleure connaissance de ce qu'est un réseau de neurones ? Pas forcément... D'autant plus que les concepts manipulés dans une implémentation de SRGAN dépassent de loin mes connaissances actuelles en la matière.

Je décide donc de ne pas participer à ce concours, mais de transformer le sujet en veille perso : je vais coder, _from scratch_, un réseau de neurones.


### Mon premier réseau de neurones

_Pourquoi redévelopper un réseau de neurones à partir de rien ? Ça fait beaucoup "je réinvente la roue"._

Tout simplement parce que c'est de cette façon que j'apprendrai réellement comment cela fonctionne !
Je décide donc de me lancer dans ce petit projet : développer un réseau de neurones en PHP.

_Pourquoi PHP ? Ce n'est pas du tout le langage le plus adapté pour un tel projet !_

Effectivement. Le langage le plus adapté serait clairement Python. Seul bémol : je ne connais pas ce langage. L'apprendre est, en soi, un projet de veille qui me plairait bien, un de ces jours. Mais ce n'est pas le sujet actuel ! Ce que j'essaie de faire, là, c'est de comprendre les réseaux de neurones et les concepts d'apprentissage machine. Perdre du temps à apprendre un nouveau langage ne rentre pas dans le cadre de ce projet, ou le ralentirait beaucoup. PHP est un langage que je maîtrise très bien. Et d'après mes lectures, le développement d'un réseau de neurones ne demande pas des fonctionnalités très exotiques : PHP fera tout à fait l'affaire !

Quelques heures de travail plus tard, une feuille A4 recouverte de calculs de dérivées partielles et la révision de lointains souvenirs de dérivations de terminale, j'obtiens [mon premier réseau de neurones fonctionnel](https://github.com/splanard/my-first-neural-network). Trois classes, quelques fonctions utilitaires. Rien d'extraordinaire. Il est basé sur le premier article cité plus haut et ne fait pas grand chose d'utile pour le moment : il identifie si un couple poids/taille correspond à un homme ou une femme, en se basant sur un échantillon de... 4 entrées ! Pas fantastique. 

Cependant, la partie intéressante : ce réseau de neurones **apprend** à réaliser cette tâche ! 

- Les mécanismes _feed forward_ et _back propagation_ sont implémentés et ils fonctionnent, 
- j'ai un réseau avec trois _layouts_ : une entrée, un caché et une sortie,
- je suis en mesure d'augmenter ou de réduire le nombre de noeuds de chaque _layout_.

Pas besoin de plus pour le moment ! :-)


### Cas d'application réel

En parallèle de cette première implémentation, je cherche un cas d'utilisation concret d'un réseau de neurones. Que pourrais-je bien lui faire apprendre ?

La plus grande difficulté réside dans la source de données : je dois choisir un cas d'application pour lequel je peux récupérer un grand nombre de données d'input à fournir au réseau. Et des bases de données ouvertes, avec des sujets d'application pour une IA, il n'y en a pas des millions. D'autant plus que mon réseau est très basique : il ne peut traiter que des nombres, pas des images. Le _machine learning_ étant très appliqué à la reconnaissance d'images, ces sources de données sont bien plus fréquentes.

Finalement, un jour, je ne sais plus exactement comment, je tombe sur [la base de données Prométhée](http://www.promethee.com/incendies), qui recence les incendies sur le pourtour méditerranéen. Il me vient l'idée suivante : **si j'essayais de faire prédire à mon IA les incendies dans les Bouches-du-Rhône ?!**

Ça y est, j'ai un sujet !

Par où commencer maintenant ? Ah, oui, les données ! Il faut que je puisse fournir à mon réseau des données avec lesquelles il pourra apprendre à estimer un risque d'incendie. Concernant la sortie, "y a-t-il eu un incendie ou non ?", j'ai la base de données Prométhée. Les critères d'entrée semblent évidents : des données météo ! Je finis par trouver un moyen de récupérer [les données SYNOP publiques des stations MétéoFrance](https://donneespubliques.meteofrance.fr/?fond=produit&id_produit=90&id_rubrique=32). Et en particulier celles qui m'intéressent le plus : celles de la station de Marignane, dans les Bouches-du-Rhône.

Après consultation du contenu de ces données météo et un moment de réfléxion, je construis la structure de mon jeu de données d'entrée :

- le mois de l'année (1-12)
- le jour (1-31)
- le jour de la semaine (lundi-dimanche) (_peut-être que les pyromanes ont leur jour de prédilection, qui sait !_)
- la température minimale sur la journée
- la température maximale sur la journée
- la température minimale moyenne sur les 7 jours précédents _(de façon tout à fait arbitraire)_
- la température maximale moyenne sur les 7 jours précédents _(de façon tout à fait arbitraire)_
- la vitesse du vent minimale sur la journée
- la vitesse du vent maximale sur la journée
- la vitesse du vent minimale moyenne sur les 7 jours précédents _(de façon tout à fait arbitraire)_
- la vitesse du vent maximale moyenne sur les 7 jours précédents _(de façon tout à fait arbitraire)_
- la vitesse minimale du vent en rafales sur la journée  
- la vitesse maximale du vent en rafales sur la journée
- l'humidité minimale sur la journée (%)
- l'humidité maximale sur la journée (%)
- les précipitations de la journée (mm)
- les précipitations des 14 jours précédents _(de façon tout à fait arbitraire)_
- les précipitations du mois précédent
- les précipitations des 6 mois précédents
- y a-t-il eu un feu ce jour-là ?
- optionnellement, combien de départ de feu y a-t-il eu ce jour-là ?
- optionnellement, la surface total brûlée ce jour-là

J'utilise MySQL pour :

- Charger les données brutes issues de Prométhée et de MétéoFrance. J'ai d'ailleurs développé des [scripts PHP](./resources/raw_data/meteo) pour charger ces dernières, qui ne sont disponibles que mois par mois.
- Constituer un jeu de données en croisant les deux et le normaliser pour pouvoir le passer au réseau de neurones : [voir les scripts dédiés](./resources/sql).

Le 21 juin, la première version du jeu de données normalisé est prête : je l'exporte en CSV. Mon jeu de données d'entraînement sera constitué des données de 2010 à 2017. Les données de 2018 constitueront mon jeu de données de test. 


### Implémentation et résultats

La première tâche est d'adapter mon premier réseau de neurones à ce jeu de données. C'est assez rapide.

J'ajoute rapidement un système pour exporter la configuration du réseau dans un fichier et le ré-importer, pour être en mesure de l'entraîner de jour en jour.

Je remarque que, parfois, lorsque la valeur du taux d'apprentissage est trop élevée, la perte se met à osciller lors de l'entraînement. 
Alors que mon but est qu'elle baisse en continu. J'ajoute donc un mode d'entraînement automatique qui divise le taux d'entraînement par deux à chaque fois que la perte oscile trop.

Malgré tout, les tests avec des données 2018 restent peu concluants.
Sur le plus gros incendie de 2018, j'obtiens un pourcentage de 81.7%, ce qui n'est pas fantastique.

Je me rends également compte qu'en utilisant la fonction de perte MSE classique, les faux positifs (lorsque l'IA prédit un incendie alors qu'il n'y en a pas eu), ont le même impact sur la perte que les faux négatifs (lorsque l'IA ne prédit pas d'incendie alors qu'il y en a eu un).
Or, dans ce cas d'application, un résultat à 50% sur un jour sans incendie devrait être moins impactant qu'un résultat à 80% un jour où il y a effectivement eu un incendie. J'essaie de faire évaluer un risque : il est normal que, certains jours, le risque ne soit pas nul mais qu'il n'y a pour autant pas eu d'incendie.
J'essaie donc à plusieurs reprises d'adapter ma fonction de perte. Avec plus ou moins de succès. La version qui semble le mieux fonctionner pour le moment est la suivante :

    SOMME( (1 + 3*y_true) * (y_true - y_pred)² )

De cette façon, les éléments du jeu de données pour lesquels il y a eu un incendie (`y_true` vaut 1) ont 4 fois plus d'impact sur la perte que ceux où il n'y a pas eu d'incendie (`y_true` vaut 0). Cette fonction reste très imparfaite, mais je manque de connaissance en la matière pour l'améliorer.

À la base, j'avais utilisé une librairie PHP qui proposait une fonction de génération de nombres aléatoires suivant une distribution normale pour initialiser la valeur des _weights_ et _bias_ de chaque neurone, comme préconisé dans la littérature sur le sujet. Je me suis rapidement aperçu que cette librairie ne fonctionnait pas. Je l'ai remplacé par la fonction PHP `mt_rand`, beaucoup plus classique.

Je finis par trouver (merci Stackoverflow) une fonction alternative qui permettait de simuler une loi normale. Après avoir lancé la génération de 100000 nombres et vérifié que leur distribution formait bien une gaussienne, je remplace l'utilisation de `mt_rand` par cette nouvelle fonction.


## Fonctionnement

### Pré-requis

Avoir PHP 7 installé et disponible dans le `PATH`.

### Installation

Simplement récupérer les sources du projet.

### Utilisation

Avec une invite de commandes, se placer dans le répertoire des sources du projet.

Pour initialiser un réseau de neurones :

    php init.php 19 19

Les jeux de données présents dans les sources possèdent 19 valeurs d'entrée.

Pour entraîner le réseau de neurones sur les données 2010 à 2017 (`resources/train.csv`) :

    php train.php help

Pour tester le réseau de neurones sur les données 2018 (`resources/test.csv`) :
    
    php test.php

