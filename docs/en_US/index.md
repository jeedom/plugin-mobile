Plugin for using the Jeedom Mobile application.

The Jeedom mobile application requires the installation of this plugin in order to
the box can communicate with the Mobile application.

Mobile plugin configuration 
==============================

After installing the plugin, you just need to activate it :

![mobile1](../images/mobile1.png)

**Configuration**

To configure the plugin, you must add the phones that
will be able to access Jeedom.

To Add a phone : **Plugins** → **Communication** → **App
Mobile** → **Ajouter**

![mobile2](../images/mobile2.png)

Here are the parameters to enter :

-   **Name of mobile equipment** : Phone name

-   **Activer** : Enabling access for this mobile

-   **Mobile Type** : Phone OS selection (iOS, Android)

-   **Utilisateur** : User associated with this access

> **Tip**
>
> The choice of the user is important because it determines the
> equipment to which the latter will have access according to his rights.

![mobile3](../images/mobile3.png)

After saving, you will get a QRCode allowing
the application to configure itself.

Setup of plugins and commands received by the app 
=======================================================

After initializing the Mobile Plugin you have the option of
rework generic types of controls, plugins and parts.

![mobile10](../images/mobile10.png)

By clicking on a plugin, you can authorize it or not to chat
with the mobile app, and configure each of the generic types
associated with his orders.

![mobile11](../images/mobile11.png)

By clicking on a part, you can authorize it or not to be
present in the mobile application, and configure each of the types
generics associated with his orders.

![mobile12](../images/mobile12.png)

Mobile app configuration 
=====================================

You will find the applications on the mobile blinds :

**Android Google Play**

![Google Play FR](../images/Google_Play_FR.png)

**Apple App Store**

![App Store FR](../images/App_Store_FR.png)

First launch of the app 
--------------------------

On the 1st launch of the Mobile application, a tutorial will be offered
in order to assist you in configuring it.

After downloading and installing your Jeedom mobile app,
launch the application on your smartphone.

You then arrive in a configuration tutorial that we
advise to follow. Some steps have been done previously.

You will then have the choice between a manual configuration or
automatic by QRcode. If you choose the configuration by QRcode,
just flash the QRcode on the Mobile App plugin in
smartphone equipment created previously. In this case, the application will
automatically retrieve the entire configuration of your Jeedom and
connect automatically. When it is connected to your home via Wifi,
the application will automatically use the Jeedom ethernet address
internal to your network. When you are connected in 4G or 3G, there
will use your external address to connect to your Jeedom (by
example via Jeedom DNS service if you use it). If you choose
for manual configuration, in this case you will have to enter the
hand the internal and external IP addresses of your Jeedom. This option
is reserved for an informed public.

The application will synchronize and you will arrive on its home page
(preceded by a mini presentation guide).

Jeedom mobile app is now ready to work.

Favorites 
-----------

On the application you can have Favorites (shortcuts of
commands, plugins, scenarios).

Here is the procedure for creating them :

Click on one of the + on the home screen of the application :

![mobile dashboard 1](../images/mobile_dashboard_1.PNG)

You will arrive on the shortcut type selection page :

![mobile dashboard 2](../images/mobile_dashboard_2.PNG)

For example, we are going to take Action, so it offers us
Rooms / Objects :

![mobile dashboard 3](../images/mobile_dashboard_3.PNG)

You just need to select the action you want in
shortcut :

![mobile dashboard 4](../images/mobile_dashboard_4.PNG)

It is then possible to customize the color of it (for the
three colors are available) :

![mobile dashboard 5](../images/mobile_dashboard_5.PNG)

As well as the two associated texts :

![mobile dashboard 6](../images/mobile_dashboard_6.PNG) ![mobile
dashboard 7](../ images / mobile_dashboard_7.PNG)

Here, you have now a shortcut of your order (in the
version 1.1 On / Off commands are expected to appear on the
same key).

![mobile dashboard 8](../images/mobile_dashboard_8.PNG)

How to properly configure generic types 
============================================

Generic Types in the Mobile plugin 
------------------------------------------

Better than words, here is an example of typical generics for a
light with all its controls (see also the table Light plus
low) :

![generic type in plugin](../images/generic_type_in_plugin.jpg)

Application template tables 
---------------------------------------

### The lights #

