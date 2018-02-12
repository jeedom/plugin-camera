Plugin for creating and managing wifi cameras (display and
recording)

== Plugin configuration

After installing the plugin, you just have to activate it, however
there are some advanced configuration settings:

-   **Records Path**: indicates the path where Jeedom must
    store the images that it captures from your cameras (it is not recommended
    to touch it). If your path is not in the way
    installing Jeedom then you will only be able to view the
    catches in Jeedom.

-   **Maximum size of the recording folder (MB)**: indicates the
    maximum size allowed for the folder where the captures are
    recorded (it is not recommended to touch it). If this quota is
    reaches Jeedom will delete the oldest captures.

-   **The camera plugin must react to interactions**: keywords /
    sentences that the plugin will react via Jeedom's interactions.

-   **Panel**: Allows you to display the panel (Menu
    Home &gt; Camera) and have a view on all of your
    cameras (see below)

Equipment configuration
=============================

Equipment
----------

Here you have the main information of your camera:

-   **Name of camera equipment**: name of your camera equipment

-   **Parent Object** : means the parent object the equipment depend
    equipment

-   **Enable**: makes your equipment active

-   **Visible**: makes it visible on the dashboard

-   **IP**: the local IP address of your camera

-   **Port**: the port for which to attach the camera

-   **Protocol**: the communication protocol of your camera (http
    or https)

-   **Username**: username to login to the
    camera (if necessary)

-   **Password**: password to connect to the camera
    (if necessary)

-   **Capture URL**: Snapshot URL of the camera. Changed into
    function of the cameras. Be careful not to put a feed url under
    to plant Jeedom. You can add \ #username \ # tags
    and \ #password \ #, which will automatically be replaced by the name
    user name and password when using this
    order

-   **Model**: Choose the model of the camera. Attention if
    you change that will overwrite your configuration settings

imagery
------

This part allows you to configure the quality of the image. Indeed
Jeedom decreases the size of the image or compresses it before sending it to
your browser. This helps to increase the fluidity of the images (because
they are less heavy). It's also in this part that you can
set the number of frames per second to display. All settings
are available in: mobile / desktop and miniature / normal.

-   Refreshment (s): delay in seconds between the display of 2
    images (here you can put numbers less than 1)

-   Compression (%): the lower it is, the less the image is compressed,
    100% no compression is done

-   Size (% - 0: automatic): the higher the% the higher one is
    close to the original size of the image. 100% none
    image resizing does not take place

> **Note**
>
> If you put a compression of 0% and a size of 100% Jeedom does not
> will not touch the image in normal mode. This is not valid in
> Miniature or there is a maximum image size of 360px.

Capture
-------

-   Maximum duration of a recording (s): maximum duration of
    recordings

-   Always make a video: force Jeedom to always transform the
    video recordings before recording

-   Number of frames per second of the video: number of frames per
    second videos

-   Motion detection threshold (0-100): detection threshold of
    movement (it is advisable to put 2). More value is big
    the more the sensitivity increases.

-   Delete all captures from the camera: delete all
    camera captures and recordings

Food
------------

-   ON command: Command to turn on the power supply
    from the camera

-   OFF command: command to switch off the power supply of the
    camera

Orders
---------

-   ID of the command (use with info type commands for by
    example back up the motion information from the camera to Jeedom
    by API, see below)

-   Name of the order with the possibility to put an icon to the
    place (to remove it you must double click on the icon
    in question)

-   Type and subtype of the order

-   Query to send to the camera to do an action (switch to mode
    night, ptz, etc.). You can use \ #username \ # tags and
    \ #password \ #, which will automatically be replaced by the name
    user name and password when using this
    order

-   Stop command: for PTZ cameras there is often a command
    which stops the movement, it is here that it must be specified

-   Show: to display the command or not on the dashboard

-   Advanced configuration (small wheels): displays
    the advanced configuration of the command (historization method,
    widget, etc.)

-   Test: test the command

-   Delete (sign -): delete the command

The widget
=========

We find on this one the image of the camera, the commands defined
in the configuration, the command to take a capture, the command
to start taking multiple image captures and ordering for
browse these captures.

> **Tip**
>
> On the dashboard and the panel it is possible to resize the
> widget to adapt it to his needs

Clicking on the image displays it in a window and
in a larger format.

A click on the last command to browse the captures you
will display this one.

You find here all the catches organized by day then by
date, you can for each of them:

-   see it bigger by clicking on the image

-   download it

-   delete it

In mobile the widget is a bit different if you click on the image of
the camera you get this one in bigger with the controls
possible.

The panels
==========

The camera plugin also provides a panel that allows you to
see all your cameras at once, it is accessible by Home →
Camera.

> **Note**
>
> To have it you have to activate it on the plugin configuration page

It is of course also available in mobile by Plugin → Camera:

Saving and sending capture
==================================

This command a little specific allows following the catch of capture of
send it (compatible with the plugin slack, mail and
transfer)

The configuration is simple enough you call the send action of
capture, in the title section you pass the options (by default it
you just have to put the number of capture wanted but you can go more
far with the advanced options) and in the message part the command of the
plugin (currently slack, mail or transfer) which sends the
catches. You can put several separated by &&.

Advanced capture options
---------------------------

-   nbSnap: number of captures, if not specified then the catches are
    made up to a request to stop the recording or arrest of the
    camera

-   delay: delay between 2 capture, if not specified then the delay is
    1s

-   wait: timeout before starting the captures, if not
    specified then no sending is done

-   sendPacket: number of snapshot triggering sending packet, if not
    specified then the catches will be sent at the end

-   detectMove = 1: send the captures only if a change greater than
    detection threshold (see camera configuration) arrives

-   movie = 1: Once the recording is finished, the images are
    converted to video

-   sendFirstSnap = 1: send the first capture of the record

Sending motion detection to Jeedom
===========================================

If you have a camera that has motion detection and that
you want to transmit this one to Jeedom here is the url to put on your
camera:

    http: //#IP_JEEDOM#/core/api/jeeApi.php apikey APIKEY = # # & type = camera & id = # ID # & value = # value #

You must of course before creating an info command on
your camera

FAQ
===

Where are the recordings?

: Records are by default in
    plugins / camera / data / records / * ID \ _CAM *, be careful this may vary if
    you asked Jeedom to register them elsewhere

The list of compatible cameras is
[Here] (https://github.com/jeedom/documentation/blob/master/camera/fr_FR/equipement.compatible.asciidoc)

Changelog
=========

-   JEED-336: Addition of the history button on the full screen view

-   Redesign of camera display management (setting the
    compression and size of the image)


