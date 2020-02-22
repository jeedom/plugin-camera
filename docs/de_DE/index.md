Plugin zum Erstellen und Verwalten von WLAN-Kameras (Anzeige und Aufzeichnung)

Plugin Konfiguration
=======================

Nach der Installation des Plugins müssen Sie es nur aktivieren, es gibt jedoch einige erweiterte Konfigurationsparameter :

-   **Pfad aufzeichnen** : Gibt den Pfad an, in dem Jeedom die von Ihren Kameras aufgenommenen Bilder speichern soll (es wird nicht empfohlen, sie zu berühren). Wenn sich Ihr Pfad nicht im Jeedom-Installationspfad befindet, können Sie die Erfassungen in Jeedom nicht anzeigen.

-   **Maximale Größe des Aufnahmeordners (MB)** : Gibt die maximale Größe an, die für den Ordner autorisiert ist, in dem die Aufnahmen gespeichert sind (es wird nicht empfohlen, sie zu berühren). Wenn diese Quote erreicht ist, löscht Jeedom die ältesten Fänge.

-   **Das Kamera-Plugin muss auf Interaktionen reagieren** : Schlüsselwörter / Phrasen, auf die das Plugin über Jeedom-Interaktionen reagiert.

-   **Platte** : Ermöglicht die Anzeige des Bedienfelds (Startmenü -&gt; Kamera) und die Ansicht aller Ihrer Kameras (siehe unten). Vergessen Sie nicht, das Platte in der Konfiguration des Plugins zu aktivieren, um später darauf zugreifen zu können.


Gerätekonfiguration
=============================

Ausrüstung
----------

Hier haben Sie die wichtigsten Informationen Ihrer Kamera :

-   **Name der Kameraausrüstung** : Name Ihrer Kameraausrüstung

-   **Übergeordnetes Objekt** : Gibt das übergeordnete Objekt an, zu dem das Gerät gehört

-   **activate** : macht Ihre Ausrüstung aktiv

-   **sichtbar** : macht es auf dem Dashboard sichtbar

-   **IP** : die lokale IP-Adresse Ihrer Kamera

-   **Hafen** : der Anschluss, an den die Kamera angeschlossen werden soll

-   **Protokoll** : das Kommunikationsprotokoll Ihrer Kamera (http oder https)

-   **Benutzername** : Benutzername, um sich bei der Kamera anzumelden (falls erforderlich). Bitte beachten Sie, dass das Plugin keine Sonderzeichen unterstützt (Sie müssen sich daher auf Zahlen, Klein- / Großbuchstaben beschränken).

-   **Passwort** : Passwort für die Verbindung zur Kamera (falls erforderlich).Bitte beachten Sie, dass das Plugin keine Sonderzeichen unterstützt (Sie müssen sich daher auf Zahlen, Klein- / Großbuchstaben beschränken).

-   **Snapshot-URL** : Kamera-Snapshot-URL. Änderungen je nach Kamera. Achten Sie darauf, dass Sie keine Flow-URL unter die Strafe des Absturzes von Jeedom stellen. Sie können die Tags \ #Benutzername \ # und \ #Kennwort \ # hinzufügen, die bei Verwendung dieses Befehls automatisch durch den Benutzernamen und das Kennwort ersetzt werden

-   **Feed-URL** : URL des Videostreams des Kameratyps rtsp: // # Benutzername #: #Kennwort # @ # IP #: 554 / videoMain (Beispiel für Foscam-Kameras)

-   **Modell** : Hier können Sie das Kameramodell auswählen. Seien Sie vorsichtig, wenn Sie dies ändern, werden Ihre Konfigurationseinstellungen überschrieben

Metaphorik
------

In diesem Teil können Sie die Bildqualität konfigurieren. In der Tat verringert Jeedom die Größe des Bildes oder der Komprimierung, bevor es an Ihren Browser gesendet wird. Dadurch können die Bilder flüssiger werden (weil sie heller sind). In diesem Teil können Sie auch die Anzahl der anzuzeigenden Bilder pro Sekunde konfigurieren. Alle Einstellungen sind verfügbar in: Mobil / Desktop und Miniatur / Normal.

-   Aktualisieren: Verzögerung in Sekunden zwischen der Anzeige von 2 Bildern (hier können Sie Zahlen unter 1 eingeben)

-   Komprimierung (%): Je niedriger die Komprimierung, desto weniger wird das Bild komprimiert. Bei 100% wird keine Komprimierung durchgeführt

-   Größe (% - 0: automatisch): Je höher%, desto näher sind wir der Originalgröße des Bildes. Bei 100% findet keine Größenänderung des Bildes statt

> **Notiz**
>
> Wenn Sie eine Komprimierung von 0% und eine Größe von 100% festlegen, berührt Jeedom das Bild im normalen Modus nicht. Dies gilt nicht im Miniaturmodus mit einer maximalen Bildgröße von 360 Pixel.

Erfassung
-------

-   Maximale Aufnahmedauer: Maximale Aufnahmedauer

-   Immer ein Video machen: Jeedom zwingt sich, die Aufnahmen vor der Aufnahme immer in Video umzuwandeln

-   Anzahl der Bilder pro Sekunde des Videos: Anzahl der Bilder pro Sekunde des Videos

-   Bewegungserkennungsschwelle (0-100): Bewegungserkennungsschwelle (es wird empfohlen, 2 einzustellen). Je höher der Wert, desto höher ist die Empfindlichkeit.

-   Alle Aufnahmen von der Kamera löschen: Alle Aufnahmen und Aufnahmen von der Kamera löschen

Versorgung
------------

-   EIN-Befehl: Befehl zum Einschalten der Stromversorgung der Kamera

