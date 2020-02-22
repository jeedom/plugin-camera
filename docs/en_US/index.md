Plugin to create and manage wifi cameras (display and recording)

Plugin configuration
=======================

Après installation du plugin, il vous suffit de l’activer, cependant il y a quelques paramètres de configuration avancée :

-   **Chemin des enregistrements** : indique le chemin où Jeedom doit stocker les images qu’il capture de vos caméras (il est déconseillé d’y toucher). Si votre chemin n’est pas dans le chemin d’installation de Jeedom alors vous ne pourrez visualiser les captures dans Jeedom.

-   **Taille maximum du dossier d’enregistrement (Mo)** : indique la taille maximum autorisée pour le dossier où les captures sont enregistrées (il est déconseillé d’y toucher). If this quota is reached Jeedom will delete the oldest catches.

-   **The camera plugin must react to interactions **: keywords / phrases to which the plugin will react via Jeedom interactions.

-   **Panel** : Vous permet d’afficher le panel (Menu Accueil -> Caméra) et d’avoir une vue sur l’ensemble de vos caméras (voir plus bas). Do not forget to activate the panel in the configuration of the plugin to access it later.


Equipment configuration
=============================

Equipment
----------

Here you have the main information of your camera:

-   **Nom de l’équipement caméra** : nom de votre équipement caméra

-   **Objet parent** : indique l’objet parent auquel appartient l’équipement

-   **Activate **: makes your equipment active

-   **Visible **: makes it visible on the dashboard

-   **IP** : l’adresse IP local de votre caméra

-   **Port **: the port for which to attach the camera

-   **Protocol **: the communication protocol of your camera (http or https)

-   **Nom d’utilisateur** : nom d’utilisateur pour se connecter à la caméra (si nécessaire). Please note that the plugin does not support special characters (you must therefore limit yourself to numbers, lowercase / uppercase letters)

-   **Password **: password to connect to the camera (if necessary).Please note that the plugin does not support special characters (you must therefore limit yourself to numbers, lowercase / uppercase letters)

-   **Snapshot URL **: Camera snapshot URL. Changes depending on the cameras. Be careful not to put a flow url under penalty of crashing Jeedom. Vous pouvez ajouter les tags \#username\# et \#password\#, qui seront automatiquement remplacés par le nom d’utilisateur et le mot de passe lors de l’utilisation de cette commande

-   **Stream URL **: video stream url of the rtsp type camera: // # username #: #password # @ # ip #: 554 / videoMain (example for Foscam cameras)

-   **Model **: allows you to choose the camera model. Be careful if you change this will overwrite your configuration settings

imagery
------

Cette partie vous permet de configurer la qualité de l’image. En effet Jeedom diminue la taille de l’image ou la compresse avant de l’envoyer à votre navigateur. This allows the images to be more fluid (because they are lighter). C’est aussi dans cette partie que vous pouvez configurer le nombre d’images par seconde à afficher. All settings are available in: mobile / desktop and miniature / normal.

-   Rafraichissement (s) : délai en seconde entre l’affichage de 2 images (vous pouvez ici mettre des chiffres inférieurs à 1)

-   Compression (%) : plus il est faible moins on compresse l’image, à 100 % aucune compression n’est faite

-   Taille (% - 0 : automatique) : plus le % est élévé plus on est proche de la taille d’origine de l’image. A 100 % aucun redimensionnement de l’image n’a lieu

> **Note**
>
> Si vous mettez une compression de 0% et une taille de 100%, Jeedom ne touchera pas à l’image en mode normal. Cela n’est pas valable en mode miniature où il y a une taille maximum de l’image de 360px.

Capture
-------

-   Durée maximum d’un enregistrement : durée maximum des enregistrements

-   Toujours faire une vidéo : force Jeedom à toujours transformer les enregistrements en vidéo avant l’enregistrement

-   Nombre d’images par seconde de la vidéo : nombre d’images par seconde des vidéos

-   Motion detection threshold (0-100): motion detection threshold (it is advisable to set 2). The higher the value, the more the sensitivity increases.

-   Delete all captures from camera: delete all captures and recordings from camera

Food
------------

-   Commande ON : Commande permettant de mettre en marche l’alimentation de la caméra

-   Commande OFF : Commande permettant de couper l’alimentation de la caméra

Orders
---------

-   ID de la commande (utiliser avec les commandes de type info pour par exemple remonter l’information de mouvement de la caméra à Jeedom par l’api, voir plus bas)

-   Nom de la commande avec la possibilité de mettre une icône à la place (pour delete it il faut double-cliquer sur l’icône en question)

