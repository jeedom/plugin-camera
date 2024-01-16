# Changelog Kamera

>**Wichtig**
>
>Wenn es keine Informationen über die Aktualisierung gibt, bedeutet dies, dass es sich nur um die Aktualisierung von Dokumentation, Übersetzung oder Text handelt.

# 01.05.2024

- Ein Fehler bei der Anzeige von Kameras auf Mobilgeräten wurde behoben

# 01.03.2024

- Vorbereitung auf Jeedom 4.4

# 26.09.2023

- ONVIF PTZ-Befehlsunterstützung

# 10.02.2022

- Es wurde ein Problem behoben, bei dem der API-Schlüssel nicht sichtbar war

# 31.01.2022

- API-Aufruf aktualisieren

# 12.11.2021

- Unterstützung für neue Kameras
- Schaltfläche hinzugefügt, um eine Vorschau der Kamera auf der Konfigurationsseite anzuzeigen

# 31.08.2021

- Änderung, um die Wiederherstellung von einer Funktion eines anderen Plugins zu ermöglichen (wird für ein zukünftiges Unifi Protect-Plugin verwendet)
- Hinzufügen der Ubiquiti G4 Bullet-Kamera (bitte beachten Sie, dass die Kamera die anonyme Verbindung aktivieren muss)

# 02/04/2021

- Es wurde ein Problem behoben, durch das das Abrufen von Bildern nicht gestoppt wurde, auch wenn keine Kamera mehr angezeigt wurde

# 29.11.2020

- Ein Problem mit der Bildanzeige bei Designs wurde behoben


# 24/11/2020

- Neue Darstellung der Objektliste
- Hinzufügung des Tags "V4-Kompatibilität"

# 30.08.2020

- Hinzufügung der Foscam FI9926P Kamera

# 07/07/2020

- Behebung eines Problems am Standardport der Stream-URL für Foscam-Kameras dank @nebz
- Hinzufügen der Reolink RLC-410-5MP Kamera danke @Dorsad
- RocketCam (Freebox) hinzufügen danke @JAG

# 26.06.2020

- Hinzufügen einer Dahua-Kamera
- Hinzufügung einer Foscam-Kamera
- Unterdrückung der Bewegungserkennung
- Hinzufügen von panasonic wc np502 danke @Flobul
- Hinzufügen von IOS-Kamera danke @Flobul

# 16.06.2020

- Behebung des Problems des Öffnens des Verlaufs im Flow-Modus

# 14.06.2020

- Verbesserung des Systems zum Bereinigen von Videostream-Dateien (Beachten Sie, dass sich die Funktion noch in der Beta-Phase befindet. Möglicherweise ist unsere Einstellung etwas zu aggressiv)
- Korrektur eines Fehlers bei der Wiederherstellung von Bewegungsinformationen von Foscam HD-Kameras
- Es wurde ein Problem behoben, durch das ein Benutzer ohne Administratorrechte den Videostream ansehen wollte
- Korrektur des Anzeigefehlers beim Design im Modus "Nur Flussmittel""
- Fehlerbehebungen

# 11/11/2020

- Konfiguration hinzufügen
- Möglichkeit, die Kamera im Videostream zu sehen (und nicht mehr Frame für Frame) - Beta
- Neugestaltung des Kamera-Vorkonfigurationssystems

# 05/11/2020

- Rückgabe der ONVIF-Erkennungsfunktion (danke @Aidom)
- Möglichkeit zur Verwendung von Befehlen (Skripttyp) zur Kamerasteuerung

# 2019.10.17

- Optimierung der Reinigungsfunktion von Kameraerfassungsdateien

# 2019.10.10

- Korrektur eines Fehlers beim Aufnehmen von Videos unter Debian 10

# 2019.09.25

- Fehlerbehebung auf RTSP in Debian 10

# 2019.09.23

- Fehlerbehebungen
- Hinzufügen eines Feldes zum Einfügen von Videostream-Optionen (rtsp)

# 2019.09.20

- Fehlerbehebungen

# 2019.09.14

 - Es wurde ein Fehler behoben, durch den die cronHourly-Aufgabe des Kamera-Plugins nicht beendet werden konnte
 - Korrektur eines Fehlers in der Vorschau des Erfassungsverlaufs

# 2019.08.28

- Verbesserte RTSP-Unterstützung
- Fehlerbehebungen

# 2019.06.06

- Ein Problem wurde behoben, wenn &amp; in der Stream-RTSP-URL vorhanden sind
- Verbesserte Sicherheit im Videostream
- Es wurde ein Problem behoben, bei dem in der Video-Stream-URL Leerzeichen vorhanden waren

# 21.01.2019

- Aktualisieren des Dokuments
- Korrektur eines Fehlers auf dem Panel

# 17.01.2019

- Aktualisieren des Dokuments
- Ein Problem mit der Konfiguration von Wanscam q3 (s) wurde behoben)

# 2019.01.15

- Hinzufügung der Wanscam q3 Kamera (s))
- Die Modusauswahl erfolgt automatisch basierend auf der Aufnahme-URL und dem Videostream (RTSP))
- Fehlerbehebungen
- Ersatz für hinzugefügt #username# und #password# in Bestellungen
- Korrektur eines Abhängigkeitsproblems

# 2018.01.06

- Bei der Neugestaltung des Panels wählen wir nun die Anzahl der Kameras pro Zeile in der Plugin-Konfiguration aus, deren Größe automatisch berechnet wird
- Verbesserung des mobilen Widgets
- Unterstützung für RTSP / MJPEG-Videostreams ... Bitte beachten Sie, dass eine Konvertierung durchgeführt wird. Diese wird nur verwendet, wenn Sie keine andere Wahl haben. Sie sollten Snapshots privilegieren (schneller und weniger belastbar) Jeedom)

# 2018.03.04

- Aktualisierung der Dokumentation

# 2018.03.10

- Aktualisieren des Dokuments
- Codeoptimierung

# 2018.03.05

- Fehlerbehebungen

# 2018.02.12

- Fehlerbehebungen
- Hinzufügen einer Option zum Deaktivieren der Bildkomprimierung auf der Jeedom-Seite
