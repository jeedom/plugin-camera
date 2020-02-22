Complemento para crear y administrar cámaras wifi (visualización y grabación)

Configuración del complemento
=======================

Après installation du plugin, il vous suffit de l’activer, cependant il y a quelques paramètres de configuration avancée :

-   **Chemin des enregistrements** : indique le chemin où Jeedom doit stocker les images qu’il capture de vos caméras (il est déconseillé d’y toucher). Si votre chemin n’est pas dans le chemin d’installation de Jeedom alors vous ne pourrez visualiser les captures dans Jeedom.

-   **Taille maximum du dossier d’enregistrement (Mo)** : indique la taille maximum autorisée pour le dossier où les captures sont enregistrées (il est déconseillé d’y toucher). Si se alcanza esta cuota, Jeedom eliminará las capturas más antiguas.

-   **El complemento de la cámara debe reaccionar a las interacciones **: palabras clave / frases a las que el complemento reaccionará a través de las interacciones de Jeedom.

-   **Panel** : Vous permet d’afficher le panel (Menu Accueil -> Caméra) et d’avoir une vue sur l’ensemble de vos caméras (voir plus bas). No olvide activar el panel en la configuración del complemento para acceder más tarde.


Configuración del equipo
=============================

equipo
----------

Aquí tienes la información principal de tu cámara:

-   **Nom de l’équipement caméra** : nom de votre équipement caméra

-   **Objet parent** : indique l’objet parent auquel appartient l’équipement

-   **Activar **: activa su equipo

-   **Visible **: lo hace visible en el tablero

-   **IP** : l’adresse IP local de votre caméra

-   **Puerto **: el puerto para el que se conecta la cámara

-   **Protocolo **: el protocolo de comunicación de su cámara (http o https)

-   **Nom d’utilisateur** : nom d’utilisateur pour se connecter à la caméra (si nécessaire). Tenga en cuenta que el complemento no admite caracteres especiales (por lo tanto, debe limitarse a números, letras minúsculas / mayúsculas)

-   **Contraseña **: contraseña para conectarse a la cámara (si es necesario).Tenga en cuenta que el complemento no admite caracteres especiales (por lo tanto, debe limitarse a números, letras minúsculas / mayúsculas)

-   **URL de instantánea **: URL de instantánea de cámara. Cambios según las cámaras. Tenga cuidado de no poner una url de flujo bajo pena de estrellar Jeedom. Vous pouvez ajouter les tags \#username\# et \#password\#, qui seront automatiquement remplacés par le nom d’utilisateur et le mot de passe lors de l’utilisation de cette commande

-   **URL de transmisión **: URL de transmisión de video de la cámara tipo rtsp: // # nombre de usuario #: # contraseña # @ # ip #: 554 / videoMain (ejemplo para cámaras Foscam)

-   **Modelo **: le permite elegir el modelo de cámara. Tenga cuidado si cambia esto, sobrescribirá su configuración

imágenes
------

Cette partie vous permet de configurer la qualité de l’image. En effet Jeedom diminue la taille de l’image ou la compresse avant de l’envoyer à votre navigateur. Esto permite que las imágenes sean más fluidas (porque son más claras). C’est aussi dans cette partie que vous pouvez configurer le nombre d’images par seconde à afficher. Todas las configuraciones están disponibles en: móvil / escritorio y miniatura / normal.

-   Rafraichissement (s) : délai en seconde entre l’affichage de 2 images (vous pouvez ici mettre des chiffres inférieurs à 1)

-   Compression (%) : plus il est faible moins on compresse l’image, à 100 % aucune compression n’est faite

-   Taille (% - 0 : automatique) : plus le % est élévé plus on est proche de la taille d’origine de l’image. A 100 % aucun redimensionnement de l’image n’a lieu

> **nota**
>
> Si vous mettez une compression de 0% et une taille de 100%, Jeedom ne touchera pas à l’image en mode normal. Cela n’est pas valable en mode miniature où il y a une taille maximum de l’image de 360px.

captura
-------

-   Durée maximum d’un enregistrement : durée maximum des enregistrements

-   Toujours faire une vidéo : force Jeedom à toujours transformer les enregistrements en vidéo avant l’enregistrement

-   Nombre d’images par seconde de la vidéo : nombre d’images par seconde des vidéos

-   Umbral de detección de movimiento (0-100): umbral de detección de movimiento (es recomendable establecer 2). Cuanto mayor sea el valor, más aumenta la sensibilidad.

-   Eliminar todas las capturas de la cámara: elimine todas las capturas y grabaciones de la cámara

suministro
------------

-   Commande ON : Commande permettant de mettre en marche l’alimentation de la caméra

-   Commande OFF : Commande permettant de couper l’alimentation de la caméra

comandos
---------

-   ID de la commande (utiliser avec les commandes de type info pour par exemple remonter l’information de mouvement de la caméra à Jeedom par l’api, voir plus bas)

-   Nom de la commande avec la possibilité de mettre une icône à la place (pour bórralo il faut double-cliquer sur l’icône en question)

-   puntao de orden y subtipo

