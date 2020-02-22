Plugin zum Erstellen und Verwalten von WLAN-Kameras (Anzeige und Aufzeichnung)

Plugin Konfiguration
=======================

Après installation du plugin, il vous suffit de l’activer, cependant il y a quelques paramètres de configuration avancée :

-   **Chemin des enregistrements** : indique le chemin où Jeedom doit stocker les images qu’il capture de vos caméras (il est déconseillé d’y toucher). Si votre chemin n’est pas dans le chemin d’installation de Jeedom alors vous ne pourrez visualiser les captures dans Jeedom.

-   **Taille maximum du dossier d’enregistrement (Mo)** : indique la taille maximum autorisée pour le dossier où les captures sont enregistrées (il est déconseillé d’y toucher). Wenn diese Quote erreicht ist, löscht Jeedom die ältesten Fänge.

-   **Das Kamera-Plugin muss auf Interaktionen reagieren **: Schlüsselwörter / Phrasen, auf die das Plugin über Jeedom-Interaktionen reagiert.

-   **Panel** : Vous permet d’afficher le panel (Menu Accueil -> Caméra) et d’avoir une vue sur l’ensemble de vos caméras (voir plus bas). Vergessen Sie nicht, das Panel in der Konfiguration des Plugins zu aktivieren, um später darauf zugreifen zu können.


Gerätekonfiguration
=============================

Ausrüstung
----------

Hier haben Sie die wichtigsten Informationen Ihrer Kamera:

-   **Nom de l’équipement caméra** : nom de votre équipement caméra

-   **Objet parent** : indique l’objet parent auquel appartient l’équipement

-   **Aktivieren **: Aktiviert Ihre Ausrüstung

-   **Sichtbar **: macht es im Dashboard sichtbar

-   **IP** : l’adresse IP local de votre caméra

-   **Port **: Der Port, an den die Kamera angeschlossen werden soll

-   **Protokoll **: Das Kommunikationsprotokoll Ihrer Kamera (http oder https)

-   **Nom d’utilisateur** : nom d’utilisateur pour se connecter à la caméra (si nécessaire). Bitte beachten Sie, dass das Plugin keine Sonderzeichen unterstützt (Sie müssen sich daher auf Zahlen, Klein- / Großbuchstaben beschränken).

-   **Passwort **: Passwort für die Verbindung zur Kamera (falls erforderlich).Bitte beachten Sie, dass das Plugin keine Sonderzeichen unterstützt (Sie müssen sich daher auf Zahlen, Klein- / Großbuchstaben beschränken).

-   **Snapshot-URL **: Kamera-Snapshot-URL. Änderungen je nach Kamera. Achten Sie darauf, dass Sie keine Flow-URL unter die Strafe des Absturzes von Jeedom stellen. Vous pouvez ajouter les tags \#username\# et \#password\#, qui seront automatiquement remplacés par le nom d’utilisateur et le mot de passe lors de l’utilisation de cette commande

-   **Stream-URL **: Video-Stream-URL der Kamera vom Typ RTSP: // # Benutzername #: #Kennwort # @ # IP-Nr.: 554 / videoMain (Beispiel für Foscam-Kameras)

-   **Modell **: Hier können Sie das Kameramodell auswählen. Seien Sie vorsichtig, wenn Sie dies ändern, werden Ihre Konfigurationseinstellungen überschrieben

Metaphorik
------

Cette partie vous permet de configurer la qualité de l’image. En effet Jeedom diminue la taille de l’image ou la compresse avant de l’envoyer à votre navigateur. Dadurch können die Bilder flüssiger werden (weil sie heller sind). C’est aussi dans cette partie que vous pouvez configurer le nombre d’images par seconde à afficher. Alle Einstellungen sind verfügbar in: Mobil / Desktop und Miniatur / Normal.

-   Rafraichissement (s) : délai en seconde entre l’affichage de 2 images (vous pouvez ici mettre des chiffres inférieurs à 1)

-   Compression (%) : plus il est faible moins on compresse l’image, à 100 % aucune compression n’est faite

-   Taille (% - 0 : automatique) : plus le % est élévé plus on est proche de la taille d’origine de l’image. A 100 % aucun redimensionnement de l’image n’a lieu

> **Notiz**
>
> Si vous mettez une compression de 0% et une taille de 100%, Jeedom ne touchera pas à l’image en mode normal. Cela n’est pas valable en mode miniature où il y a une taille maximum de l’image de 360px.

Erfassung
-------

-   Durée maximum d’un enregistrement : durée maximum des enregistrements

-   Toujours faire une vidéo : force Jeedom à toujours transformer les enregistrements en vidéo avant l’enregistrement

-   Nombre d’images par seconde de la vidéo : nombre d’images par seconde des vidéos

-   Bewegungserkennungsschwelle (0-100): Bewegungserkennungsschwelle (es wird empfohlen, 2 einzustellen). Je höher der Wert, desto höher ist die Empfindlichkeit.

-   Alle Aufnahmen von der Kamera löschen: Alle Aufnahmen und Aufnahmen von der Kamera löschen

Versorgung
------------

-   Commande ON : Commande permettant de mettre en marche l’alimentation de la caméra

-   Commande OFF : Commande permettant de couper l’alimentation de la caméra

Befehle
---------

-   ID de la commande (utiliser avec les commandes de type info pour par exemple remonter l’information de mouvement de la caméra à Jeedom par l’api, voir plus bas)

-   Nom de la commande avec la possibilité de mettre une icône à la place (pour lösche es il faut double-cliquer sur l’icône en question)

-   Auftragsart und Untertyp