Picture                           | Type generic               | Dev plugin part            | Description          |
:-----------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![LIGHT](../images/LIGHT_1.jpg) | `Lumière Bouton On`<br/>`Button Off Light` | `LIGHT_ON`<br/>`LIGHT_OFF`| presence of two buttons "ON" and "Off" no status feedback. |
![LIGHT](../images/LIGHT_2.jpg) | `Lumière Bouton On`<br/>`Button Off Light`<br/>`State Light` | `LIGHT_ON`<br/>`LIGHT_OFF`<br/>`LIGHT_STATE` | Status feedback present, the left button toggles between On and Off |
![LIGHT](../images/LIGHT_2.jpg) | `Lumière Bouton Toggle`<br/>`State Light` | `LIGHT_TOGGLE`<br/>`LIGHT_STATE` | Status feedback present, the left button toggles between On and Off |
![LIGHT](../images/LIGHT_3.jpg) | `Lumière Bouton On`<br/>`Button Off Light`<br/>`State Light`<br/>`Light Slider` | `LIGHT_ON`<br/>`LIGHT_OFF`<br/>`LIGHT_STATE`<br/>`LIGHT_SLIDER` | Status feedback present, the left button allows to switch between On and Off and the slider allows to control the intensity |
![LIGHT](../images/LIGHT_4.jpg) | `Lumière Bouton On`<br/>`Button Off Light`<br/>`State Light`<br/>`Light Slider`<br/>`Light Color (info)`<br/>`Light Color (action)`<br/>`Light Mode` (optional, it is used to have light modes, for example rainbow on Hue philips) | `LIGHT_ON`<br/>`LIGHT_OFF`<br/>`LIGHT_STATE`<br/>`LIGHT_SLIDER`<br/>`LIGHT_COLOR`<br/>`LIGHT_SET_COLOR`<br/>`LIGHT_MODE` | Status feedback present, the left button allows to switch between On and Off and the slider allows to control the intensity. In the circle the color of the lamp is present and when you click it you can change the color and activate a mode |

### The plugs #

Picture                           | Type generic               | Dev plugin part            | Description          |
:-----------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![ENERGY](../images/ENERGY_1.jpg) | `Prise Bouton On`<br/>`Button Off socket`| `ENERGY_ON`<br/>`ENERGY_OFF`| presence of two buttons "ON" and "Off" no status feedback. |
![ENERGY](../images/ENERGY_2.jpg) | `Prise Bouton On`<br/>`Button Off socket`<br/>`State Taking` | `ENERGY_ON`<br/>`ENERGY_OFF`<br/>`ENERGY_STATE` | Status feedback present, the left button toggles between On and Off |
![ENERGY](../images/ENERGY_3.jpg) | `Prise Bouton On`<br/>`Button Off socket`<br/>`State Taking`<br/>`Slider socket` | `ENERGY_ON`<br/>`ENERGY_OFF`<br/>`ENERGY_STATE`<br/>`ENERGY_SLIDER` | Status feedback present, the left button allows to switch between On and Off and the slider allows to control the intensity |

### Shutters #

Picture                           | Type generic               | Dev plugin part            | Description          |
:-----------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![FLAP](../images/FLAP_1.jpg)   | `Volet Bouton Monter`<br/>`Down button pane`<br/>`Stop Button Pane`<br/>`State pane` (optional) | `FLAP_UP`<br/>`FLAP_DOWN`<br/>`FLAP_STOP`<br/>`FLAP_STATE` (optional) | Presence of three buttons "Up", "Down", "Stop", optional status feedback. |
![FLAP](../images/FLAP_2.jpg)   | `Volet Bouton Monter`<br/>`Down button pane`<br/>`Stop Button Pane`<br/>`State pane`<br/>`Slider Button Pane` | `FLAP_UP`<br/>`FLAP_DOWN`<br/>`FLAP_STOP`<br/>`FLAP_STATE`<br/>`FLAP_SLIDER` | Presence of a slider, with an Up / Down button in Toggle (with status icon) |

### Flood #

Picture                           | Type generic               | Dev plugin part            | Description          |
:-----------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![FLOOD](../images/FLOOD.jpg)   | `Innondation`<br/>`Temperature` (optional)<br/>`Humidity` (optional)<br/>`Sabotage` (optional)|`FLOOD`<br/>`TEMPERATURE` (optional)<br/>`HUMIDITY` (optional)<br/>`HUMIDITY` (optional) | Allows you to have your complete flood sensor on a single line.

### Lock #

Picture                         | Type generic               | Dev plugin part            | Description          |
:---------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![LOCK](../images/LOCK.jpg)   | `Lock Etat`<br/>`Open Button Lock`<br/>`Lock Button Close` | `LOCK_STATE`<br/>`LOCK_OPEN`<br/>`LOCK_CLOSE` | Status feedback present, the left button toggles between on and off |

### Mermaid #

