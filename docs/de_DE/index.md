Plugin zum Erstellen und Verwalten von WLAN-Kameras (Display und
Registrierung)

Plugin Konfiguration
=======================

Après installation du plugin, il vous suffit de l’activer, cependant il
Es gibt einige erweiterte Konfigurationseinstellungen:

-   **Aufzeichnungspfad **: Gibt den Pfad an, auf dem Jeedom muss
    stocker les images qu’il capture de vos Kameras (il est déconseillé
    d’y toucher). Si votre chemin n’est pas dans le chemin
    d’installation de Jeedom alors vous ne pourrez visualiser les
    fängt in Jeedom.

-   **Taille maximum du dossier d’enregistrement (Mo)** : indique la
    Maximal zulässige Größe für den Ordner, in dem sich die Fänge befinden
    enregistrées (il est déconseillé d’y toucher). Wenn dieses Kontingent ist
    Jeedom erreicht löscht die ältesten Erfassungs.

-   **Das Kamera-Plugin muss auf Interaktionen reagieren **: keywords /
    Sätze, auf die das Plugin über Jeedom-Interaktionen reagiert.

-   **Panel** : Vous permet d’afficher le panel (Menu
    Accueil -&gt; Caméra) et d’avoir une vue sur l’ensemble de vos
    Kameras (siehe unten). Vergessen Sie nicht, das Panel in der Konfiguration des Plugins zu aktivieren, um später darauf zugreifen zu können.


Gerätekonfiguration
=============================

Ausrüstung
----------

Hier haben Sie die wichtigsten Informationen Ihrer Kamera:

-   **Nom de l’équipement Kamera** : nom de votre équipement Kamera

-   **Objet parent** : indique l’objet parent auquel appartient
    l’équipement

-   **Aktivieren **: Aktiviert Ihre Ausrüstung

-   **Sichtbar **: macht es im Dashboard sichtbar

-   **IP** : l’adresse IP local de votre Kamera

-   **Port **: Der Port, an den die Kamera angeschlossen werden soll

-   **Protokoll **: Das Kommunikationsprotokoll Ihrer Kamera (http
    oder https)

-   **Nom d’utilisateur** : nom d’utilisateur pour se connecter à la
    Kamera (falls erforderlich). Bitte beachten Sie, dass das Plugin keine Zeichen unterstützt
    Spezial (beschränken Sie sich also auf Zahlen, Klein- / Großbuchstaben)

-   **Passwort **: Passwort zum Herstellen einer Verbindung zur Kamera
    (falls erforderlich).Bitte beachten Sie, dass das Plugin keine Zeichen unterstützt
    Spezial (beschränken Sie sich also auf Zahlen, Klein- / Großbuchstaben)

-   **Snapshot-URL **: Kamera-Snapshot-URL. Wechseln Sie zu
    Kameras funktionieren. Achten Sie darauf, keine Flow-URL darunter zu setzen
    es lohnt sich, Jeedom zu pflanzen. Sie können die Tags \ #Benutzername \ hinzufügen#
    und \ #password \ #, das automatisch durch den Namen ersetzt wird
    d’utilisateur et le mot de passe lors de l’utilisation de cette
    bestellen

-   **Stream-URL **: Video-Stream-URL der Kamera vom Typ RTSP: // # Benutzername #: #Kennwort # @ # IP-Nr.: 554 / videoMain (Beispiel für Foscam-Kameras)

-   **Modell **: Hier können Sie das Kameramodell auswählen. Seien Sie vorsichtig, wenn
    Sie ändern, dass Ihre Konfigurationseinstellungen überschrieben werden

Metaphorik
------

Cette partie vous permet de configurer la qualité de l’image. In der Tat
Jeedom diminue la taille de l’image ou la compresse avant de l’envoyer à
Ihr Browser. Dies verbessert die Fließfähigkeit der Bilder (weil
sie sind leichter). C’est aussi dans cette partie que vous pouvez
configurer le nombre d’images par seconde à afficher. Alle Einstellungen
sind verfügbar in: Mobile / Desktop und Miniatur / Normal.

