Plugin permettant de créer et gérer des caméras wifi (affichage et
enregistrement)

configuración del plugin
=======================

Après installation du plugin, il vous suffit de l’activer, cependant il
y a quelques paramètres de configuration avancée :

-   **Chemin des enregistrements** : indique le chemin où Jeedom doit
    stocker les images qu’il capture de vos caméras (il est déconseillé
    d’y toucher). Si votre chemin n’est pas dans le chemin
    d’installation de Jeedom alors vous ne pourrez visualiser les
    captures dans Jeedom.

-   **Taille maximum du dossier d’enregistrement (Mo)** : indique la
    taille maximum autorisée pour le dossier où les captures sont
    enregistrées (il est déconseillé d’y toucher). Si ce quotas est
    atteint Jeedom supprimera les captures les plus anciennes.

-   **Le plugin caméra doit réagir aux intéractions** : mots clés /
    phrases auxquels le plugin réagira via les intéractions de Jeedom.

-   **Panel** : Vous permet d’afficher le panel (Menu
    Accueil &gt; Caméra) et d’avoir une vue sur l’ensemble de vos
    caméras (voir plus bas)

Configuration des équipements 
=============================

Equipement 
----------

Ici vous avez les informations principales de votre caméra :

-   **Nom de l’équipement caméra** : nom de votre équipement caméra

-   **Objeto padre** : especifica el objeto padre al que pertenece
    l’équipement

-   **Activer** : permet de rendre votre équipement actif

-   **Visible** : le rend visible sur le dashboard

-   **IP** : l’adresse IP local de votre caméra

-   **Port** : le port pour sur lequel joindre la caméra

-   **Protocole** : le protocole de communication de votre caméra (http
    ou https)

-   **Nom d’utilisateur** : nom d’utilisateur pour se connecter à la
    caméra (si nécessaire)

-   **Mot de passe** : mot de passe pour se connecter à la caméra
    (si nécessaire)

-   **URL de capture** : URL de "snapshot" de la caméra. Change en
    fonction des caméras. Attention à ne pas mettre une url de flux sous
    peine de planter Jeedom. Vous pouvez ajouter les tags \#username\#
    et \#password\#, qui seront automatiquement remplacé par le nom
    d’utilisateur et le mot de passe lors de l’utilisation de cette
    commande

-   **Modèle** : permet de choisir le modèle de la caméra. Attention si
    vous changez cela écrasera vos paramètres de configuration

Images 
------

Cette partie vous permets de configurer la qualité de l’image. En effet
Jeedom diminue la taille de l’image ou la compresse avant de l’envoyer à
votre navigateur. Cela permet de gagner en fluidité des images (car
elles sont moins lourde). C’est aussi dans cette partie que vous pouvez
configurer le nombre d’image par secondes à afficher. Tous les réglages
sont déclinés en : mobile/desktop et miniature/normal.

-   Rafraichissement (s) : délai en seconde entre l’affichage de 2
    images (vous pouvez ici mettre des chiffre inférieure à 1)

-   Compression (%) : plus il est faible moins on compresse l’image, à
    100% aucune compression n’est faite

-   Taille (% - 0 : automatique) : plus le % est élévé plus on est
    proche de la taille d’origine de l’image. A 100% aucun
    redimensionnement de l’image n’a lieu

> **Note**
>
> Si vous mettez une compression de 0% et une taille de 100% Jeedom ne
> touchera pas à l’image en mode normal. Cela n’est pas valable en mode
> miniature ou il y a une taille maximum de l’image de 360px.

Capture 
-------

-   Durée maximum d’un enregistrement (s) : durée maximum des
    enregistrements

-   Toujours faire une video : force Jeedom à toujours transformer les
    enregistrements en video avant l’enregistrement

-   Nombre d’images par seconde de la vidéo : nombre d’images par
    seconde des vidéos

-   Seuil de détection mouvement (0-100) : seuil de détection de
    mouvement (il est conseillé de mettre 2). Plus la valeur est grande
    plus la sensibilité augmente.

-   Supprimer toutes les captures de la caméra : supprime toutes les
    captures et enregistrements de la caméra

Alimentation 
------------

-   Commande ON : Commande permetant de mettre en marche l’alimentation
    de la caméra

-   Commande OFF : Commande permetant de couper l’alimentation de la
    caméra

Commandes 
---------

-   ID de la commande (utiliser avec les commandes de type info pour par
    exemple remonter l’information de mouvement de la caméra à Jeedom
    par l’api, voir plus bas)

