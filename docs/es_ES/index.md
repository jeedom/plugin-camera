Plugin para crear y gestionar cámaras inalámbricas (pantalla y
registro)

configuración del plugin
=======================

Después de instalar el plugin, sólo hay que activarlo, sin embargo,
Unos ajustes de configuración avanzada:

-   **Documentos** Ruta de acceso: Indica la ruta donde debe Jeedom
    almacenar imágenes que captan las cámaras (no se recomienda
    tocarlo). Si la ruta no está en el camino
    Jeedom instalación a continuación, puede ver la
    capturas en Jeedom.

-   **El tamaño máximo de la carpeta de grabación (MB)** indica el
    el tamaño máximo permitido para la carpeta en la que la captura es
    grabada (que no debe ser tocado). Si esta cuota está
    alcanzado Jeedom eliminar las capturas más antiguos.

-   **El plug-in de la cámara debe reaccionar a las interacciones** palabras clave /
    frases a las que el plugin va a reaccionar a través de las interacciones de Jeedom.

-   ** ** Grupo: le permite ver el panel (Menú
    Inicio &gt; cámara) y tener una vista de la totalidad de su
    cámaras (véase más adelante)

Configuración del dispositivo
=============================

equipo
----------

Aquí tienes la información básica de la cámara:

-   **Nombre del equipo de la cámara**: su cámara nombre de equipo

-   **Objeto padre** : especifica el objeto padre al que pertenece
    equipo

-   ** ** Activar: para que su equipo activo

-   Visible ** **: hace visible en el tablero de instrumentos

-   ** ** IP: la dirección IP local de su cámara

-   ** ** Puerto: El puerto en el que montar la cámara

-   ** ** Protocolo: protocolo de comunicación de la cámara (http
    o https)

-   ** ** Nombre de usuario: nombre de usuario para conectarse a la
    La cámara (si es necesario)

-   ** ** Contraseña: contraseña para conectarse a la cámara
    (si es necesario)

-   Capturar ** ** URL: URL "instantánea" de la cámara. Cambio en
    cámaras basadas. Tenga cuidado de no poner un url flujo bajo
    planta pena de Jeedom. Puede añadir etiquetas \ #username \ #
    y \ #Password \ #, que será reemplazado automáticamente por el nombre
    usuario y la contraseña cuando se utiliza este
    orden

-   ** ** Modelo: selecciona el modelo de la cámara. Tenga cuidado si
    cambia que se sobreponen a los parámetros de configuración

imágenes
------

Esta sección le permite configurar la calidad de la imagen. En efecto
Jeedom reduce el tamaño de la imagen o comprimir antes de enviarlo a
su navegador. Esto ahorra la fluidez de las imágenes (porque
que son menos pesados). Es también en esta sección se puede
configurar el número de imágenes por segundo para mostrar. todos los ajustes
están disponibles en: Móvil / escritorio y miniatura / normal.

-   Actualizar (s): tiempo en segundos entre la pantalla 2
    imágenes (aquí puede poner la siguiente figura 1)

-   Compresión (%): cuanto menor es el menos la imagen se comprime en
    100% sin compresión se realiza

-   Tamaño (% - 0: Automático): cuanto mayor es el%, mayor es uno es
    cerca del tamaño de imagen original. En ningún 100%
    imagen de cambio de tamaño se produce

> **Nota**
>
> Si se pone una compresión de 0% y 100% el tamaño sí Jeedom
> No toque la imagen en modo normal. Esta no es la forma válida
> Miniatura o hay un tamaño máximo de la imagen de 360 ​​píxeles.

captura
-------

-   La duración máxima de una grabación (s): duración máxima de
    grabaciones

-   Siempre un vídeo: fuerza Jeedom gire siempre
    grabaciones de vídeo antes de grabar

-   Número de fotogramas por segundo del video: velocidad de fotogramas
    segundo vídeos

-   umbral de detección de movimiento (0-100): umbral de detección
    movimiento (se recomienda poner 2). A medida que el valor es grande
    más aumenta la sensibilidad.

-   Eliminar todas las capturas de la cámara: borra todos
    las capturas y la cámara graba

suministro
------------

-   A la orden: Comando permetant a conectar la alimentación
    la cámara

-   OFF: Comando permetant desconectar la alimentación de la
    cámara

comandos
---------

-   Identificación de la orden (usar con comandos de información de tipo de
    por ejemplo, hasta información de movimiento de cámara a Jeedom
    por API, ver abajo)

