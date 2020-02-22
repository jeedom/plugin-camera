Complemento para crear y administrar cámaras wifi (pantalla y
registro)

Configuración del complemento
=======================

Après installation du plugin, il vous suffit de l’activer, cependant il
Hay algunas opciones de configuración avanzadas:

-   **Registro de ruta **: indica la ruta donde Jeedom debe
    stocker les images qu’il capture de vos cámaras (il est déconseillé
    d’y toucher). Si votre chemin n’est pas dans le chemin
    d’installation de Jeedom alors vous ne pourrez visualiser les
    capturas en Jeedom.

-   **Taille maximum du dossier d’enregistrement (Mo)** : indique la
    tamaño máximo permitido para la carpeta donde están las capturas
    enregistrées (il est déconseillé d’y toucher). Si esta cuota es
    alcanzado Jeedom borrará las capturas más antiguas.

-   **El complemento de la cámara debe reaccionar a las interacciones **: palabras clave /
    frases a las que reaccionará el complemento a través de las interacciones de Jeedom.

-   **Panel** : Vous permet d’afficher le panel (Menu
    Accueil -&gt; Caméra) et d’avoir une vue sur l’ensemble de vos
    cámaras (ver abajo). No olvide activar el panel en la configuración del complemento para acceder más tarde.


Configuración del equipo
=============================

equipo
----------

Aquí tienes la información principal de tu cámara:

-   **Nom de l’équipement cámara** : nom de votre équipement cámara

-   **Objet parent** : indique l’objet parent auquel appartient
    l’équipement

-   **Activar **: activa su equipo

-   **Visible **: lo hace visible en el tablero

-   **IP** : l’adresse IP local de votre cámara

-   **Puerto **: el puerto para el que se conecta la cámara

-   **Protocolo **: el protocolo de comunicación de su cámara (http
    o https)

-   **Nom d’utilisateur** : nom d’utilisateur pour se connecter à la
    cámara (si es necesario). Tenga en cuenta que el complemento no admite caracteres
    especial (así que limítese a números, letras minúsculas / mayúsculas)

-   **Contraseña **: contraseña para conectarse a la cámara
    (si es necesario).Tenga en cuenta que el complemento no admite caracteres
    especial (así que limítese a números, letras minúsculas / mayúsculas)

-   **URL de instantánea **: URL de instantánea de cámara. Cambiar a
    Funcion de camaras. Tenga cuidado de no poner una url de flujo debajo
    Vale la pena plantar Jeedom. Puede agregar las etiquetas \ #username \#
    y \ # contraseña \ #, que se reemplazará automáticamente por el nombre
    d’utilisateur et le mot de passe lors de l’utilisation de cette
    orden

-   **URL de transmisión **: URL de transmisión de video de la cámara tipo rtsp: // # nombre de usuario #: # contraseña # @ # ip #: 554 / videoMain (ejemplo para cámaras Foscam)

-   **Modelo **: le permite elegir el modelo de cámara. Ten cuidado si
    cambia eso sobrescribirá sus ajustes de configuración

imágenes
------

Cette partie vous permet de configurer la qualité de l’image. En efecto
Jeedom diminue la taille de l’image ou la compresse avant de l’envoyer à
su navegador Esto mejora la fluidez de las imágenes (porque
son más ligeros) C’est aussi dans cette partie que vous pouvez
configurer le nombre d’images par seconde à afficher. Todos los ajustes
están disponibles en: móvil / escritorio y miniatura / normal.

-   Rafraichissement (s) : délai en seconde entre l’affichage de 2
    imágenes (aquí puedes poner números menores que 1)

-   Compression (%) : plus il est faible moins on compresse l’image, à
    100 % aucune compression n’est faite

-   Tamaño (% - 0: automático): cuanto mayor sea el%, más eres
    proche de la taille d’origine de l’image. 100% ninguno
    redimensionnement de l’image n’a lieu

&gt; ** Nota**
&gt;
&gt; Si vous mettez une compression de 0% et une taille de 100%, Jeedom ne
&gt; touchera pas à l’image en mode normal. Cela n’est pas valable en mode
&gt; miniature où il y a une taille maximum de l’image de 360px.

captura
-------

-   Durée maximum d’un enregistrement : durée maximum des
    grabaciones

-   Siempre haz un video: Jeedom obliga a transformar siempre
    grabaciones en vidéo avant l’enregistrement

-   Nombre d’images par seconde de la vidéo : nombre d’images par
    segundos videos

-   Umbral de detección de movimiento (0-100): umbral de detección de movimiento
    movimiento (es recomendable poner 2). Cuanto mayor es el valor
    cuanto más aumenta la sensibilidad.

-   Eliminar todas las capturas de cámara: elimina todas
    captures et grabaciones de la camara

suministro
------------

-   Commande ON : Commande permettant de mettre en marche l’alimentation
    de la camara

-   Commande OFF : Commande permettant de couper l’alimentation de la
    cámara

comandos
---------

-   ID de la orden (utiliser avec les ordens de type info pour par
    exemple remonter l’information de mouvement de la camara à Jeedom
    par l’api, voir plus bas)

-   Nom de la orden avec la possibilité de mettre une icône à la
    place (pour bórralo il faut double-cliquer sur l’icône
    en cuestión)

-   Type et sous-type de la orden