-   Nom de la commande avec la possibilité de mettre une icone à la
    place (pour la supprimer il faut double cliquer sur l’icone
    en question)

-   Type et sous type de la commande

-   Requête à envoyer à la caméra pour faire une action (passage en mode
    nuit, ptz, etc.). Vous pouvez utiliser les tags \#username\# et
    \#password\#, qui seront automatiquement remplacé par le nom
    d’utilisateur et le mot de passe lors de l’utilisation de cette
    commande

-   Commande stop : pour les caméras PTZ il existe souvent une commande
    qui arrête le mouvement, c’est ici qu’il faut la spécifier

-   Afficher : permet d’afficher la commande ou non sur le dashboard

-   Configuration avancée (petites roues crantées) : permet d’afficher
    la configuration avancée de la commande (méthode d’historisation,
    widget, etc.)

-   Tester : permet de tester la commande

-   Supprimer (signe -) : permet de supprimer la commande

Le widget 
=========

On retrouve sur celui-ci l’image de la caméra, les commandes définies
dans la configuration, la commande pour prendre une capture, la commande
pour lancer la prise de multiples captures images et la commande pour
parcourir ces captures.

> **Tip**
>
> Sur le dashboard et le panel il est possible de redimensionner le
> widget pour l’adapter à ses besoins

Un clique sur l’image permet d’afficher celle-ci dans une fenêtre et
dans un format plus grand.

Un clique sur la dernière commande pour parcourir les captures vous
affichera celle-ci.

Vous retrouvez ici toutes les captures organisées par jour puis par
date, vous pouvez pour chacune d’elle :

-   la voir en plus en grand en cliquant sur l’image

-   la télécharger

-   la supprimer

En mobile le widget est un peu différent si vous cliquez sur l’image de
la caméra vous obtenez celle-ci en plus grande avec les commandes
possibles.

Les panels 
==========

Le plugin caméra met aussi à disposition un panel qui vous permet de
voir d’un seul coup toutes vos caméras, il est accessible par Acceuil →
Caméra.

> **Note**
>
> Pour l’avoir il faut l’activer sur la page de configuration du plugin

Il est bien sur aussi disponible en mobile par Plugin → Caméra :

Enregistrement et envoi de capture 
==================================

Cette commande un peu spécifique permet suite à la prise de capture de
faire l’envoi de celle-ci (compatible avec le plugin slack, mail et
transfert)

La configuration est assez simple vous appellez l’action d’envoi de
capture, dans la partie titre vous passez les options (par défaut il
faut juste mettre le nombre de capture voulu mais vous pouvez aller plus
loin avec les options avancées) et dans la partie message la commande du
plugin (actuellement slack, mail ou transfert) qui fait l’envoi des
captures. Vous pouvez en mettre plusieurs séparé par des &&.

Options avancés de captures 
---------------------------

-   nbSnap : nombre de capture, si non précisé alors les captures sont
    faite jusqu’a une demande d’arret d’enregistrement ou d’arret de la
    caméra

-   delay : délai entre 2 capture, si non précisé alors le délai est de
    1s

-   wait : délai d’attente avant de commencer les captures, si non
    précié alors aucun envoi n’est fait

-   sendPacket : nombre de capture déclenchant l’envoi de paquet, si non
    précisé alors les captures seront envoyée qu’a la fin

-   detectMove=1 : envoi les captures que si un changement superieur au
    seuil de detection (voir configuration de la caméra) arrive

-   movie=1 : une fois l’enregistrement terminé, les images sont
    convertie en video

-   sendFirstSnap=1 : envoi la premiere capture de l’enregistrement

Envoi de la detection de mouvement à Jeedom 
===========================================

Si vous avez une caméra qui possède la détection de mouvement et que
vous voulez transmettre celle-ci à Jeedom voilà l’url à mettre sur votre
caméra :

    http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=camera&id=#ID#&value=#value#

Il faut bien entendu avant avoir créé une commande de type info sur
votre caméra

Preguntas frecuentes
===

Où sont les enregistrements ?

:   Les enregistrements se trouvent par défaut dans
    plugins/camera/data/records/*ID\_CAM*, attention cela peut varier si
    vous avez demandé à Jeedom de les enregistrer ailleurs

La liste des caméras compatibles est
[ici](https://github.com/jeedom/documentation/blob/master/camera/fr_FR/equipement.compatible.asciidoc)

Changelog 
=========

-   JEED-336 : Rajout du bouton historique sur la vue pleine ecran

-   Refonte de la gestion de l’affichage des caméras (réglage de la
    compression et de la taille de l’image)