-   Order type and subtype

-   Request to send to the camera to perform an action (switch to night mode, ptz, etc.). Vous pouvez utiliser les tags \#username\# et \#password\#, qui seront automatiquement remplacés par le nom d’utilisateur et le mot de passe lors de l’utilisation de cette commande

-   Commande stop : pour les caméras PTZ, il existe souvent une commande qui arrête le mouvement, c’est ici qu’il faut la spécifier

-   Afficher : permet d’afficher la commande ou non sur le dashboard

-   Configuration avancée (petites roues crantées) : permet d’afficher la configuration avancée de la commande (méthode d’historisation, widget, etc.)

-   Test: allows you to test the command

-   Delete (sign -): allows you to delete the command

The widget
=========

On retrouve sur celui-ci l’image de la caméra, les commandes définies dans la configuration, la commande pour prendre une capture, la commande pour lancer la prise de multiples captures images et la commande pour parcourir ces captures.

> **Tip**
>
> Sur le dashboard et le panel il est possible de redimensionner le widget pour l’adapter à ses besoins

Un clic sur l’image permet d’afficher celle-ci dans une fenêtre et dans un format plus grand.

A click on the last command to browse the screenshots will display this one.

Vous retrouvez ici toutes les captures organisées par jour puis par date, vous pouvez pour chacune d’elle :

-   la voir en plus en grand en cliquant sur l’image

-   download it

-   delete it

En mobile le widget est un peu différent : si vous cliquez sur l’image de la caméra vous obtenez celle-ci en plus grande avec les commandes possibles.

The panels
==========

Le plugin caméra met aussi à disposition un panel qui vous permet de voir d’un seul coup toutes vos caméras, il est accessible par Acceuil → Caméra.

> **Note**
>
> Pour l’avoir il faut l’activer sur la page de configuration du plugin

It is of course also available in mobile by Plugin → Camera:

Save and send capture
==================================

Cette commande un peu spécifique permet suite à la prise de capture de faire l’envoi de celle-ci (compatible avec le plugin slack, mail et transfert).

La configuration est assez simple vous appelez l’action d’envoi de capture (dénommée "Enregistrement") dans un scénario. In the title part you pass the options.

By default, just put the number of captures you want in the &quot;number of captures or options&quot; field, but you can go further with options (see details below &quot;advanced options of captures&quot;). Dans la partie message, vous n'avez plus qu'à renseigner la commande du plugin (actuellement slack, mail ou transfert) qui fait l’envoi des captures. You can put several separated by &amp;&amp;.

Advanced capture options
---------------------------

-   nbSnap : nombre de capture, si non précisé alors les captures sont faites jusqu’à une demande d’arrêt d’enregistrement ou d’arrêt de la caméra

-   delay: delay between 2 captures, if not specified then the delay is 1s

-   wait : délai d’attente avant de commencer les captures, si non précié alors aucun envoi n’est fait

-   sendPacket : nombre de captures déclenchant l’envoi de paquet de captures, si non précisé alors les captures ne seront envoyées qu’à la fin

-   detectMove = 1: sends captures only if there is a change above the detection threshold (see camera configuration)

-   movie=1 : une fois l’enregistrement terminé, les images sont converties en vidéo

-   sendFirstSnap=1 : envoi la première capture de l’enregistrement

> **Examples**
>
> nbSnap = 3 delay = 5 ==&gt; send 3 captures made at 5 second intervals (send triggered via the scenario) movie = 1 sendFirstSnap = 1 detectMove = 1 ==&gt; send the first capture, then send a capture to each motion detection and record a video until the &quot;Stop Recording&quot; command to insert in the scenario. The film will be stored on your Jeedom.


Send motion detection to Jeedom
===========================================

Si vous avez une caméra qui possède la détection de mouvement et que vous voulez transmettre celle-ci à Jeedom voilà l’url à mettre sur votre caméra :

    http: //#IP_JEEDOM#/core/api/jeeApi.php?apikey APIKEY = # # &amp; type = camera &amp; id = # ID # &amp; # value = value#

Obviously, before creating an info type command on your camera

FAQ
===

>**Where are the records?**
>
>The recordings are found by default in plugins / camera / data / records / * ID \ _CAM *, please note that this may vary if you asked Jeedom to record them elsewhere

>**Addictions can&#39;t settle in?**
>
>In ssh or in administration -&gt; OS / DB -&gt; System do: dpkg --configure -a

>**What are the conditions for my camera to be Jeedom compatible (if it is not in the compatibility list)?**
>
> The only condition is that the camera has an url which sends back an image (well an image not a video stream)
