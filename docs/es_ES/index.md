Complemento para usar la aplicación Jeedom Móvil.

La aplicación móvil Jeedom requiere la instalación de este complemento para
la caja puede comunicarse con la aplicación móvil.

Configuración de complemento móvil 
==============================

Después de instalar el complemento, solo necesita activarlo :

![mobile1](../images/mobile1.png)

**Configuration**

Para configurar el complemento, debe agregar los teléfonos que
podrá acceder a Jeedom.

Para agregar un teléfono : **Plugins** → **Communication** → **App
Mobile** → **Ajouter**

![mobile2](../images/mobile2.png)

Aquí están los parámetros para ingresar :

-   **Nombre del equipo móvil.** : Nombre del teléfono

-   **Activer** : Habilitar el acceso para este móvil

-   **Tipo de móvil** : Selección del sistema operativo del teléfono (iOS, Android)

-   **Utilisateur** : Usuario asociado con este acceso

> **Tip**
>
> La elección del usuario es importante porque determina la
> equipo al que este último tendrá acceso según sus derechos.

![mobile3](../images/mobile3.png)

Después de guardar, obtendrá un código QR que permite
la aplicación para configurarse.

Configuración de complementos y comandos recibidos por la aplicación 
=======================================================

Después de inicializar el complemento móvil, tiene la opción de
reelaborar tipos genéricos de controles, complementos y piezas.

![mobile10](../images/mobile10.png)

Al hacer clic en un complemento, puede autorizarlo o no a chatear
con la aplicación móvil y configure cada uno de los tipos genéricos
asociado con sus órdenes.

![mobile11](../images/mobile11.png)

Al hacer clic en una parte, puede autorizarla o no
presente en la aplicación móvil y configure cada uno de los tipos
genéricos asociados con sus órdenes.

![mobile12](../images/mobile12.png)

Configuración de la aplicación móvil 
=====================================

Encontrará las aplicaciones en las persianas móviles. :

**Android Google Play**

![Google Play FR](../images/Google_Play_FR.png)

**Apple App Store**

![App Store FR](../images/App_Store_FR.png)

Primer lanzamiento de la aplicación. 
--------------------------

En el primer lanzamiento de la aplicación móvil, se ofrecerá un tutorial
para ayudarlo a configurarlo.

Después de descargar e instalar su aplicación móvil Jeedom,
inicie la aplicación en su teléfono inteligente.

Luego llega a un tutorial de configuración que nosotros
aconseja seguir. Algunos pasos se han realizado previamente.

A continuación, podrá elegir entre una configuración manual o
automático por QRcode. Si elige la configuración por QRcode,
simplemente actualice el código QR en el complemento de la aplicación móvil en
equipo de teléfono inteligente creado previamente. En este caso, la aplicación
recupere automáticamente toda la configuración de su Jeedom y
conectarse automáticamente. Cuando está conectado a su hogar a través de Wifi,
la aplicación usará automáticamente la dirección de Ethernet de Jeedom
interno a su red. Cuando estás conectado en 4G o 3G, hay
usará su dirección externa para conectarse a su Jeedom (por
ejemplo a través del servicio DNS de Jeedom si lo usa). Si eliges
para la configuración manual, en este caso deberá ingresar el
entregar las direcciones IP internas y externas de su Jeedom. Esta opcion
está reservado para un público informado.

La aplicación se sincronizará y usted llegará a su página de inicio.
(precedido por una mini guía de presentación).

La aplicación móvil Jeedom ahora está lista para funcionar.

Favoritos 
-----------

En la aplicación puede tener Favoritos (accesos directos de
comandos, complementos, escenarios).

Aquí está el procedimiento para crearlos. :

Haga clic en uno de los + en la pantalla de inicio de la aplicación :

![mobile dashboard 1](../images/mobile_dashboard_1.PNG)

Llegará a la página de selección de tipo de acceso directo :

![mobile dashboard 2](../images/mobile_dashboard_2.PNG)

Por ejemplo, vamos a tomar medidas, por lo que nos ofrece
Salas / Objetos :

![mobile dashboard 3](../images/mobile_dashboard_3.PNG)

Solo necesita seleccionar la acción que desea en
atajo :

