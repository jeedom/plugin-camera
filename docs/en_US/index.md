Plugin for creating and managing wifi cameras (display and
recording)

Plugin configuration
=======================

Après installation du plugin, il vous suffit de l’activer, cependant il
there are some advanced configuration settings:

- ** Record path **: indicates the path where Jeedom must
    stocker les images qu’il capture de vos cameras (il est déconseillé
    d’y toucher). Si votre chemin n’est pas dans le chemin
    d’installation de Jeedom alors vous ne pourrez visualiser les
    catches in Jeedom.

-   **Taille maximum du dossier d’enregistrement (Mo)** : indique la
    maximum size allowed for the folder where the catches are
    enregistrées (il est déconseillé d’y toucher). If this quota is
    reached Jeedom will delete the oldest captures.

- ** The camera plugin must react to interactions **: keywords /
    sentences to which the plugin will react via Jeedom interactions.

-   **Panel** : Vous permet d’afficher le panel (Menu
    Accueil -&gt; Caméra) et d’avoir une vue sur l’ensemble de vos
    cameras (see below). Do not forget to activate the panel in the configuration of the plugin to access it later.


Equipment configuration
=============================

Equipment
----------

Here you have the main information of your camera:

-   **Nom de l’équipement camera** : nom de votre équipement camera

-   **Objet parent** : indique l’objet parent auquel appartient
    l’équipement

- ** Activate **: makes your equipment active

- ** Visible **: makes it visible on the dashboard

-   **IP** : l’adresse IP local de votre camera

- ** Port **: the port for which to attach the camera

- ** Protocol **: the communication protocol of your camera (http
    or https)

-   **Nom d’utilisateur** : nom d’utilisateur pour se connecter à la
    camera (if necessary). Please note the plugin does not support characters
    special (so limit yourself to numbers, lowercase / uppercase letters)

- ** Password **: password to connect to the camera
    (if necessary).Please note the plugin does not support characters
    special (so limit yourself to numbers, lowercase / uppercase letters)

- ** Snapshot URL **: Camera snapshot URL. Changed into
    cameras function. Be careful not to put a flow url under
    worth planting Jeedom. You can add the tags \ #username \#
    and \ #password \ #, which will be automatically replaced by the name
    d’utilisateur et le mot de passe lors de l’utilisation de cette
    ordered

- ** Stream URL **: video stream url of the rtsp type camera: // # username #: #password # @ # ip #: 554 / videoMain (example for Foscam cameras)

- ** Model **: allows you to choose the camera model. Be careful if
    you change that will overwrite your configuration settings

imagery
------

Cette partie vous permet de configurer la qualité de l’image. Indeed
Jeedom diminue la taille de l’image ou la compresse avant de l’envoyer à
your browser. This improves the fluidity of the images (because
they are lighter). C’est aussi dans cette partie que vous pouvez
configurer le nombre d’images par seconde à afficher. All settings
are available in: mobile / desktop and miniature / normal.

-   Rafraichissement (s) : délai en seconde entre l’affichage de 2
    images (here you can put numbers less than 1)

-   Compression (%) : plus il est faible moins on compresse l’image, à
    100 % aucune compression n’est faite

- Size (% - 0: automatic): the higher the% the more you are
    proche de la taille d’origine de l’image. 100% none
    redimensionnement de l’image n’a lieu

&gt; ** Note**
&gt;
&gt; Si vous mettez une compression de 0% et une taille de 100%, Jeedom ne
&gt; touchera pas à l’image en mode normal. Cela n’est pas valable en mode
&gt; miniature où il y a une taille maximum de l’image de 360px.

Capture
-------

-   Durée maximum d’un enregistrement : durée maximum des
    recordings

- Always make a video: Jeedom forces to always transform
    recordings en vidéo avant l’enregistrement

-   Nombre d’images par seconde de la vidéo : nombre d’images par
    second videos

- Motion detection threshold (0-100): motion detection threshold
    movement (it is advisable to put 2). The greater the value
    the more the sensitivity increases.

- Delete all captures from the camera: delete all
    captures et recordings from the camera

Food
------------

-   Commande ON : Commande permettant de mettre en marche l’alimentation
    from the camera

-   Commande OFF : Commande permettant de couper l’alimentation de la
    camera

Orders
---------

-   ID de la ordered (utiliser avec les ordereds de type info pour par
    exemple remonter l’information de mouvement from the camera à Jeedom
    par l’api, voir plus bas)

-   Nom de la ordered avec la possibilité de mettre une icône à la
    place (pour la supprimer il faut double-cliquer sur l’icône
    in question)

-   Type et sous-type de la ordered

-   Requête à envoyer à la camera pour faire une action (passage en mode
    night, ptz, etc.). You can use the tags \ #username \ # and
    \ #password \ #, which will be automatically replaced by the name
    d’utilisateur et le mot de passe lors de l’utilisation de cette
    ordered