-   Requête à envoyer à la cámara pour faire une action (passage en mode
    noche, ptz, etc.). Puede usar las etiquetas \ #username \ # y
    \ # contraseña \ #, que será reemplazado automáticamente por el nombre
    d’utilisateur et le mot de passe lors de l’utilisation de cette
    orden

-   Commande stop : pour les cámaras PTZ, il existe souvent une orden
    qui arrête le mouvement, c’est ici qu’il faut la spécifier

-   Afficher : permet d’afficher la orden ou non sur le dashboard

-   Configuration avancée (petites roues crantées) : permet d’afficher
    la configuration avancée de la orden (méthode d’historisation,
    widget, etc.)

-   Tester : permet de tester la orden

-   Supprimer (signe -) : permet de supprimer la orden

El widget
=========

On retrouve sur celui-ci l’image de la camara, les ordens définies
dans la configuration, la orden pour prendre une capture, la orden
pour lancer la prise de multiples captures images et la orden pour
navegar por estas capturas.

&gt; **Tip**
&gt;
&gt; Sur le dashboard et le panel il est possible de redimensionner le
&gt; widget pour l’adapter à ses besoins

Un clic sur l’image permet d’afficher celle-ci dans une fenêtre et
en un formato más grande

Un clic sur la dernière orden pour parcourir les captures vous
mostrará esto.

Encontrarás aquí todas las capturas organizadas por día y luego por
date, vous pouvez pour chacune d’elle :

-   la voir en plus en grand en cliquant sur l’image

-   descárgalo

-   bórralo

En mobile le widget est un peu différent : si vous cliquez sur l’image de
la cámara vous obtenez celle-ci en plus grande avec les ordens
posible.

Los paneles
==========

Le plugin cámara met aussi à disposition un panel qui vous permet de
voir d’un seul coup toutes vos cámaras, il est accessible par Acceuil →
Cámara.

&gt; ** Nota**
&gt;
&gt; Pour l’avoir il faut l’activer sur la page de configuration du plugin

Por supuesto, también está disponible en dispositivos móviles con Plugin → Cámara:

Guardar y enviar captura
==================================

Cette orden un peu spécifique permet suite à la prise de capture de
faire l’envoi de celle-ci (compatible avec le plugin slack, mail et
transferencia).

La configuration est assez simple vous appelez l’action d’envoi de
captura (llamado &quot;Grabar&quot;) en un escenario. En la parte del título pasas las opciones.

De forma predeterminada, solo coloque el número de capturas que desea en el campo &quot;número de capturas u opciones&quot;, pero puede ir más
más con las opciones (ver detalles a continuación &quot;opciones avanzadas de capturas&quot;). Dans la partie message, vous n'avez plus qu'à renseigner la orden du plugin (actuellement slack, mail ou transfert) qui fait l’envoi des captures. Puede poner varios separados por &amp;&amp;.

Opciones de captura avanzadas
---------------------------

-   nbSnap: número de capturas, si no se especifica, las capturas son
    faites jusqu’à une demande d’arrêt d’enregistrement ou d’arrêt de la
    cámara

-   retraso: retraso entre 2 capturas, si no se especifica, el retraso es
    1s

-   wait : délai d’attente avant de commencer les captures, si non
    précié alors aucun envoi n’est fait

-   sendPacket : nombre de captures déclenchant l’envoi de paquet de captures, si non
    précisé alors les captures ne seront envoyées qu’à la fin

-   detectMove = 1: envía capturas solo si hay un cambio mayor que
    seuil de détection (voir configuration de la camara)

-   movie=1 : une fois l’enregistrement terminé, les images sont
    convertido a video

-   sendFirstSnap=1 : envoi la première capture de l’enregistrement

&gt; **Exemples**
&gt;
&gt; nbSnap=3 delay=5 ==&gt; envoi 3 captures faites à 5 secondes d'intervalle (envoi déclenché via le scénario)
&gt; movie=1 sendFirstSnap=1 detectMove=1 ==&gt; envoi la première capture, puis envoi d'une capture à chaque détection de mouvement et enregistre une vidéo jusqu'à la orden "Arrêter Enregistrement" à insérer dans le scénario. La película se almacenará en su Jeedom.


Enviar detección de movimiento a Jeedom
===========================================

Si vous avez une cámara qui possède la détection de mouvement et que
vous voulez transmettre celle-ci à Jeedom voilà l’url à mettre sur votre
cámara :

    http: //#IP_JEEDOM#/core/api/jeeApi.php?apikey apikey = # # &amp; type = &amp; id = cámara # ID # &amp; # valor = valor#

Il faut bien entendu avant avoir créé une orden de type info sur
votre cámara

Preguntas frecuentes
===

&gt;**Où sont les grabaciones ?**
&gt;
&gt;Les grabaciones se trouvent par défaut dans plugins/camera/data/records/*ID\_CAM*, attention cela peut varier si vous avez demandé à Jeedom de les enregistrer ailleurs

&gt;**Les dépendances n'arrivents pas à s'installer ?**
&gt;
&gt;En ssh ou dans administration -&gt; OS/DB -&gt; Système faire : dpkg --configure -a

&gt;**Quelles sont les conditions pour que ma cámara soit compatible Jeedom (si elle n'est pas dans la liste de compatibilité) ?**
&gt;
&gt; La seule condition c'est que la cámara possède une url qui renvoi une image (et bien une image pas un flux video)