-   Solicitud para enviar a la cámara para realizar una acción (cambiar al modo nocturno, ptz, etc.). Vous pouvez utiliser les tags \#username\# et \#password\#, qui seront automatiquement remplacés par le nom d’utilisateur et le mot de passe lors de l’utilisation de cette commande

-   Commande stop : pour les caméras PTZ, il existe souvent une commande qui arrête le mouvement, c’est ici qu’il faut la spécifier

-   Afficher : permet d’afficher la commande ou non sur le dashboard

-   Configuration avancée (petites roues crantées) : permet d’afficher la configuration avancée de la commande (méthode d’historisation, widget, etc.)

-   Prueba: le permite probar el comando

-   Eliminar (signo -): le permite eliminar el comando

El widget
=========

On retrouve sur celui-ci l’image de la caméra, les commandes définies dans la configuration, la commande pour prendre une capture, la commande pour lancer la prise de multiples captures images et la commande pour parcourir ces captures.

> **punta**
>
> Sur le dashboard et le panel il est possible de redimensionner le widget pour l’adapter à ses besoins

Un clic sur l’image permet d’afficher celle-ci dans une fenêtre et dans un format plus grand.

Un clic en el último comando para explorar las capturas de pantalla mostrará este.

Vous retrouvez ici toutes les captures organisées par jour puis par date, vous pouvez pour chacune d’elle :

-   la voir en plus en grand en cliquant sur l’image

-   descárgalo

-   bórralo

En mobile le widget est un peu différent : si vous cliquez sur l’image de la caméra vous obtenez celle-ci en plus grande avec les commandes possibles.

Los paneles
==========

Le plugin caméra met aussi à disposition un panel qui vous permet de voir d’un seul coup toutes vos caméras, il est accessible par Acceuil → Caméra.

> **nota**
>
> Pour l’avoir il faut l’activer sur la page de configuration du plugin

Por supuesto, también está disponible en dispositivos móviles con Plugin → Cámara:

Guardar y enviar captura
==================================

Cette commande un peu spécifique permet suite à la prise de capture de faire l’envoi de celle-ci (compatible avec le plugin slack, mail et transfert).

La configuration est assez simple vous appelez l’action d’envoi de capture (dénommée "Enregistrement") dans un scénario. En la parte del título pasas las opciones.

De forma predeterminada, simplemente coloque el número de capturas que desea en el campo &quot;número de capturas u opciones&quot;, pero puede ir más allá con las opciones (consulte los detalles a continuación &quot;opciones avanzadas de capturas&quot;). Dans la partie message, vous n'avez plus qu'à renseigner la commande du plugin (actuellement slack, mail ou transfert) qui fait l’envoi des captures. Puede poner varios separados por &amp;&amp;.

Opciones de captura avanzadas
---------------------------

-   nbSnap : nombre de capture, si non précisé alors les captures sont faites jusqu’à une demande d’arrêt d’enregistrement ou d’arrêt de la caméra

-   retraso: retraso entre 2 capturas, si no se especifica, el retraso es de 1 s

-   wait : délai d’attente avant de commencer les captures, si non précié alors aucun envoi n’est fait

-   sendPacket : nombre de captures déclenchant l’envoi de paquet de captures, si non précisé alors les captures ne seront envoyées qu’à la fin

-   detectMove = 1: envía capturas solo si hay un cambio por encima del umbral de detección (consulte la configuración de la cámara)

-   movie=1 : une fois l’enregistrement terminé, les images sont converties en vidéo

-   sendFirstSnap=1 : envoi la première capture de l’enregistrement

> **Ejemplos**
>
> nbSnap = 3 delay = 5 ==&gt; envía 3 capturas realizadas a intervalos de 5 segundos (envío activado a través del escenario) película = 1 sendFirstSnap = 1 detectMove = 1 ==&gt; envía la primera captura, luego envía una captura a cada detección de movimiento y grabar un video hasta el comando &quot;Detener grabación&quot; para insertar en el escenario. La película se almacenará en su Jeedom.


Enviar detección de movimiento a Jeedom
===========================================

Si vous avez une caméra qui possède la détection de mouvement et que vous voulez transmettre celle-ci à Jeedom voilà l’url à mettre sur votre caméra :

    http: //#IP_JEEDOM#/core/api/jeeApi.php?apikey apikey = # # &amp; type = &amp; id = cámara # ID # &amp; # valor = valor#

Obviamente, antes de crear un comando de tipo de información en su cámara

Preguntas frecuentes
===

>**¿Dónde están los registros?**
>
>Las grabaciones se encuentran por defecto en plugins / camera / data / records / * ID \ _CAM *, tenga en cuenta que esto puede variar si le pide a Jeedom que las grabe en otro lugar

>**¿Las adicciones no pueden asentarse?**
>
>En ssh o en administración -&gt; OS / DB -&gt; Sistema hacer: dpkg --configure -a

>**¿Cuáles son las condiciones para que mi cámara sea compatible con Jeedom (si no está en la lista de compatibilidad)?**
>
> La única condición es que la cámara tenga una URL que envíe una imagen (bueno, una imagen, no una transmisión de video)