-   Rafraichissement (s) : délai en seconde entre l’affichage de 2
    Bilder (hier können Sie Zahlen unter 1 setzen)

-   Compression (%) : plus il est faible moins on compresse l’image, à
    100 % aucune compression n’est faite

-   Größe (% - 0: automatisch): Je höher%, desto mehr sind Sie
    proche de la taille d’origine de l’image. 100% keine
    redimensionnement de l’image n’a lieu

&gt; ** Hinweis**
&gt;
&gt; Si vous mettez une compression de 0% et une taille de 100%, Jeedom ne
&gt; touchera pas à l’image en mode normal. Cela n’est pas valable en mode
&gt; miniature où il y a une taille maximum de l’image de 360px.

Erfassung
-------

-   Durée maximum d’un enregistrement : durée maximum des
    Aufnahmen

-   Mach immer ein Video: Jeedom zwingt sich, sich immer zu verwandeln
    Aufnahmen en vidéo avant l’enregistrement

-   Nombre d’images par seconde de la vidéo : nombre d’images par
    zweite Videos

-   Bewegungserkennungsschwelle (0-100): Bewegungserkennungsschwelle
    Bewegung (es ist ratsam, 2 zu setzen). Je größer der Wert
    Je mehr die Empfindlichkeit steigt.

-   Alle Kameraaufnahmen löschen: Löscht alle
    captures et Aufnahmen von der Kamera

Versorgung
------------

-   Commande ON : Commande permettant de mettre en marche l’alimentation
    von der Kamera

-   Commande OFF : Commande permettant de couper l’alimentation de la
    Kamera

Befehle
---------

-   ID de la bestellen (utiliser avec les bestellens de type info pour par
    exemple remonter l’information de mouvement von der Kamera à Jeedom
    par l’api, voir plus bas)

-   Nom de la bestellen avec la possibilité de mettre une icône à la
    place (pour lösche es il faut double-cliquer sur l’icône
    in Frage)

-   Type et sous-type de la bestellen

-   Requête à envoyer à la Kamera pour faire une action (passage en mode
    Nacht, ptz usw.). Sie können die Tags \ #Benutzername \ # und verwenden
    \ #password \ #, das automatisch durch den Namen ersetzt wird
    d’utilisateur et le mot de passe lors de l’utilisation de cette
    bestellen

-   Commande stop : pour les Kameras PTZ, il existe souvent une bestellen
    qui arrête le mouvement, c’est ici qu’il faut la spécifier

-   Afficher : permet d’afficher la bestellen ou non sur le dashboard

-   Configuration avancée (petites roues crantées) : permet d’afficher
    la configuration avancée de la bestellen (méthode d’historisation,
    Widget usw.)

-   Tester : permet de tester la bestellen

-   Supprimer (signe -) : permet de supprimer la bestellen

Das Widget
=========

On retrouve sur celui-ci l’image von der Kamera, les bestellens définies
dans la configuration, la bestellen pour prendre une capture, la bestellen
pour lancer la prise de multiples captures images et la bestellen pour
Durchsuchen Sie diese Aufnahmen.

&gt; **Tip**
&gt;
&gt; Sur le dashboard et le panel il est possible de redimensionner le
&gt; widget pour l’adapter à ses besoins

Un clic sur l’image permet d’afficher celle-ci dans une fenêtre et
in einem größeren Format.

Un clic sur la dernière bestellen pour parcourir les captures vous
wird dies anzeigen.

Hier finden Sie alle Fänge, die nach Tag und dann nach Tag organisiert sind
date, vous pouvez pour chacune d’elle :

-   la voir en plus en grand en cliquant sur l’image

-   Laden Sie es herunter

-   lösche es

En mobile le widget est un peu différent : si vous cliquez sur l’image de
la Kamera vous obtenez celle-ci en plus grande avec les bestellens
möglich.

Die Paneele
==========