![mobile dashboard 4](../images/mobile_dashboard_4.PNG)

Entonces es posible personalizar el color (para
tres colores están disponibles) :

![mobile dashboard 5](../images/mobile_dashboard_5.PNG)

Así como los dos textos asociados :

![mobile dashboard 6](../images/mobile_dashboard_6.PNG) ![mobile
tablero de instrumentos 7](../ images / mobile_dashboard_7.PNG)

Aquí, ahora tiene un acceso directo a su pedido (en el
versión 1.1 Se espera que los comandos de encendido / apagado aparezcan en el
misma llave).

![mobile dashboard 8](../images/mobile_dashboard_8.PNG)

Cómo configurar correctamente los tipos genéricos 
============================================

Tipos genéricos en el complemento móvil 
------------------------------------------

Mejor que las palabras, aquí hay un ejemplo de genéricos típicos para un
light con todos sus controles (ver también la tabla Light plus
abajo) :

![generic type in plugin](../images/generic_type_in_plugin.jpg)

Tablas de plantillas de aplicación 
---------------------------------------

### Las luces #

Imagen                           | Tipo genérico               | Parte del complemento de desarrollo            | Descripción          |
:-----------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![LIGHT](../images/LIGHT_1.jpg) | `Lumière Bouton On`<br/>`Botón apagado luz` | `LIGHT_ON`<br/>`LIGHT_OFF`| presencia de dos botones "ON" y "Off" sin retroalimentación de estado. |
![LIGHT](../images/LIGHT_2.jpg) | `Lumière Bouton On`<br/>`Botón apagado luz`<br/>`State Light` | `LIGHT_ON`<br/>`LIGHT_OFF`<br/>`LIGHT_STATE` | Comentarios de estado presentes, el botón izquierdo alterna entre Encendido y Apagado |
![LIGHT](../images/LIGHT_2.jpg) | `Lumière Bouton Toggle`<br/>`State Light` | `LIGHT_TOGGLE`<br/>`LIGHT_STATE` | Comentarios de estado presentes, el botón izquierdo alterna entre Encendido y Apagado |
![LIGHT](../images/LIGHT_3.jpg) | `Lumière Bouton On`<br/>`Botón apagado luz`<br/>`State Light`<br/>`Light Slider` | `LIGHT_ON`<br/>`LIGHT_OFF`<br/>`LIGHT_STATE`<br/>`LIGHT_SLIDER` | Estado de retroalimentación presente, el botón izquierdo permite cambiar entre Encendido y Apagado y el control deslizante permite controlar la intensidad |
![LIGHT](../images/LIGHT_4.jpg) | `Lumière Bouton On`<br/>`Botón apagado luz`<br/>`State Light`<br/>`Light Slider`<br/>`Color claro (información)`<br/>`Color claro (acción)`<br/>`Modo de luz` (opcional, se utiliza para tener modos de luz, por ejemplo, arco iris en Hue philips) | `LIGHT_ON`<br/>`LIGHT_OFF`<br/>`LIGHT_STATE`<br/>`LIGHT_SLIDER`<br/>`LIGHT_COLOR`<br/>`LIGHT_SET_COLOR`<br/>`LIGHT_MODE` | Estado de retroalimentación presente, el botón izquierdo permite cambiar entre Encendido y Apagado y el control deslizante permite controlar la intensidad. En el círculo, el color de la lámpara está presente y, al hacer clic en él, puede cambiar el color y activar un modo. |

### Los enchufes #

Imagen                           | Tipo genérico               | Parte del complemento de desarrollo            | Descripción          |
:-----------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![ENERGY](../images/ENERGY_1.jpg) | `Prise Bouton On`<br/>`Toma de botón apagado`| `ENERGY_ON`<br/>`ENERGY_OFF`| presencia de dos botones "ON" y "Off" sin retroalimentación de estado. |
![ENERGY](../images/ENERGY_2.jpg) | `Prise Bouton On`<br/>`Toma de botón apagado`<br/>`Toma de estado` | `ENERGY_ON`<br/>`ENERGY_OFF`<br/>`ENERGY_STATE` | Comentarios de estado presentes, el botón izquierdo alterna entre Encendido y Apagado |
![ENERGY](../images/ENERGY_3.jpg) | `Prise Bouton On`<br/>`Toma de botón apagado`<br/>`Toma de estado`<br/>`Toma deslizante` | `ENERGY_ON`<br/>`ENERGY_OFF`<br/>`ENERGY_STATE`<br/>`ENERGY_SLIDER` | Estado de retroalimentación presente, el botón izquierdo permite cambiar entre Encendido y Apagado y el control deslizante permite controlar la intensidad |