-   AUS-Befehl: Befehl zum Abschalten der Kamera

Befehle
---------

-   Bestell-ID (Verwendung mit Befehlen vom Typ Info, um beispielsweise die Bewegungsinformationen von der Kamera über die API zu Jeedom zu bringen, siehe unten)

-   Name des Befehls mit der Möglichkeit, stattdessen ein Symbol einzufügen (um es zu löschen, müssen Sie auf das betreffende Symbol doppelklicken).

-   Auftragsart und Untertyp

-   Anforderung zum Senden an die Kamera, um eine Aktion auszuführen (Umschalten in den Nachtmodus, ptz usw.). Sie können die Tags \ #Benutzername \ # und \ #Kennwort \ # verwenden, die bei Verwendung dieses Befehls automatisch durch den Benutzernamen und das Kennwort ersetzt werden

-   Stoppbefehl: Bei PTZ-Kameras gibt es häufig einen Befehl, der die Bewegung stoppt. Hier muss er angegeben werden

-   Anzeige: Mit dieser Option können Sie die Reihenfolge im Dashboard anzeigen oder nicht

-   Erweiterte Konfiguration (kleine gekerbte Räder): Ermöglicht die Anzeige der erweiterten Konfiguration des Befehls (Protokollierungsmethode, Widget usw.)

-   Test: Ermöglicht das Testen des Befehls

-   Löschen (sign -): Ermöglicht das Löschen des Befehls

Das Widget
=========

Wir finden hier das Bild der Kamera, die in der Konfiguration definierten Befehle, den Befehl zum Aufnehmen einer Aufnahme, den Befehl zum Starten mehrerer Bildaufnahmen und den Befehl zum Durchsuchen dieser Aufnahmen.

> **Spitze**
>
> Auf dem Dashboard und im Bedienfeld kann die Größe des Widgets geändert werden, um es an Ihre Anforderungen anzupassen

Durch Klicken auf das Bild wird es in einem Fenster und in einem größeren Format angezeigt.

Ein Klick auf den letzten Befehl zum Durchsuchen der Screenshots zeigt diesen an.

Hier finden Sie alle Fänge nach Tag und dann nach Datum, die Sie für jeden von ihnen können :

-   Sehen Sie es größer, indem Sie auf das Bild klicken

-   Laden Sie es herunter

-   lösche es

In Mobilgeräten ist das Widget etwas anders: Wenn Sie auf das Bild der Kamera klicken, wird es mit den möglichen Befehlen größer.

Die Paneele
==========

Das Kamera-Plugin bietet auch ein Bedienfeld, mit dem Sie alle Ihre Kameras gleichzeitig sehen können. Der Zugriff erfolgt über Home → Kamera.

> **Notiz**
>
> Um es zu haben, müssen Sie es auf der Plugin-Konfigurationsseite aktivieren

Es ist natürlich auch für Handys per Plugin → Kamera erhältlich :

Speichern und Erfassung senden
==================================

Dieser etwas spezifische Befehl ermöglicht es dem Erfassung, ihn zu senden (kompatibel mit dem Slack-, Mail- und Transfer-Plugin).

Die Konfiguration ist recht einfach. Sie rufen in einem Szenario die Send-Send-Aktion (&quot;Aufnahme&quot; genannt) auf. Im Titelteil übergeben Sie die Optionen.

Standardmäßig reicht es aus, die Anzahl der gewünschten Aufnahmen in das Feld &quot;Anzahl der Aufnahmen oder Optionen&quot; einzufügen. Sie können jedoch weitere Optionen auswählen (siehe Details unter &quot;Erweiterte Optionen für Aufnahmen&quot;). Im Nachrichtenteil müssen Sie nur die Reihenfolge des Plugins (derzeit locker, E-Mail oder Übertragung) eingeben, das die Erfassungs sendet. Sie können mehrere durch &amp;&amp; getrennte setzen.

Erweiterte Aufnahmeoptionen
---------------------------

-   `nbSnap` : nombre de capture, si non précisé alors les captures sont faites jusqu'à une demande d'arrêt d'enregistrement ou d'arrêt de la caméra

-   `delay` : délai entre 2 captures, si non précisé alors le délai est de 1s

-   `wait` : délai d'attente avant de commencer les captures, si non précié alors aucun envoi n'est fait

-   `sendPacket` : nombre de captures déclenchant l'envoi de paquet de captures, si non précisé alors les captures ne seront envoyées qu'à la fin

-   `detectMove=1` : envoi les captures que s'il y a un changement supérieur au seuil de détection (voir configuration de la caméra)

-   `movie=1` : une fois l'enregistrement terminé, les images sont converties en vidéo

-   `sendFirstSnap=1` : envoi la première capture de l'enregistrement

> **Beispiele**
>
> nbSnap = 3 delay = 5 ==&gt; 3 in 5-Sekunden-Intervallen erstellte Aufnahmen senden (Senden über das Szenario ausgelöst) movie = 1 sendFirstSnap = 1 detectMove = 1 ==&gt; Sende die erste Aufnahme und sende dann eine Aufnahme an Bei jeder Bewegungserkennung wird ein Video aufgezeichnet, bis der Befehl &quot;Aufzeichnung beenden&quot; in das Szenario eingefügt wird. Der Film wird auf Ihrem Jeedom gespeichert.


Senden Sie die Bewegungserkennung an Jeedom
===========================================

Wenn Sie eine Kamera mit Bewegungserkennung haben und diese an Jeedom senden möchten, ist dies die URL, die Sie auf Ihre Kamera setzen müssen :

    http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=camera&id=#ID#&value=#value#

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