Picture                         | Type generic               | Dev plugin part            | Description          |
:---------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![SIREN](../images/SIREN.jpg)   | `Mermaid Etat`<br/>`Siren Button On`<br/>`Siren Button Off` | `SIREN_STATE`<br/>`SIREN_ON`<br/>`SIREN_OFF` | Status feedback present, the left button toggles between on and off |

### Smoke #

Picture                           | Type generic               | Dev plugin part            | Description          |
:-----------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![SMOKE](../images/SMOKE.jpg)   | `Smoke`<br/>`Temperature` (optional)|`SMOKE`<br/>`TEMPERATURE` (optional) | Allows you to have your complete smoke sensor on a single line.

### Temperature #

Picture                                       | Type generic               | Dev plugin part            | Description          |
:-----------------------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![TEMPERATURE](../images/TEMPERATURE.jpg)   | `Temperature`<br/>`Humidity` (optional)|`TEMPERATURE`<br/>`HUMIDITY` (optional) | See Picture.

### Presence #

Picture                                 | Type generic               | Dev plugin part            | Description          |
:-----------------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![PRESENCE](../images/PRESENCE.jpg)   | `Presence`<br/>`Temperature` (optional)<br/>`Brightness` (optional)<br/>`Humidity` (optional)<br/>`UV` (optional)<br/>`Sabotage` (optional)|`PRESENCE`<br/>`TEMPERATURE` (optional)<br/>`BRIGHTNESS` (optional)<br/>`HUMIDITY` (optional)<br/>`UV` (optional)<br/>`SABOTAGE` (optional) | See picture.

### Opening #

Picture                                       | Type generic               | Dev plugin part            | Description          |
:-----------------------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![OPENING](../images/OPENING.jpg)   | `Porte / Fenêtre`<br/>`Temperature` (optional)|`OPENING / OPENING_WINDOW`<br/>`TEMPERATURE` (optional) | See Picture (ie you can choose between window and door).

### Pilot wire #

Picture                               | Type generic               | Dev plugin part            | Description          |
:---------------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![HEATING](../images/HEATING.jpg)   | `Chauffage fil pilote Bouton ON`<br/>`Heating pilot wire OFF button`<br/>`Heating pilot wire State`<br/>`Button pilot wire heating` (optional) | `HEATING_ON`<br/>`HEATING_OFF`<br/>`HEATING_STATE`<br/>`HEATING_OTHER`|The ON / OFF and Status buttons allow you to create the button on the far left of the template and the `button pilot wire heating` are there to add buttons (5 max)

THE JOKERS 
----------

### Generic Action #

Picture                             | Type generic               | Dev plugin part            | Description          |
:-------------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![ACTION](../images/ACTION.jpg)   | `Action Générique`           | `GENERIC_ACTION`             | Le bouton prend la forme du type de l'action. Par défaut c'est un toggle, si c'est un message alors vous avez une enveloppe, si slider vous avez un slider etc...

### Generic Info #

Picture                         | Type generic               | Dev plugin part            | Description          |
:---------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![INFO](../images/INFO.jpg)   | `Information Générique`           | `GENERIC_INFO`             | Le bouton prend la forme du type de l'info.


Troubleshooting 
===============

Mobile Help 
-----------

**→ I'm on Android version of the app (1.0.1 or 1.0.0) i can't
no access to my rooms or even to the configuration of the app.**

> **Caution**
>
> You had a popup warning you of a concern about the parameters
> accessibility, you just need to go to the
> accessibility settings of your mobile and uncheck them
> applications using this option. (A fix will be made
> soon on the app)

**→ I have a message in one of the lines of my modules telling me that it
missing a Generic Type !**

> **Tip**
>
> By reading this message, it tells you which generic type is missing for
> create a compatible template. Just apply it.
> Refer to [doc chapter Type
> Générique](https://www.jeedom.com/doc/documentation/plugins/mobile/fr_FR/mobile#_configuration_des_plugins_et_commandes_que_reçoit_l_app).

**→ I have a problem with one of the so-called fully integrated plugins (weather,
thermostat, alarm, camera) !**

> **Tip**
>
> Do not hesitate to access your module and to click on
> save again, this will re-include the types
> credits associated with the module.

**→ Impossible to put an info on the welcome of the app !**

> **Tip**
>
> This is normal, it will be available on version 1.1.

**→ I have the application which takes up a lot of memory in my
Phone !**

> **Tip**
>
> There was a bug on versions 1.0.0 and 1.0.1 on the game
> Camera. The problem will not happen again with 1.0.2, to delete
> hide it without overpricing the app, just go to the configuration
> from your Mobile App and click on "delete cache".

**→ I have a concern of first synchronization on the app or of sql on the mobile plugin !**

> **Tip**
>
> you have to put generic types and authorize the plugin to send generics see the doc a little higher.