### Los boletos #

Imagen                           | Tipo genérico               | Parte del complemento de desarrollo            | Descripción          |
:-----------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![FLAP](../images/FLAP_1.jpg)   | `Volet Bouton Monter`<br/>`Panel de botones hacia abajo`<br/>`Panel de botones de parada`<br/>`Panel de estado` (opcional) | `FLAP_UP`<br/>`FLAP_DOWN`<br/>`FLAP_STOP`<br/>`FLAP_STATE` (opcional) | Presencia de tres botones "Arriba", "Abajo", "Detener", retroalimentación de estado opcional. |
![FLAP](../images/FLAP_2.jpg)   | `Volet Bouton Monter`<br/>`Panel de botones hacia abajo`<br/>`Panel de botones de parada`<br/>`Panel de estado`<br/>`Panel del botón deslizante` | `FLAP_UP`<br/>`FLAP_DOWN`<br/>`FLAP_STOP`<br/>`FLAP_STATE`<br/>`FLAP_SLIDER` | Presencia de un control deslizante, con un botón Arriba / Abajo en Toggle (con icono de estado) |

### Inundación #

Imagen                           | Tipo genérico               | Parte del complemento de desarrollo            | Descripción          |
:-----------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![FLOOD](../images/FLOOD.jpg)   | `Innondation`<br/>`Temperatura` (opcional)<br/>`Humedad` (opcional)<br/>`Sabotaje` (opcional)|`FLOOD`<br/>`TEMPERATURA` (opcional)<br/>`HUMEDAD` (opcional)<br/>`HUMEDAD` (opcional) | Le permite tener su sensor de inundación completo en una sola línea.

### Bloquear #

Imagen                         | Tipo genérico               | Parte del complemento de desarrollo            | Descripción          |
:---------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![LOCK](../images/LOCK.jpg)   | `Bloquear Etat`<br/>`Bloqueo de botón abierto`<br/>`Cerrar el botón de bloqueo` | `LOCK_STATE`<br/>`LOCK_OPEN`<br/>`LOCK_CLOSE` | Comentarios de estado presentes, el botón izquierdo alterna entre encendido y apagado |

### Sirena #

Imagen                         | Tipo genérico               | Parte del complemento de desarrollo            | Descripción          |
:---------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![SIREN](../images/SIREN.jpg)   | `Sirena Etat`<br/>`Siren Button On`<br/>`Siren Button Off` | `SIREN_STATE`<br/>`SIREN_ON`<br/>`SIREN_OFF` | Comentarios de estado presentes, el botón izquierdo alterna entre encendido y apagado |

### Humo #

Imagen                           | Tipo genérico               | Parte del complemento de desarrollo            | Descripción          |
:-----------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![SMOKE](../images/SMOKE.jpg)   | `Humo`<br/>`Temperatura` (opcional)|`SMOKE`<br/>`TEMPERATURA` (opcional) | Le permite tener su sensor de humo completo en una sola línea.

### Temperatura #

Imagen                                       | Tipo genérico               | Parte del complemento de desarrollo            | Descripción          |
:-----------------------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![TEMPERATURE](../images/TEMPERATURE.jpg)   | `Temperatura`<br/>`Humedad` (opcional)|`TEMPERATURE`<br/>`HUMEDAD` (opcional) | Ver imagen.

### Presencia #

Imagen                                 | Tipo genérico               | Parte del complemento de desarrollo            | Descripción          |
:-----------------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![PRESENCE](../images/PRESENCE.jpg)   | `Presencia`<br/>`Temperatura` (opcional)<br/>`Brillo` (opcional)<br/>`Humedad` (opcional)<br/>`UV` (opcional)<br/>`Sabotaje` (opcional)|`PRESENCE`<br/>`TEMPERATURA` (opcional)<br/>`BRILLO` (opcional)<br/>`HUMEDAD` (opcional)<br/>`UV` (opcional)<br/>`SABOTAGE` (opcional) | Ver foto.