-   nombre del comando con la posibilidad de un icono de
    arriba (para borrar debe hacer doble clic en el icono
    en cuestión)

-   Tipo y subtipo del comando

-   Solicitud de envío de la cámara para hacer una acción (modo de transferencia
    noche PTZ, etc.). Se puede utilizar el \ etiquetas #username \ # y
    \ #Password \ #, que será reemplazado automáticamente por el nombre
    usuario y la contraseña cuando se utiliza este
    orden

-   Deja de orden: para cámaras PTZ a menudo hay una orden
    que se detiene el movimiento, esto es donde se especifica la

-   Ver: Muestra el orden o no en el salpicadero

-   Configuración avanzada (pequeñas ruedas dentadas): Muestra
    el control prolongado de la configuración (método de tala,
    flash, etc.)

-   Prueba: prueba el comando

-   Eliminar (signo -): eliminar comandos

el widget
=========

Nos encontramos en él la imagen de la cámara, que se define comandos
en la configuración, el comando para tomar una instantánea, control de
para empezar a tomar varias instantáneas y comando para
navegar por estas capturas.

> **Tip**
>
> En el salpicadero y el panel es posible cambiar el tamaño
> Widget para adaptarse a sus necesidades

Un clic en la imagen lo muestra en una ventana y
en un formato más grande.

Un clic en el último comando para navegar que coger
mostrarlo.

Aquí encontrará todas las capturas organizados por día y por
fecha en la que puede para cada uno:

-   ver más grande haciendo clic en la imagen

-   descargar

-   eliminar

En el widget móvil es un poco diferente si hace clic en la imagen
la cámara que lo consigue en más con los controles
posible.

paneles
==========

El plug-in cámara también proporciona un panel que le permite
ver a la vez todas sus cámaras, es accesible mediante Inicio →
Cámara.

> **Nota**
>
> Para que se tiene que activar la página de configuración del plugin

Por supuesto, es también disponible en móviles → plug-in de la cámara:

Almacenamiento y envío de captura
==================================

Este comando algo específico tras la captura de decisión
para enviarlo (plug-in compatible con holgura, correo electrónico y
transferencia)

La configuración es bastante simple que llamamos el envío de la acción
captura, en la parte del título se pasa opciones (por defecto
Sólo hay que poner el número de captura quería, pero se puede ir
desistido de las opciones avanzadas) y en la parte del mensaje de la orden
plug-in (actualmente holgura, correo electrónico o transferencia) hace que el envío
capturas. Se puede poner múltiples separados por &&.

Opciones avanzadas de captura
---------------------------

-   nbSnap: número de captura, si no se especifica a continuación, la captura es
    formado por una solicitud de detener la grabación o detener el
    cámara

-   Retardo: tiempo entre 2 captura, si no se especifica a continuación, la fecha límite es
    1s

-   esperar: período de espera antes de comenzar la captura, si no se
    precie entonces no envío se hace

-   sendPacket: número de captura de desencadenar el envío de paquetes, si no se
    entonces clara la captura se enviará sólo al final

-   detectMove = 1: el envío de la captura si un cambio mayor
    umbral de detección (véase la configuración de la cámara) llega

-   película = 1: Una vez finalizada la grabación, las imágenes son
    vídeo convertido

-   sendFirstSnap = 1: el envío de la primera captura de la grabación

El envío de la detección de movimiento para Jeedom
===========================================

Si usted tiene una cámara que cuenta con detección de movimiento y
que quiere transmitir a la Jeedom url aquí para poner en su
la cámara:

    http: //#IP_JEEDOM#/core/api/jeeApi.php apikey apikey = # # & type = & id = cámara # ID # & valor = # valor #

Se debe entender antes de crear un tipo de información de comandos
su cámara

Preguntas frecuentes
===

¿Dónde están las cintas?

: Las grabaciones son de forma predeterminada
    plugins / cámara / datos / registros / Identificación * \ * _cam, cuidado con esto puede variar si
    Jeedom que pidió guardar en otra parte

La lista de cámaras compatibles es
[Aquí] (https://github.com/jeedom/documentation/blob/master/camera/fr_FR/equipement.compatible.asciidoc)

cambios
=========

-   JEED-336: Adición del botón Historial de la vista de pantalla completa

-   pantalla de la cámara de administración rediseñada (ajuste de la
    compresión y tamaño de la imagen)