Le plugin Kamera met aussi à disposition un panel qui vous permet de
voir d’un seul coup toutes vos Kameras, il est accessible par Acceuil →
Kamera.

&gt; ** Hinweis**
&gt;
&gt; Pour l’avoir il faut l’activer sur la page de configuration du plugin

Es ist natürlich auch für Handys per Plugin → Kamera erhältlich:

Speichern und Capture senden
==================================

Cette bestellen un peu spécifique permet suite à la prise de capture de
faire l’envoi de celle-ci (compatible avec le plugin slack, mail et
Transfer).

La configuration est assez simple vous appelez l’action d’envoi de
Capture (&quot;Record&quot; genannt) in einem Szenario. Im Titelteil übergeben Sie die Optionen.

Standardmäßig geben Sie einfach die gewünschte Anzahl von Aufnahmen in das Feld &quot;Anzahl der Aufnahmen oder Optionen&quot; ein, aber Sie können noch mehr tun
weiter mit Optionen (siehe Detail unten &quot;Erweiterte Optionen für Aufnahmen&quot;). Dans la partie message, vous n'avez plus qu'à renseigner la bestellen du plugin (actuellement slack, mail ou transfert) qui fait l’envoi des captures. Sie können mehrere durch &amp;&amp; getrennte setzen.

Erweiterte Aufnahmeoptionen
---------------------------

-   nbSnap: Anzahl der Erfassungen, falls nicht angegeben, sind die Erfassungen
    faites jusqu’à une demande d’arrêt d’enregistrement ou d’arrêt de la
    Kamera

-   Verzögerung: Verzögerung zwischen 2 Aufnahmen, wenn nicht angegeben, ist die Verzögerung
    1s

-   wait : délai d’attente avant de commencer les captures, si non
    précié alors aucun envoi n’est fait

-   sendPacket : nombre de captures déclenchant l’envoi de paquet de captures, si non
    précisé alors les captures ne seront envoyées qu’à la fin

-   detectMove = 1: Captures nur senden, wenn eine Änderung größer als ist
    seuil de détection (voir configuration von der Kamera)

-   movie=1 : une fois l’enregistrement terminé, les images sont
    in Video konvertiert

-   sendFirstSnap=1 : envoi la première capture de l’enregistrement

&gt; **Exemples**
&gt;
&gt; nbSnap=3 delay=5 ==&gt; envoi 3 captures faites à 5 secondes d'intervalle (envoi déclenché via le scénario)
&gt; movie=1 sendFirstSnap=1 detectMove=1 ==&gt; envoi la première capture, puis envoi d'une capture à chaque détection de mouvement et enregistre une vidéo jusqu'à la bestellen "Arrêter Enregistrement" à insérer dans le scénario. Der Film wird auf Ihrem Jeedom gespeichert.


Senden Sie die Bewegungserkennung an Jeedom
===========================================

Si vous avez une Kamera qui possède la détection de mouvement et que
vous voulez transmettre celle-ci à Jeedom voilà l’url à mettre sur votre
Kamera :

    http: //#IP_JEEDOM#/core/api/jeeApi.php?apikey APIKEY = # # &amp; type = Kamera &amp; id = # ID # &amp; # value = Wert#

Il faut bien entendu avant avoir créé une bestellen de type info sur
votre Kamera

FAQ
===

&gt;**Où sont les Aufnahmen ?**
&gt;
&gt;Les Aufnahmen se trouvent par défaut dans plugins/camera/data/records/*ID\_CAM*, attention cela peut varier si vous avez demandé à Jeedom de les enregistrer ailleurs

&gt;**Les dépendances n'arrivents pas à s'installer ?**
&gt;
&gt;En ssh ou dans administration -&gt; OS/DB -&gt; Système faire : dpkg --configure -a

&gt;**Quelles sont les conditions pour que ma Kamera soit compatible Jeedom (si elle n'est pas dans la liste de compatibilité) ?**
&gt;
&gt; La seule condition c'est que la Kamera possède une url qui renvoi une image (et bien une image pas un flux video)
