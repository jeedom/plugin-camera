Plugin to create and manage wifi cameras (display and recording)

Plugin configuration
=======================

After installing the plugin, you just need to activate it, however there are some advanced configuration parameters :

-   **Record path** : indicates the path where Jeedom should store the images it captures from your cameras (it is not recommended to touch it). If your path is not in the Jeedom installation path then you will not be able to view the captures in Jeedom.

-   **Maximum recording folder size (MB)** : indicates the maximum size authorized for the folder where the captures are saved (it is not recommended to touch them). If this quota is reached Jeedom will delete the oldest catches.

-   **The camera plugin must react to interactions** : keywords / phrases to which the plugin will react via Jeedom interactions.

-   **panel** : Allows you to display the panel (Home Menu -&gt; Camera) and to have a view of all of your cameras (see below). Do not forget to activate the panel in the configuration of the plugin to access it later.


Equipment configuration
=============================

Equipment
----------

Here you have the main information of your camera :

-   **Camera equipment name** : name of your camera equipment

-   **Parent object** : indicates the parent object to which the equipment belongs

-   **Activate** : makes your equipment active

-   **Visible** : makes it visible on the dashboard

-   **IP** : the local IP address of your camera

-   **Harbor** : the port for which to attach the camera

-   **Protocol** : the communication protocol of your camera (http or https)

-   **username** : username to log in to the camera (if necessary). Please note that the plugin does not support special characters (you must therefore limit yourself to numbers, lowercase / uppercase letters)

-   **Password** : password to connect to the camera (if necessary).Please note that the plugin does not support special characters (you must therefore limit yourself to numbers, lowercase / uppercase letters)

-   **Snapshot URL** : Camera snapshot URL. Changes depending on the cameras. Be careful not to put a flow url under penalty of crashing Jeedom. You can add the tags \ #username \ # and \ #password \ #, which will be automatically replaced by the username and password when using this command

-   **Feed URL** : url of the video stream of the camera type rtsp: // # username #: #password # @ # ip #: 554 / videoMain (example for Foscam cameras)

-   **Model** : allows you to choose the camera model. Be careful if you change this will overwrite your configuration settings

imagery
------

This part allows you to configure the image quality. Indeed Jeedom decreases the size of the image or the compress before sending it to your browser. This allows the images to be more fluid (because they are lighter). It is also in this part that you can configure the number of images per second to display. All settings are available in: mobile / desktop and miniature / normal.

-   Refresh (s): delay in seconds between the display of 2 images (here you can put numbers less than 1)

-   Compression (%): the lower it is the less the image is compressed, at 100% no compression is done

-   Size (% - 0: automatic): the higher the%, the closer we are to the original size of the image. At 100% no resizing of the image takes place

> **Note**
>
> If you put a compression of 0% and a size of 100%, Jeedom will not touch the image in normal mode. This is not valid in miniature mode where there is a maximum image size of 360px.

Capture
-------

-   Maximum duration of a recording: maximum duration of recordings

-   Always make a video: Jeedom forces to always transform the recordings into video before recording

-   Number of frames per second of video: number of frames per second of videos

-   Motion detection threshold (0-100): motion detection threshold (it is advisable to set 2). The higher the value, the more the sensitivity increases.

-   Delete all captures from camera: delete all captures and recordings from camera

Food
------------

-   ON command: Command for switching on the power supply to the camera

-   OFF command: Command to cut the power to the camera

Orders
---------

-   Order ID (use with info type commands to, for example, bring the movement information from the camera to Jeedom via the API, see below)

-   Name of the command with the possibility of putting an icon instead (to delete it you must double-click on the icon in question)

-   Order type and subtype

-   Request to send to the camera to perform an action (switch to night mode, ptz, etc.). You can use the tags \ #username \ # and \ #password \ #, which will be automatically replaced by the username and password when using this command

-   Stop command: for PTZ cameras, there is often a command that stops the movement, this is where it must be specified

-   Display: allows you to display the order or not on the dashboard

-   Advanced configuration (small notched wheels): allows you to display the advanced configuration of the command (logging method, widget, etc.)

-   Test: allows you to test the command

-   Delete (sign -): allows you to delete the command

The widget
=========

We find on this one the image of the camera, the commands defined in the configuration, the command to take a capture, the command to start taking multiple image captures and the command to browse these captures.

> **Tip**
>
> On the dashboard and the panel it is possible to resize the widget to adapt it to your needs

Clicking on the image displays it in a window and in a larger format.

A click on the last command to browse the screenshots will display this one.

You will find here all the catches organized by day then by date, you can for each of them :

-   see it bigger by clicking on the image

-   download it

-   delete it

In mobile the widget is a little different: if you click on the image of the camera you get this larger with the possible commands.

The panels
==========

The camera plugin also provides a panel that allows you to see all your cameras at once, it is accessible by Home → Camera.

> **Note**
>
> To have it you have to activate it on the plugin configuration page

It is of course also available in mobile by Plugin → Camera :

Save and send capture
==================================

This somewhat specific command allows the capture to send it (compatible with the slack, mail and transfer plugin).

The configuration is quite simple, you call the send send action (called &quot;Recording&quot;) in a scenario. In the title part you pass the options.

By default, just put the number of captures you want in the &quot;number of captures or options&quot; field, but you can go further with options (see details below &quot;advanced options of captures&quot;). In the message part, you just have to fill in the order of the plugin (currently slack, email or transfer) which sends the captures. You can put several separated by &amp;&amp;.

Advanced capture options
---------------------------

-   `nbSnap` : nombre de capture, si non précisé alors les captures sont faites jusqu'à une demande d'arrêt d'enregistrement ou d'arrêt de la caméra

-   `delay` : délai entre 2 captures, si non précisé alors le délai est de 1s

-   `wait` : délai d'attente avant de commencer les captures, si non précié alors aucun envoi n'est fait

-   `sendPacket` : nombre de captures déclenchant l'envoi de paquet de captures, si non précisé alors les captures ne seront envoyées qu'à la fin

-   `detectMove=1` : envoi les captures que s'il y a un changement supérieur au seuil de détection (voir configuration de la caméra)

-   `movie=1` : une fois l'enregistrement terminé, les images sont converties en vidéo

-   `sendFirstSnap=1` : envoi la première capture de l'enregistrement

> **Examples**
>
> nbSnap = 3 delay = 5 ==&gt; send 3 captures made at 5 second intervals (send triggered via the scenario) movie = 1 sendFirstSnap = 1 detectMove = 1 ==&gt; send the first capture, then send a capture to each motion detection and record a video until the &quot;Stop Recording&quot; command to insert in the scenario. The film will be stored on your Jeedom.


Send motion detection to Jeedom
===========================================

If you have a camera that has motion detection and you want to transmit it to Jeedom this is the url to put on your camera :

    http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=camera&id=#ID#&value=#value#

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
