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
Sur le plus gros incendie de 2018, j'obtiens un risque d'incendie de 81.7%, ce qui n'est pas fantastique.

Je me rends également compte qu'en utilisant la fonction de perte MSE classique, les faux positifs (lorsque l'IA prédit un incendie alors qu'il n'y en a pas eu) ont le même impact sur la perte que les faux négatifs (lorsque l'IA ne prédit pas d'incendie alors qu'il y en a eu un).
Or, dans ce cas d'utilisation, un résultat à 50% sur un jour sans incendie devrait être moins impactant qu'un résultat à 80% un jour où il y a effectivement eu un incendie. J'essaie de faire évaluer un risque : il est normal que, certains jours, le risque ne soit pas nul mais qu'il n'y ait pour autant pas eu d'incendie.
J'essaie donc à plusieurs reprises d'adapter ma fonction de perte. Avec plus ou moins de succès. La version qui semble le mieux fonctionner pour le moment est la suivante :

    SOMME( (1 + 3*y_true) * (y_true - y_pred)² )

De cette façon, les éléments du jeu de données pour lesquels il y a eu un incendie (`y_true` vaut 1) ont 4 fois plus d'impact sur la perte que ceux où il n'y a pas eu d'incendie (`y_true` vaut 0). Cette fonction reste très imparfaite, mais je manque de connaissance en la matière pour l'améliorer.

À la base, j'avais utilisé une librairie PHP qui proposait une fonction de génération de nombres aléatoires suivant une distribution normale pour initialiser la valeur des _weights_ et _bias_ de chaque neurone, comme préconisé dans la littérature sur le sujet. Je me suis rapidement aperçu que cette librairie ne fonctionnait pas. Je la remplace d'abord par la fonction PHP `mt_rand`, beaucoup plus classique. Je finis par trouver (merci Stackoverflow) une fonction alternative qui permettait de simuler une loi normale. Après avoir lancé la génération de 100000 nombres et vérifié que leur distribution formait bien une gaussienne, je remplace l'utilisation de `mt_rand` par cette nouvelle fonction.

Somme toute, les prédictions de la meilleure IA que j'ai pu entraîner avec ces jeux de données ne sont pas franchement satisfaisantes. Ci-dessus, les prédictions réalisées par cette IA pour l'année 2018, avec un taux final de faux négatif de 0.1931 :

| Date       | Risque prédit | Surface brûlée |
|------------|---------------|----------------|
| 2018-01-01 | 0.0%          | 0 m²           |
| 2018-01-02 | 40.7%         | 0 m²           |
| 2018-01-03 | 0.2%          | 0 m²           |
| 2018-01-04 | 0.0%          | 0 m²           |
| 2018-01-05 | 3.0%          | 0 m²           |
| 2018-01-06 | 0.0%          | 3000 m²        |
| 2018-01-07 | 0.0%          | 0 m²           |
| 2018-01-08 | 0.0%          | 0 m²           |
| 2018-01-09 | 0.0%          | 0 m²           |
| 2018-01-10 | 0.0%          | 0 m²           |
| 2018-01-11 | 0.0%          | 0 m²           |
| 2018-01-12 | 4.1%          | 0 m²           |
| 2018-01-13 | 0.3%          | 0 m²           |
| 2018-01-14 | 0.0%          | 0 m²           |
| 2018-01-15 | 68.6%         | 0 m²           |
| 2018-01-16 | 0.2%          | 0 m²           |
| 2018-01-17 | 0.1%          | 0 m²           |
| 2018-01-18 | 1.7%          | 0 m²           |
| 2018-01-19 | 0.2%          | 0 m²           |
| 2018-01-20 | 0.1%          | 0 m²           |
| 2018-01-21 | 62.9%         | 0 m²           |
| 2018-01-22 | 69.6%         | 0 m²           |
| 2018-01-23 | 0.0%          | 0 m²           |
| 2018-01-24 | 97.7%         | 0 m²           |
| 2018-01-25 | 0.0%          | 0 m²           |
| 2018-01-26 | 0.0%          | 0 m²           |
| 2018-01-27 | 0.0%          | 0 m²           |
| 2018-01-28 | 0.0%          | 0 m²           |
| 2018-01-29 | 0.0%          | 0 m²           |
| 2018-01-30 | 0.0%          | 0 m²           |
| 2018-01-31 | 0.0%          | 0 m²           |
| 2018-02-01 | 0.0%          | 0 m²           |
| 2018-02-02 | 0.0%          | 0 m²           |
| 2018-02-03 | 0.0%          | 0 m²           |
| 2018-02-04 | 0.0%          | 0 m²           |
| 2018-02-05 | 0.0%          | 0 m²           |
| 2018-02-06 | 0.0%          | 0 m²           |
| 2018-02-07 | 0.0%          | 0 m²           |
| 2018-02-08 | 0.0%          | 0 m²           |
| 2018-02-09 | 0.0%          | 0 m²           |
| 2018-02-10 | 0.0%          | 0 m²           |
| 2018-02-11 | 0.0%          | 0 m²           |
| 2018-02-12 | 0.0%          | 0 m²           |
| 2018-02-13 | 6.8%          | 0 m²           |
| 2018-02-14 | 0.0%          | 0 m²           |
| 2018-02-15 | 0.0%          | 0 m²           |
| 2018-02-16 | 0.0%          | 0 m²           |
| 2018-02-17 | 0.0%          | 0 m²           |
| 2018-02-18 | 0.0%          | 0 m²           |
| 2018-02-19 | 83.5%         | 0 m²           |
| 2018-02-20 | 0.2%          | 0 m²           |
| 2018-02-21 | 0.0%          | 0 m²           |
| 2018-02-22 | 0.0%          | 0 m²           |
| 2018-02-23 | 0.0%          | 0 m²           |
| 2018-02-24 | 0.0%          | 0 m²           |
| 2018-02-25 | 0.0%          | 0 m²           |
| 2018-02-26 | 0.0%          | 0 m²           |
| 2018-02-27 | 0.0%          | 0 m²           |
| 2018-02-28 | 0.0%          | 0 m²           |
| 2018-03-01 | 0.0%          | 0 m²           |
| 2018-03-02 | 0.0%          | 0 m²           |
| 2018-03-03 | 0.0%          | 0 m²           |
| 2018-03-04 | 0.0%          | 0 m²           |
| 2018-03-05 | 0.0%          | 0 m²           |
| 2018-03-06 | 0.0%          | 0 m²           |
| 2018-03-07 | 0.0%          | 0 m²           |
| 2018-03-08 | 0.1%          | 0 m²           |
| 2018-03-09 | 0.0%          | 0 m²           |
| 2018-03-10 | 0.0%          | 0 m²           |
| 2018-03-11 | 0.0%          | 0 m²           |
| 2018-03-12 | 0.0%          | 0 m²           |
| 2018-03-13 | 0.0%          | 0 m²           |
| 2018-03-14 | 35.6%         | 0 m²           |
| 2018-03-15 | 0.0%          | 0 m²           |
| 2018-03-16 | 0.0%          | 0 m²           |
| 2018-03-17 | 0.0%          | 0 m²           |
| 2018-03-18 | 0.0%          | 0 m²           |
| 2018-03-19 | 0.1%          | 0 m²           |
| 2018-03-20 | 0.0%          | 0 m²           |
| 2018-03-21 | 0.2%          | 0 m²           |
| 2018-03-22 | 25.8%         | 0 m²           |
| 2018-03-23 | 31.9%         | 0 m²           |
| 2018-03-24 | 0.6%          | 0 m²           |
| 2018-03-25 | 0.0%          | 0 m²           |
| 2018-03-26 | 0.1%          | 0 m²           |
| 2018-03-27 | 0.0%          | 0 m²           |
| 2018-03-28 | 0.0%          | 0 m²           |
| 2018-03-29 | 0.0%          | 0 m²           |
| 2018-03-30 | 1.4%          | 0 m²           |
| 2018-03-31 | 89.9%         | 0 m²           |
| 2018-04-01 | 1.7%          | 0 m²           |
| 2018-04-02 | 73.1%         | 0 m²           |
| 2018-04-03 | 99.3%         | 0 m²           |
| 2018-04-04 | 6.1%          | 0 m²           |
| 2018-04-05 | 27.8%         | 25000 m²       |
| 2018-04-06 | 57.2%         | 0 m²           |
| 2018-04-07 | 80.9%         | 36 m²          |
| 2018-04-08 | 85.9%         | 4000 m²        |
| 2018-04-09 | 4.5%          | 0 m²           |
| 2018-04-10 | 0.6%          | 0 m²           |
| 2018-04-11 | 0.0%          | 0 m²           |
| 2018-04-12 | 0.0%          | 0 m²           |
| 2018-04-13 | 0.0%          | 0 m²           |
| 2018-04-14 | 0.0%          | 0 m²           |
| 2018-04-15 | 0.0%          | 0 m²           |
| 2018-04-16 | 0.0%          | 0 m²           |
| 2018-04-17 | 0.2%          | 0 m²           |
| 2018-04-18 | 1.5%          | 0 m²           |
| 2018-04-19 | 6.3%          | 0 m²           |
| 2018-04-20 | 30.3%         | 0 m²           |
| 2018-04-21 | 87.0%         | 0 m²           |
| 2018-04-22 | 58.7%         | 0 m²           |
| 2018-04-23 | 1.3%          | 0 m²           |
| 2018-04-24 | 0.3%          | 0 m²           |
| 2018-04-25 | 3.0%          | 0 m²           |
| 2018-04-26 | 99.9%         | 0 m²           |
| 2018-04-27 | 40.3%         | 0 m²           |
| 2018-04-28 | 58.3%         | 5000 m²        |
| 2018-04-29 | 91.2%         | 0 m²           |
| 2018-04-30 | 0.0%          | 0 m²           |
| 2018-05-01 | 0.1%          | 0 m²           |
| 2018-05-02 | 14.5%         | 0 m²           |
| 2018-05-03 | 0.0%          | 0 m²           |
| 2018-05-04 | 0.0%          | 0 m²           |
| 2018-05-05 | 0.0%          | 0 m²           |
| 2018-05-06 | 0.0%          | 0 m²           |
| 2018-05-07 | 0.2%          | 0 m²           |
| 2018-05-08 | 2.7%          | 0 m²           |
| 2018-05-09 | 2.8%          | 0 m²           |
| 2018-05-10 | 19.0%         | 0 m²           |
| 2018-05-11 | 0.1%          | 0 m²           |
| 2018-05-12 | 30.6%         | 0 m²           |
| 2018-05-13 | 2.3%          | 0 m²           |
| 2018-05-14 | 0.4%          | 0 m²           |
| 2018-05-15 | 16.4%         | 0 m²           |
| 2018-05-16 | 10.6%         | 0 m²           |
| 2018-05-17 | 17.1%         | 0 m²           |
| 2018-05-18 | 29.4%         | 0 m²           |
| 2018-05-19 | 42.5%         | 0 m²           |
| 2018-05-20 | 2.9%          | 0 m²           |
| 2018-05-21 | 0.0%          | 0 m²           |
| 2018-05-22 | 0.5%          | 0 m²           |
| 2018-05-23 | 27.1%         | 0 m²           |
| 2018-05-24 | 51.2%         | 0 m²           |
| 2018-05-25 | 39.4%         | 0 m²           |
| 2018-05-26 | 92.3%         | 4000 m²        |
| 2018-05-27 | 61.0%         | 0 m²           |
| 2018-05-28 | 0.0%          | 0 m²           |
| 2018-05-29 | 0.0%          | 0 m²           |
| 2018-05-30 | 0.0%          | 0 m²           |
| 2018-05-31 | 0.3%          | 0 m²           |
| 2018-06-01 | 8.0%          | 0 m²           |
| 2018-06-02 | 0.3%          | 0 m²           |
| 2018-06-03 | 0.4%          | 0 m²           |
| 2018-06-04 | 38.9%         | 0 m²           |
| 2018-06-05 | 47.6%         | 0 m²           |
| 2018-06-06 | 42.3%         | 0 m²           |
| 2018-06-07 | 0.0%          | 0 m²           |
| 2018-06-08 | 0.0%          | 7000 m²        |
| 2018-06-09 | 1.8%          | 0 m²           |
| 2018-06-10 | 0.2%          | 0 m²           |
| 2018-06-11 | 50.6%         | 0 m²           |
| 2018-06-12 | 23.0%         | 0 m²           |
| 2018-06-13 | 60.4%         | 3200 m²        |
| 2018-06-14 | 48.0%         | 0 m²           |
| 2018-06-15 | 60.2%         | 0 m²           |
| 2018-06-16 | 16.7%         | 0 m²           |
| 2018-06-17 | 82.4%         | 0 m²           |
| 2018-06-18 | 100.0%        | 1500 m²        |
| 2018-06-19 | 100.0%        | 0 m²           |
| 2018-06-20 | 93.9%         | 0 m²           |
| 2018-06-21 | 99.7%         | 0 m²           |
| 2018-06-22 | 99.4%         | 10000 m²       |
| 2018-06-23 | 71.1%         | 0 m²           |
| 2018-06-24 | 62.3%         | 0 m²           |
| 2018-06-25 | 96.3%         | 0 m²           |
| 2018-06-26 | 91.3%         | 300 m²         |
| 2018-06-27 | 97.0%         | 4900 m²        |
| 2018-06-28 | 99.9%         | 0 m²           |
| 2018-06-29 | 99.8%         | 0 m²           |
| 2018-06-30 | 98.5%         | 0 m²           |
| 2018-07-01 | 58.1%         | 1400 m²        |
| 2018-07-02 | 96.7%         | 130 m²         |
| 2018-07-03 | 97.6%         | 20 m²          |
| 2018-07-04 | 94.6%         | 2020 m²        |
| 2018-07-05 | 72.4%         | 0 m²           |
| 2018-07-06 | 99.8%         | 3000 m²        |
| 2018-07-07 | 98.0%         | 940 m²         |
| 2018-07-08 | 87.3%         | 6200 m²        |
| 2018-07-09 | 100.0%        | 0 m²           |
| 2018-07-10 | 100.0%        | 40 m²          |
| 2018-07-11 | 100.0%        | 37200 m²       |
| 2018-07-12 | 99.9%         | 100 m²         |
| 2018-07-13 | 99.7%         | 300 m²         |
| 2018-07-14 | 75.9%         | 500 m²         |
| 2018-07-15 | 96.7%         | 805 m²         |
| 2018-07-16 | 61.5%         | 0 m²           |
| 2018-07-17 | 100.0%        | 0 m²           |
| 2018-07-18 | 100.0%        | 2000 m²        |
| 2018-07-19 | 99.5%         | 125 m²         |
| 2018-07-20 | 97.9%         | 3000 m²        |
| 2018-07-21 | 24.4%         | 17925 m²       |
| 2018-07-22 | 98.4%         | 5960 m²        |
| 2018-07-23 | 100.0%        | 0 m²           |
| 2018-07-24 | 99.5%         | 32 m²          |
| 2018-07-25 | 99.9%         | 0 m²           |
| 2018-07-26 | 100.0%        | 0 m²           |
| 2018-07-27 | 98.8%         | 7100 m²        |
| 2018-07-28 | 99.6%         | 0 m²           |
| 2018-07-29 | 94.9%         | 200 m²         |
| 2018-07-30 | 43.6%         | 0 m²           |
| 2018-07-31 | 99.8%         | 470 m²         |
| 2018-08-01 | 97.4%         | 0 m²           |
| 2018-08-02 | 98.4%         | 0 m²           |
| 2018-08-03 | 77.1%         | 0 m²           |
| 2018-08-04 | 55.3%         | 1630 m²        |
| 2018-08-05 | 87.4%         | 100 m²         |
| 2018-08-06 | 99.7%         | 220 m²         |
| 2018-08-07 | 98.7%         | 0 m²           |
| 2018-08-08 | 98.6%         | 0 m²           |
| 2018-08-09 | 79.1%         | 44 m²          |
| 2018-08-10 | 68.9%         | 0 m²           |
| 2018-08-11 | 99.3%         | 500 m²         |
| 2018-08-12 | 96.1%         | 0 m²           |
| 2018-08-13 | 29.2%         | 0 m²           |
| 2018-08-14 | 82.1%         | 0 m²           |
| 2018-08-15 | 64.2%         | 5 m²           |
| 2018-08-16 | 96.7%         | 0 m²           |
| 2018-08-17 | 74.9%         | 100 m²         |
| 2018-08-18 | 99.1%         | 300 m²         |
| 2018-08-19 | 98.8%         | 0 m²           |
| 2018-08-20 | 87.3%         | 400 m²         |
| 2018-08-21 | 99.9%         | 200 m²         |
| 2018-08-22 | 99.9%         | 200 m²         |
| 2018-08-23 | 99.7%         | 200 m²         |
| 2018-08-24 | 99.9%         | 500 m²         |
| 2018-08-25 | 99.6%         | 0 m²           |
| 2018-08-26 | 99.8%         | 0 m²           |
| 2018-08-27 | 97.8%         | 900 m²         |
| 2018-08-28 | 8.6%          | 100 m²         |
| 2018-08-29 | 0.8%          | 0 m²           |
| 2018-08-30 | 100.0%        | 50 m²          |
| 2018-08-31 | 100.0%        | 0 m²           |
| 2018-09-01 | 100.0%        | 9410 m²        |
| 2018-09-02 | 99.7%         | 400 m²         |
| 2018-09-03 | 99.3%         | 0 m²           |
| 2018-09-04 | 100.0%        | 2 m²           |
| 2018-09-05 | 29.8%         | 0 m²           |
| 2018-09-06 | 0.0%          | 2 m²           |
| 2018-09-07 | 89.0%         | 0 m²           |
| 2018-09-08 | 57.6%         | 1000 m²        |
| 2018-09-09 | 0.0%          | 0 m²           |
| 2018-09-10 | 9.1%          | 0 m²           |
| 2018-09-11 | 56.2%         | 0 m²           |
| 2018-09-12 | 62.5%         | 0 m²           |
| 2018-09-13 | 48.0%         | 0 m²           |
| 2018-09-14 | 42.7%         | 35000 m²       |
| 2018-09-15 | 39.3%         | 0 m²           |
| 2018-09-16 | 73.7%         | 0 m²           |
| 2018-09-17 | 75.3%         | 0 m²           |
| 2018-09-18 | 71.5%         | 0 m²           |
| 2018-09-19 | 95.6%         | 0 m²           |
| 2018-09-20 | 82.5%         | 0 m²           |
| 2018-09-21 | 43.7%         | 0 m²           |
| 2018-09-22 | 99.9%         | 11000 m²       |
| 2018-09-23 | 89.0%         | 123300 m²      |
| 2018-09-24 | 83.6%         | 13680 m²       |
| 2018-09-25 | 98.2%         | 0 m²           |
| 2018-09-26 | 80.8%         | 10000 m²       |
| 2018-09-27 | 72.0%         | 0 m²           |
| 2018-09-28 | 34.1%         | 2500 m²        |
| 2018-09-29 | 10.9%         | 1500 m²        |
| 2018-09-30 | 10.1%         | 0 m²           |
| 2018-10-01 | 100.0%        | 8100 m²        |
| 2018-10-02 | 100.0%        | 12050 m²       |
| 2018-10-03 | 83.3%         | 2000 m²        |
| 2018-10-04 | 90.7%         | 0 m²           |
| 2018-10-05 | 98.7%         | 0 m²           |
| 2018-10-06 | 90.1%         | 0 m²           |
| 2018-10-07 | 0.0%          | 0 m²           |
| 2018-10-08 | 0.0%          | 0 m²           |
| 2018-10-09 | 7.4%          | 0 m²           |
| 2018-10-10 | 0.0%          | 0 m²           |
| 2018-10-11 | 0.0%          | 0 m²           |
| 2018-10-12 | 0.0%          | 0 m²           |
| 2018-10-13 | 0.0%          | 0 m²           |
| 2018-10-14 | 0.0%          | 0 m²           |
| 2018-10-15 | 0.0%          | 0 m²           |
| 2018-10-16 | 0.0%          | 0 m²           |
| 2018-10-17 | 0.0%          | 0 m²           |
| 2018-10-18 | 0.0%          | 0 m²           |
| 2018-10-19 | 0.0%          | 0 m²           |
| 2018-10-20 | 0.0%          | 0 m²           |
| 2018-10-21 | 0.0%          | 0 m²           |
| 2018-10-22 | 0.0%          | 0 m²           |
| 2018-10-23 | 0.0%          | 0 m²           |
| 2018-10-24 | 0.0%          | 0 m²           |
| 2018-10-25 | 0.0%          | 0 m²           |
| 2018-10-26 | 0.0%          | 0 m²           |
| 2018-10-27 | 0.0%          | 0 m²           |
| 2018-10-28 | 0.0%          | 0 m²           |
| 2018-10-29 | 0.0%          | 0 m²           |
| 2018-10-30 | 0.0%          | 0 m²           |
| 2018-10-31 | 0.0%          | 0 m²           |
| 2018-11-01 | 0.0%          | 0 m²           |
| 2018-11-02 | 0.0%          | 0 m²           |
| 2018-11-03 | 0.0%          | 0 m²           |
| 2018-11-04 | 0.0%          | 0 m²           |
| 2018-11-05 | 0.0%          | 0 m²           |
| 2018-11-06 | 0.0%          | 0 m²           |
| 2018-11-07 | 0.0%          | 0 m²           |
| 2018-11-08 | 0.0%          | 0 m²           |
| 2018-11-09 | 0.0%          | 0 m²           |
| 2018-11-10 | 0.0%          | 0 m²           |
| 2018-11-11 | 0.0%          | 0 m²           |
| 2018-11-12 | 0.0%          | 0 m²           |
| 2018-11-13 | 0.0%          | 0 m²           |
| 2018-11-14 | 0.0%          | 0 m²           |
| 2018-11-15 | 0.0%          | 0 m²           |
| 2018-11-16 | 0.0%          | 0 m²           |
| 2018-11-17 | 0.0%          | 0 m²           |
| 2018-11-18 | 0.0%          | 0 m²           |
| 2018-11-19 | 0.0%          | 0 m²           |
| 2018-11-20 | 0.0%          | 0 m²           |
| 2018-11-21 | 0.0%          | 0 m²           |
| 2018-11-22 | 0.0%          | 0 m²           |
| 2018-11-23 | 0.0%          | 0 m²           |
| 2018-11-24 | 0.0%          | 0 m²           |
| 2018-11-25 | 0.0%          | 0 m²           |
| 2018-11-26 | 0.0%          | 0 m²           |
| 2018-11-27 | 0.0%          | 0 m²           |
| 2018-11-28 | 0.0%          | 0 m²           |
| 2018-11-29 | 0.0%          | 0 m²           |
| 2018-11-30 | 0.0%          | 0 m²           |
| 2018-12-01 | 0.0%          | 0 m²           |
| 2018-12-02 | 0.0%          | 0 m²           |
| 2018-12-03 | 0.0%          | 0 m²           |
| 2018-12-04 | 0.0%          | 0 m²           |
| 2018-12-05 | 0.0%          | 0 m²           |
| 2018-12-06 | 0.0%          | 0 m²           |
| 2018-12-07 | 0.0%          | 0 m²           |
| 2018-12-08 | 0.0%          | 0 m²           |
| 2018-12-09 | 0.0%          | 0 m²           |
| 2018-12-10 | 0.0%          | 0 m²           |
| 2018-12-11 | 0.0%          | 0 m²           |
| 2018-12-12 | 0.0%          | 0 m²           |
| 2018-12-13 | 0.0%          | 0 m²           |
| 2018-12-14 | 0.0%          | 0 m²           |
| 2018-12-15 | 0.0%          | 0 m²           |
| 2018-12-16 | 0.0%          | 0 m²           |
| 2018-12-17 | 0.0%          | 0 m²           |
| 2018-12-18 | 0.0%          | 0 m²           |
| 2018-12-19 | 0.0%          | 0 m²           |
| 2018-12-20 | 0.0%          | 0 m²           |
| 2018-12-21 | 0.0%          | 0 m²           |
| 2018-12-22 | 0.0%          | 0 m²           |
| 2018-12-23 | 0.0%          | 0 m²           |
| 2018-12-24 | 0.0%          | 0 m²           |
| 2018-12-25 | 0.0%          | 0 m²           |
| 2018-12-26 | 0.0%          | 0 m²           |
| 2018-12-27 | 0.0%          | 0 m²           |
| 2018-12-28 | 0.0%          | 0 m²           |
| 2018-12-29 | 0.0%          | 0 m²           |
| 2018-12-30 | 0.0%          | 0 m²           |
| 2018-12-31 | 0.0%          | 0 m²           |


### Pour aller plus loin...

Plusieurs choses pourraient être améliorées.

Comme j'en ai déjà parlé, la fonction de perte pourrait probablement être optimisée pour le calul d'un risque (qui, par nature, peut être supérieur à zéro sans être pour autant réalisé).

Les données d'entrée sont très certainement incomplètes. Je ne suis pas expert en la matière, les données météo retenues ne sont donc pas forcément les bonnes. Il y a, de plus, un facteur humain très important dans le déclenchement des incendies : malveillance, négligeance, accidents, disponibilité des moyens de lutte et de surveillance, etc. Si les données météorologiques suffisaient à prédire les incendies, nous saurions probablement mieux les anticiper depuis lontemps. Ce facteur humain n'est presque pas représenté dans le jeu de données et seraient difficiles à obtenir pour moi.

Il est possible que la structure du réseau (1 hidden layer donc le nombre de neurones est identique au nombre de données en entrée et 1 output layer d'un unique neurone) ne soit pas la plus adaptée à ce problème particulier.

Si je réalisais ces améliorations, le ratio "quantité de connaissances acquises / temps passé" augmenterait significativement. Cela relève finalement plus de réglages expérimentaux et/ou d'une expertise poussée dans le domaine des réseaux de neurones, alors que mon but principal était simplement de m'initier à leur fonctionnement. Dans tous les cas, la partie "développement" serait fortement réduite. Or, c'est dans cette partie que réside ma motivation première.

Je vais donc mettre en pause ce projet, dont l'objectif est atteint.

Prochaine étape : la manipulation d'images avec un réseau de neurones convolutif !


## Fonctionnement

### Pré-requis

Avoir PHP 7 installé et disponible dans le `PATH`.

### Installation

Simplement récupérer les sources du projet.

### Utilisation

Avec une invite de commandes, se placer dans le répertoire des sources du projet.

Pour initialiser un réseau de neurones :

    php init.php 19 19

_Les jeux de données présents dans les sources possèdent 19 valeurs d'entrée._

Pour entraîner le réseau de neurones sur les données 2010 à 2017 (`resources/train.csv`) :

    php train.php help

Pour tester le réseau de neurones sur les données 2018 (`resources/test.csv`) :
    
    php test.php