-   Anforderung zum Senden an die Kamera, um eine Aktion auszuführen (Umschalten in den Nachtmodus, ptz usw.). Vous pouvez utiliser les tags \#username\# et \#password\#, qui seront automatiquement remplacés par le nom d’utilisateur et le mot de passe lors de l’utilisation de cette commande

-   Commande stop : pour les caméras PTZ, il existe souvent une commande qui arrête le mouvement, c’est ici qu’il faut la spécifier

-   Afficher : permet d’afficher la commande ou non sur le dashboard

-   Configuration avancée (petites roues crantées) : permet d’afficher la configuration avancée de la commande (méthode d’historisation, widget, etc.)

-   Test: Ermöglicht das Testen des Befehls

-   Löschen (sign -): Ermöglicht das Löschen des Befehls

Das Widget
=========

On retrouve sur celui-ci l’image de la caméra, les commandes définies dans la configuration, la commande pour prendre une capture, la commande pour lancer la prise de multiples captures images et la commande pour parcourir ces captures.

> **Spitze**
>
> Sur le dashboard et le panel il est possible de redimensionner le widget pour l’adapter à ses besoins

Un clic sur l’image permet d’afficher celle-ci dans une fenêtre et dans un format plus grand.

Ein Klick auf den letzten Befehl zum Durchsuchen der Screenshots zeigt diesen an.

Vous retrouvez ici toutes les captures organisées par jour puis par date, vous pouvez pour chacune d’elle :

-   la voir en plus en grand en cliquant sur l’image

-   Laden Sie es herunter

-   lösche es

En mobile le widget est un peu différent : si vous cliquez sur l’image de la caméra vous obtenez celle-ci en plus grande avec les commandes possibles.

Die Paneele
==========

Le plugin caméra met aussi à disposition un panel qui vous permet de voir d’un seul coup toutes vos caméras, il est accessible par Acceuil → Caméra.

> **Notiz**
>
> Pour l’avoir il faut l’activer sur la page de configuration du plugin

Es ist natürlich auch für Handys per Plugin → Kamera erhältlich:

Speichern und Capture senden
==================================

Cette commande un peu spécifique permet suite à la prise de capture de faire l’envoi de celle-ci (compatible avec le plugin slack, mail et transfert).

La configuration est assez simple vous appelez l’action d’envoi de capture (dénommée "Enregistrement") dans un scénario. Im Titelteil übergeben Sie die Optionen.

Standardmäßig reicht es aus, die Anzahl der gewünschten Aufnahmen in das Feld &quot;Anzahl der Aufnahmen oder Optionen&quot; einzufügen. Sie können jedoch weitere Optionen auswählen (siehe Details unter &quot;Erweiterte Optionen für Aufnahmen&quot;). Dans la partie message, vous n'avez plus qu'à renseigner la commande du plugin (actuellement slack, mail ou transfert) qui fait l’envoi des captures. Sie können mehrere durch &amp;&amp; getrennte setzen.

Erweiterte Aufnahmeoptionen
---------------------------

-   nbSnap : nombre de capture, si non précisé alors les captures sont faites jusqu’à une demande d’arrêt d’enregistrement ou d’arrêt de la caméra

-   Verzögerung: Verzögerung zwischen 2 Aufnahmen, wenn nicht angegeben, beträgt die Verzögerung 1s

-   wait : délai d’attente avant de commencer les captures, si non précié alors aucun envoi n’est fait

-   sendPacket : nombre de captures déclenchant l’envoi de paquet de captures, si non précisé alors les captures ne seront envoyées qu’à la fin

-   detectMove = 1: Sendet nur dann Captures, wenn sich die Erkennungsschwelle ändert (siehe Kamerakonfiguration)

-   movie=1 : une fois l’enregistrement terminé, les images sont converties en vidéo

-   sendFirstSnap=1 : envoi la première capture de l’enregistrement

> **Beispiele**
>
> nbSnap = 3 delay = 5 ==&gt; 3 in 5-Sekunden-Intervallen erstellte Aufnahmen senden (Senden über das Szenario ausgelöst) movie = 1 sendFirstSnap = 1 detectMove = 1 ==&gt; Sende die erste Aufnahme und sende dann eine Aufnahme an Bei jeder Bewegungserkennung wird ein Video aufgezeichnet, bis der Befehl &quot;Aufzeichnung beenden&quot; in das Szenario eingefügt wird. Der Film wird auf Ihrem Jeedom gespeichert.


Senden Sie die Bewegungserkennung an Jeedom
===========================================

Si vous avez une caméra qui possède la détection de mouvement et que vous voulez transmettre celle-ci à Jeedom voilà l’url à mettre sur votre caméra :

    http: //#IP_JEEDOM#/core/api/jeeApi.php?apikey APIKEY = # # &amp; type = Kamera &amp; id = # ID # &amp; # value = Wert#

Natürlich vor dem Erstellen eines Info-Befehls auf Ihrer Kamera

FAQ
===

>**Wo sind die Aufzeichnungen?**
>
>Aufnahmen werden standardmäßig in Plugins / camera / data / records / * ID \ _CAM * gefunden. Bitte beachten Sie, dass dies variieren kann, wenn Sie Jeedom gebeten haben, sie an anderer Stelle aufzunehmen.

>**Sucht kann sich nicht einleben?**
>
>In ssh oder in der Administration -&gt; OS / DB -&gt; System do: dpkg --configure -a

>**Unter welchen Bedingungen muss meine Kamera Jeedom-kompatibel sein (sofern sie nicht in der Kompatibilitätsliste enthalten ist)?**
>
> Die einzige Bedingung ist, dass die Kamera eine URL hat, die ein Bild zurücksendet (also ein Bild, kein Videostream).