### Apertura #

Imagen                                       | Tipo genérico               | Parte del complemento de desarrollo            | Descripción          |
:-----------------------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![OPENING](../images/OPENING.jpg)   | `Porte / Fenêtre`<br/>`Temperatura` (opcional)|`OPENING / OPENING_WINDOW`<br/>`TEMPERATURA` (opcional) | Ver imagen (es decir, puede elegir entre ventana y puerta).

### Cable piloto #

Imagen                               | Tipo genérico               | Parte del complemento de desarrollo            | Descripción          |
:---------------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![HEATING](../images/HEATING.jpg)   | `Chauffage fil pilote Bouton ON`<br/>`Botón de apagado del cable piloto de calentamiento`<br/>`Calentar el estado del cable piloto`<br/>`Botón de calentamiento del cable piloto` (opcional) | `HEATING_ON`<br/>`HEATING_OFF`<br/>`HEATING_STATE`<br/>`HEATING_OTHER`|Los botones ON / OFF y Status le permiten crear el botón en el extremo izquierdo de la plantilla y el `botón de calentamiento del cable piloto 'está ahí para agregar botones (5 máx.)

Los bromistas 
----------

### Acción genérica #

Imagen                             | Tipo genérico               | Parte del complemento de desarrollo            | Descripción          |
:-------------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![ACTION](../images/ACTION.jpg)   | `Action Générique`           | `GENERIC_ACTION`             | Le bouton prend la forme du type de l'action. Par défaut c'est un toggle, si c'est un message alors vous avez une enveloppe, si slider vous avez un slider etc...

### Información genérica #

Imagen                         | Tipo genérico               | Parte del complemento de desarrollo            | Descripción          |
:---------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![INFO](../images/INFO.jpg)   | `Information Générique`           | `GENERIC_INFO`             | Le bouton prend la forme du type de l'info.


Solución de problemas 
===============

Ayuda móvil 
-----------

**→ Estoy en la versión de Android de la aplicación (1.0.1 o 1.0.0) no puedo
sin acceso a mis habitaciones o incluso a la configuración de la aplicación.**

> **Caution**
>
> Tuviste una ventana emergente que te advirtió de una preocupación sobre los parámetros
> accesibilidad, solo necesitas ir al
> configuración de accesibilidad de su móvil y desmarque
> aplicaciones que usan esta opción. (Se arreglará
> pronto en la aplicación)

**→ Tengo un mensaje en una de las líneas de mis módulos diciéndome que
falta un tipo genérico !**

> **Tip**
>
> Al leer este mensaje, le indica qué tipo genérico falta para
> crear una plantilla compatible. Solo aplícalo.
> Consulte [doc capítulo Tipo
> Générique](https://www.jeedom.com/doc/documentation/plugins/mobile/fr_FR/mobile#_configuration_des_plugins_et_commandes_que_reçoit_l_app).

**→ Tengo un problema con uno de los llamados complementos totalmente integrados (clima,
termostato, alarma, cámara) !**

> **Tip**
>
> No dude en acceder a su módulo y hacer clic en
> guardar de nuevo, esto volverá a incluir los tipos
> créditos asociados al módulo.

**→ Imposible poner una información sobre la bienvenida de la aplicación !**

> **Tip**
>
> Esto es normal, estará disponible en la versión 1.1.

**→ Tengo la aplicación que ocupa mucha memoria en mi
Teléfono !**

> **Tip**
>
> Hubo un error en las versiones 1.0.0 y 1.0.1 en el juego
> Cámara. El problema no volverá a suceder con 1.0.2, para eliminar
> ocultarlo sin sobrevalorar la aplicación, solo vaya a la configuración
> desde su aplicación móvil y haga clic en "Eliminar caché".

**→ Me preocupa la primera sincronización en la aplicación o sql en el complemento móvil !**

> **Tip**
>
> tienes que poner tipos genéricos y autorizar el complemento para enviar genéricos ver el documento un poco más alto.