-   Commande stop : pour les cameras PTZ, il existe souvent une ordered
    qui arrête le mouvement, c’est ici qu’il faut la spécifier

-   Afficher : permet d’afficher la ordered ou non sur le dashboard

-   Configuration avancée (petites roues crantées) : permet d’afficher
    la configuration avancée de la ordered (méthode d’historisation,
    widget, etc.)

-   Tester : permet de tester la ordered

-   Supprimer (signe -) : permet de supprimer la ordered

The widget
=========

On retrouve sur celui-ci l’image from the camera, les ordereds définies
dans la configuration, la ordered pour prendre une capture, la ordered
pour lancer la prise de multiples captures images et la ordered pour
browse these captures.

&gt; **Tip**
&gt;
&gt; Sur le dashboard et le panel il est possible de redimensionner le
&gt; widget pour l’adapter à ses besoins

Un clic sur l’image permet d’afficher celle-ci dans une fenêtre et
in a larger format.

Un clic sur la dernière ordered pour parcourir les captures vous
will display this.

You will find here all the catches organized by day then by
date, vous pouvez pour chacune d’elle :

-   la voir en plus en grand en cliquant sur l’image

- download it

- delete it

En mobile le widget est un peu différent : si vous cliquez sur l’image de
la camera vous obtenez celle-ci en plus grande avec les ordereds
possible.

The panels
==========

Le plugin camera met aussi à disposition un panel qui vous permet de
voir d’un seul coup toutes vos cameras, il est accessible par Acceuil →
Camera.

&gt; ** Note**
&gt;
&gt; Pour l’avoir il faut l’activer sur la page de configuration du plugin

It is of course also available in mobile by Plugin → Camera:

Save and send capture
==================================

Cette ordered un peu spécifique permet suite à la prise de capture de
faire l’envoi de celle-ci (compatible avec le plugin slack, mail et
transfer).

La configuration est assez simple vous appelez l’action d’envoi de
capture (called &quot;Record&quot;) in a scenario. In the title part you pass the options.

By default just put the number of captures you want in the &quot;number of captures or options&quot; field, but you can go more
further with options (see detail below &quot;advanced options of captures&quot;). Dans la partie message, vous n'avez plus qu'à renseigner la ordered du plugin (actuellement slack, mail ou transfert) qui fait l’envoi des captures. You can put several separated by &amp;&amp;.

Advanced capture options
---------------------------

- nbSnap: number of captures, if not specified then the captures are
    faites jusqu’à une demande d’arrêt d’enregistrement ou d’arrêt de la
    camera

- delay: delay between 2 captures, if not specified then the delay is
    1s

-   wait : délai d’attente avant de commencer les captures, si non
    précié alors aucun envoi n’est fait

-   sendPacket : nombre de captures déclenchant l’envoi de paquet de captures, si non
    précisé alors les captures ne seront envoyées qu’à la fin

- detectMove = 1: send captures only if there is a change greater than
    seuil de détection (voir configuration from the camera)

-   movie=1 : une fois l’enregistrement terminé, les images sont
    converted to video

-   sendFirstSnap=1 : envoi la première capture de l’enregistrement

&gt; **Exemples**
&gt;
&gt; nbSnap=3 delay=5 ==&gt; envoi 3 captures faites à 5 secondes d'intervalle (envoi déclenché via le scénario)
&gt; movie=1 sendFirstSnap=1 detectMove=1 ==&gt; envoi la première capture, puis envoi d'une capture à chaque détection de mouvement et enregistre une vidéo jusqu'à la ordered "Arrêter Enregistrement" à insérer dans le scénario. The film will be stored on your Jeedom.


Send motion detection to Jeedom
===========================================

Si vous avez une camera qui possède la détection de mouvement et que
vous voulez transmettre celle-ci à Jeedom voilà l’url à mettre sur votre
camera :

    http: //#IP_JEEDOM#/core/api/jeeApi.php?apikey APIKEY = # # &amp; type = camera &amp; id = # ID # &amp; # value = value#

Il faut bien entendu avant avoir créé une ordered de type info sur
votre camera

FAQ
===

&gt;**Où sont les recordings ?**
&gt;
&gt;Les recordings se trouvent par défaut dans plugins/camera/data/records/*ID\_CAM*, attention cela peut varier si vous avez demandé à Jeedom de les enregistrer ailleurs

&gt;**Les dépendances n'arrivents pas à s'installer ?**
&gt;
&gt;En ssh ou dans administration -&gt; OS/DB -&gt; Système faire : dpkg --configure -a

&gt;**Quelles sont les conditions pour que ma camera soit compatible Jeedom (si elle n'est pas dans la liste de compatibilité) ?**
&gt;
&gt; La seule condition c'est que la camera possède une url qui renvoi une image (et bien une image pas un flux video)
