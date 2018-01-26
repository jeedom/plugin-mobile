Plugin permettant d’utiliser l’application Mobile Jeedom.

L’application mobile Jeedom nécessite l’installation de ce plugin afin
que la box puisse dialoguer avec l’application Mobile.

Configuration du plugin Mobile 
==============================

Après installation du plugin, il vous suffit de l’activer :

![mobile1](../images/mobile1.png)

**Configuration**

Pour configurer le plugin, vous devez ajouter les téléphones qui
pourront accéder à Jeedom.

Pour Ajouter un téléphone : **Plugins** → **Communication** → **App
Mobile** → **Ajouter**

![mobile2](../images/mobile2.png)

Voici les paramètres à renseigner :

-   **Nom de l’équipement mobile** : Nom du téléphone

-   **Activer** : Activation de l’accès pour ce mobile

-   **Type de Mobile** : Sélection de l’OS du téléphone (iOS, Android)

-   **Utilisateur** : Utilisateur associé à cet accès

> **Tip**
>
> Le choix de l’utilisateur est important car il détermine les
> équipements auxquels celui-ci aura accès en fonction de ses droits.

![mobile3](../images/mobile3.png)

Après avoir sauvegardé, vous obtiendrez un QRCode permettant à
l’application de se configurer toute seule.

Configuration des plugins et commandes que reçoit l’app 
=======================================================

Après l’initialisation du Plugin Mobile vous avez la possibilité de
remanier les types génériques des commandes, des plugins et des pièces.

![mobile10](../images/mobile10.png)

En cliquant sur un plugin, vous pouvez l’autoriser ou non à dialoguer
avec l’application mobile, et configurer chacun des types génériques
associés à ses commandes.

![mobile11](../images/mobile11.png)

En cliquant sur une pièce, vous pouvez l’autoriser ou non à être
présente dans l’application mobile, et configurer chacun des types
génériques associés à ses commandes.

![mobile12](../images/mobile12.png)

Configuration de l’application Mobile 
=====================================

Vous trouverez les applications sur les stores mobiles :

**Android Google Play**

![Google Play FR](../images/Google_Play_FR.png)

**Apple App Store**

![App Store FR](../images/App_Store_FR.png)

Premier lancement de l’app 
--------------------------

Au 1er lancement de l’application Mobile, un tutorial vous sera proposé
afin de vous accompagner dans la configuration de celle-ci.

Après avoir téléchargée et installée votre application mobile Jeedom,
lancez l’application sur votre smartphone.

Vous arrivez alors dans un didacticiel de configuration que nous vous
conseillons de suivre. Certaines étapes ont étés faites précédemment.

Vous aurez ensuite le choix entre une configuration manuelle ou
automatique par QRcode. Si vous optez pour la configuration par QRcode,
il suffit de flasher le QRcode présent sur le plugin App Mobile dans
l’équipement smartphone créé précédemment. Dans ce cas, l’application va
récupérer automatiquement toute la configuration de votre Jeedom et se
connecter automatiquement. Lorsqu’il sera connecté chez vous en Wifi,
l’application utilisera automatiquement l’adresse Jeedom ethernet
interne à votre réseau. Lorsque vous serez connecté en 4G ou 3G, il
utilisera votre adresse externe pour se connecter à votre Jeedom (par
exemple via le service DNS Jeedom si vous l’utilisez). Si vous optez
pour la configuration manuelle, dans ce cas il vous faudra entrer à la
main les adresses IP interne et externe de votre Jeedom. Cette option
est réservée à un public averti.

L’application va se synchroniser et vous arrivez sur sa page d’accueil
(précédée par un mini guide de présentation).

L’application mobile Jeedom est maintenant prête à fonctionner.

Les Favoris 
-----------

Sur l’application vous pouvez avoir des Favoris (raccourcis de
commandes, plugins, scénarios).

Voici donc la marche à suivre pour en créer :

Cliquez sur un des + sur l’écran d’accueil de l’application :

![mobile dashboard 1](../images/mobile_dashboard_1.PNG)

Vous arriverez sur la page de sélection du type de raccourci :

![mobile dashboard 2](../images/mobile_dashboard_2.PNG)

Par exemple, nous allons prendre Action, il nous propose donc des
Pièces/Objets :

![mobile dashboard 3](../images/mobile_dashboard_3.PNG)

Il vous suffit alors de sélectionner l’action que vous souhaitez en
raccourci :

![mobile dashboard 4](../images/mobile_dashboard_4.PNG)

Il est ensuite possible de personnaliser la couleur de celle-ci (pour le
moment trois couleurs sont proposées) :

![mobile dashboard 5](../images/mobile_dashboard_5.PNG)

Ainsi que les deux textes associés :

![mobile dashboard 6](../images/mobile_dashboard_6.PNG) ![mobile
dashboard 7](../images/mobile_dashboard_7.PNG)

Voilà, vous avez maitenant un raccourci de votre commande (dans la
version 1.1 il est prévu que les commandes On/Off apparaissent sur la
même touche).

![mobile dashboard 8](../images/mobile_dashboard_8.PNG)

Comment bien configurer ses types génériques 
============================================

Les Génériques Types dans le plugin Mobile 
------------------------------------------

Mieux que des mots, voici un exemple des génériques types pour une
lumière avec toutes ses commandes (voir aussi le tableau Lumière plus
bas) :

![generic type in plugin](../images/generic_type_in_plugin.jpg)

Tableaux des templates de l’application 
---------------------------------------

### Les Lumières #

Image                           | type générique               | Partie Dev plugin            | Description          |
:-----------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![LIGHT](../images/LIGHT_1.jpg) | `Lumière Bouton On`<br/>`Lumière Bouton Off` | `LIGHT_ON`<br/>`LIGHT_OFF`| présence de deux boutons "ON" et "Off" pas de retour d'état. |
![LIGHT](../images/LIGHT_2.jpg) | `Lumière Bouton On`<br/>`Lumière Bouton Off`<br/>`Lumière Etat` | `LIGHT_ON`<br/>`LIGHT_OFF`<br/>`LIGHT_STATE` | Retour d'état présent, le bouton de gauche permet de switcher entre On et Off |
![LIGHT](../images/LIGHT_2.jpg) | `Lumière Bouton Toggle`<br/>`Lumière Etat` | `LIGHT_TOGGLE`<br/>`LIGHT_STATE` | Retour d'état présent, le bouton de gauche permet de switcher entre On et Off |
![LIGHT](../images/LIGHT_3.jpg) | `Lumière Bouton On`<br/>`Lumière Bouton Off`<br/>`Lumière Etat`<br/>`Lumière Slider` | `LIGHT_ON`<br/>`LIGHT_OFF`<br/>`LIGHT_STATE`<br/>`LIGHT_SLIDER` | Retour d'état présent, le bouton de gauche permet de switcher entre On et Off et le slider permet de contrôler l'intensité |
![LIGHT](../images/LIGHT_4.jpg) | `Lumière Bouton On`<br/>`Lumière Bouton Off`<br/>`Lumière Etat`<br/>`Lumière Slider`<br/>`Lumière Couleur (info)`<br/>`Lumière Couleur (action)`<br/>`Lumière Mode` (optionnel, il sert à avoir des mode de lumière,par exemple arc-en-ciel sur les philips Hue) | `LIGHT_ON`<br/>`LIGHT_OFF`<br/>`LIGHT_STATE`<br/>`LIGHT_SLIDER`<br/>`LIGHT_COLOR`<br/>`LIGHT_SET_COLOR`<br/>`LIGHT_MODE` | Retour d'état présent, le bouton de gauche permet de switcher entre On et Off et le slider permet de contrôler l'intensité. Dans le cercle la couleur de la lampe est présente et lors d'un cloc dans celui-ci vous pouvez changer la couleur et activer un mode |

### Les Prises #

Image                           | type générique               | Partie Dev plugin            | Description          |
:-----------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![ENERGY](../images/ENERGY_1.jpg) | `Prise Bouton On`<br/>`Prise Bouton Off`| `ENERGY_ON`<br/>`ENERGY_OFF`| présence de deux boutons "ON" et "Off" pas de retour d'état. |
![ENERGY](../images/ENERGY_2.jpg) | `Prise Bouton On`<br/>`Prise Bouton Off`<br/>`Prise Etat` | `ENERGY_ON`<br/>`ENERGY_OFF`<br/>`ENERGY_STATE` | Retour d'état présent, le bouton de gauche permet de switcher entre On et Off |
![ENERGY](../images/ENERGY_3.jpg) | `Prise Bouton On`<br/>`Prise Bouton Off`<br/>`Prise Etat`<br/>`Prise Slider` | `ENERGY_ON`<br/>`ENERGY_OFF`<br/>`ENERGY_STATE`<br/>`ENERGY_SLIDER` | Retour d'état présent, le bouton de gauche permet de switcher entre On et Off et le slider permet de contrôler l'intensité |

### Les Volets #

Image                           | type générique               | Partie Dev plugin            | Description          |
:-----------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![FLAP](../images/FLAP_1.jpg)   | `Volet Bouton Monter`<br/>`Volet Bouton Descendre`<br/>`Volet Bouton Stop`<br/>`Volet Etat`(optionnel) | `FLAP_UP`<br/>`FLAP_DOWN`<br/>`FLAP_STOP`<br/>`FLAP_STATE`(optionnel) | Présence de trois boutons "Monter", "Descendre", "Stop", retour d'état optionnel. |
![FLAP](../images/FLAP_2.jpg)   | `Volet Bouton Monter`<br/>`Volet Bouton Descendre`<br/>`Volet Bouton Stop`<br/>`Volet Etat`<br/>`Volet Bouton Slider` | `FLAP_UP`<br/>`FLAP_DOWN`<br/>`FLAP_STOP`<br/>`FLAP_STATE`<br/>`FLAP_SLIDER` | Présence d'un slider, avec un bouton Monter/Descendre en Toggle (avec icône d'état) |

### Inondation #

Image                           | type générique               | Partie Dev plugin            | Description          |
:-----------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![FLOOD](../images/FLOOD.jpg)   | `Innondation`<br/>`Température`(optionnel)<br/>`Humidité`(optionnel)<br/>`Sabotage`(optionnel)|`FLOOD`<br/>`TEMPERATURE`(optionnel)<br/>`HUMIDITY`(optionnel)<br/>`HUMIDITY`(optionnel) | Permet d'avoir son capteur d'inondation complet sur une seule ligne.

### Serrure #

Image                         | type générique               | Partie Dev plugin            | Description          |
:---------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![LOCK](../images/LOCK.jpg)   | `Serrure Etat`<br/>`Serrure Bouton Ouvrir`<br/>`Serrure Bouton Fermer` | `LOCK_STATE`<br/>`LOCK_OPEN`<br/>`LOCK_CLOSE` | Retour d'état présent, le bouton de gauche permet de switcher entre on et off |

### Sirène #

Image                         | type générique               | Partie Dev plugin            | Description          |
:---------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![SIREN](../images/SIREN.jpg)   | `Sirène Etat`<br/>`Sirène Bouton On`<br/>`Sirène Bouton Off` | `SIREN_STATE`<br/>`SIREN_ON`<br/>`SIREN_OFF` | Retour d'état présent, le bouton de gauche permet de switcher entre on et off |

### Fumée #

Image                           | type générique               | Partie Dev plugin            | Description          |
:-----------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![SMOKE](../images/SMOKE.jpg)   | `Fumée`<br/>`Température`(optionnel)|`SMOKE`<br/>`TEMPERATURE`(optionnel) | Permet d'avoir son capteur de fumée complet sur une seule ligne.

### Température #

Image                                       | type générique               | Partie Dev plugin            | Description          |
:-----------------------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![TEMPERATURE](../images/TEMPERATURE.jpg)   | `Température`<br/>`Humidité`(optionnel)|`TEMPERATURE`<br/>`HUMIDITY`(optionnel) | Voir Image.

### Présence #

Image                                 | type générique               | Partie Dev plugin            | Description          |
:-----------------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![PRESENCE](../images/PRESENCE.jpg)   | `Présence`<br/>`Température`(optionnel)<br/>`Luminosité`(optionnel)<br/>`Humidité`(optionnel)<br/>`UV`(optionnel)<br/>`Sabotage`(optionnel)|`PRESENCE`<br/>`TEMPERATURE`(optionnel)<br/>`BRIGHTNESS`(optionnel)<br/>`HUMIDITY`(optionnel)<br/>`UV`(optionnel)<br/>`SABOTAGE`(optionnel) | Voir image.

### Ouvrant #

Image                                       | type générique               | Partie Dev plugin            | Description          |
:-----------------------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![OPENING](../images/OPENING.jpg)   | `Porte / Fenêtre`<br/>`Température`(optionnel)|`OPENING / OPENING_WINDOW`<br/>`TEMPERATURE`(optionnel) | Voir Image (à savoir que vous pouvez choisir entre fenêtre et porte).

### Fil pilote #

Image                               | type générique               | Partie Dev plugin            | Description          |
:---------------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![HEATING](../images/HEATING.jpg)   | `Chauffage fil pilote Bouton ON`<br/>`Chauffage fil pilote bouton OFF`<br/>`Chauffage fil pilote Etat`<br/>`Chauffage fil pilote bouton`(optionnel) | `HEATING_ON`<br/>`HEATING_OFF`<br/>`HEATING_STATE`<br/>`HEATING_OTHER`|Les boutons ON/OFF et Etat permette de créer le bouton tout à gauche du template et les `chauffage fil pilote Bouton`sont là pour rajouter des boutons (5 max)

LES JOKERS 
----------

### Générique Action #

Image                             | type générique               | Partie Dev plugin            | Description          |
:-------------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![ACTION](../images/ACTION.jpg)   | `Action Générique`           | `GENERIC_ACTION`             | Le bouton prend la forme du type de l'action. Par défaut c'est un toggle, si c'est un message alors vous avez une enveloppe, si slider vous avez un slider etc...

### Générique Info #

Image                         | type générique               | Partie Dev plugin            | Description          |
:---------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![INFO](../images/INFO.jpg)   | `Information Générique`           | `GENERIC_INFO`             | Le bouton prend la forme du type de l'info.


Troubleshooting 
===============

Aide Mobile 
-----------

**→ Je suis sur Android version de l’app (1.0.1 ou 1.0.0) je n’arrive
pas à accéder à mes pièces ni même à la configuration de l’app.**

> **Caution**
>
> Vous avez eu un popup vous avertissant d’un souci sur les paramètres
> d’accessibilité, il vous suffit donc de vous rendre dans les
> paramètres d’accessibilité de votre mobile et de décocher les
> applications utilisant cette option. (Un correctif sera apporté
> prochainement sur l’app)

**→ J’ai un message dans une des lignes de mes modules me disant qu’il
manque un Type Générique !**

> **Tip**
>
> En lisant ce message, il vous dit quel type générique manque pour
> créer un template compatible. Il suffit juste de l’appliquer.
> Reportez-vous à la [doc chapitre Type
> Générique](https://www.jeedom.com/doc/documentation/plugins/mobile/fr_FR/mobile#_configuration_des_plugins_et_commandes_que_reçoit_l_app).

**→ J’ai un souci sur un des plugins dit complètement intégré (météo,
thermostat, alarme, caméra) !**

> **Tip**
>
> N’hésitez pas à accéder à votre module et de bien cliquer sur
> sauvegarder à nouveau, cela permettra de ré-inclure les types
> génériques associés au module.

**→ Impossible de mettre une info sur l’accueil de l’app !**

> **Tip**
>
> Cela est normal, ça sera disponible sur la version 1.1.

**→ J’ai l’application qui prend enormement de memoire dans mon
telephone !**

> **Tip**
>
> Il y avait un bug sur les versions 1.0.0 et 1.0.1 sur la partie
> Camera. Le souci ne ce reproduira plus avec la 1.0.2, pour supprimer
> le cache sans surprimer l’app, il suffit d’aller dans la configuration
> de votre App Mobile et de cliquer sur "supprimer le cache".

Aide Homebridge 
---------------

**→ Je n’arrive pas à inclure jeedom dans homekit !**

> **Tip**
>
> Vérifier que le statut du démon Homebridge est sur OK.

![demonHB](../images/demonHB.png)

> **Tip**
>
> Pour inclure votre jeedom dans homekit, via une application compatible
> (par exemple Domicile ou Eve), vérifiez que appareil IOS est connecté
> au même réseau que votre jeedom.

![config pluginhb](../images/config-pluginhb.png)

**→ Le démon Homebridge ne veut pas démarrer !**

> **Tip**
>
> Vérifiez que vous disposez de la dernière version des dépendances. En
> cas de doute, il est possible de les réinstaller en cliquant sur
> "relancer". Si la réinstallation des dépendances ne fonctionne pas ou
> indique une erreur dans le log des dépendances, cliquez sur "Réparer
> et Réinstaller".

![dépendances homebridge](../images/dépendances-homebridge.png)

**→ Mon équipement n’apparait pas dans homebridge !**

> **Tip**
>
> Vérifiez que la case "envoyer à Homebridge" soit cochée dans la
> configuration du plugin mobile.

**→ La case "envoyer à homebridge est bien cochée" mais mon équipement
n’apparait toujours pas !**

> **Tip**
>
> Vérifiez dans la configuration de votre équipement que celui-ci soit
> activé et visible.

> **Tip**
>
> Vérifiez que les types génériques soient bien configurés et que la
> commande ou l’info soit visible. Chaque équipement envoyé à homebridge
> doit au moins avoir un type générique "Etat".

![ypegelumi](../images/ypegelumi.png)

**→ J’ai mon Homebridge qui n’exécute pas les commandes !**

> **Tip**
>
> Il faut bien mettre à jour le plugin App Mobile, puis dans la
> configuration des dépendances, il suffit de renseigner un utilisateur
> avec des droits d’exécutions sur les commandes.

**→ J’ai bien le retour d’état d’un équipement mais impossible de le
piloter !**

> **Tip**
>
> Vérifiez que les types génériques soient bien configurés. Il doit
> avoir une cohérence entre les types. Si vous avez le type "info
> lumière" vérifiez que les actions soient de types "Action lumière
> bouton ON" etc…​

**→ Le message "sans réponse" apparait dans l’app domicile ou Eve**

![sans reponse](../images/sans-reponse.jpg)

1.  Si vous n’avez pas de concentrateur Homekit (iPad ou Apple TV)
    vérifiez que vous êtes connectés dans le même réseau que
    votre jeedom.

2.  Vérifiez que le démon est activé. Si ce n’est pas le cas,
    redémarrez le.

3.  Relancez votre box

4.  Si malgré tout vous avez toujours ces états, lancez une réparation.

> **Tip**
>
> Beaucoup d’informations se trouvent dans les logs, le prochain
> chapitre vous expliquera comment les analyser

Interprétation des LOGS Homebridge 
----------------------------------

    [Mon Jul 17 2017 19:35:08 GMT+0000 (UTC)] [Jeedom] ┌──── Maison > Accessoire 1 (111)
    [Mon Jul 17 2017 19:35:08 GMT+0000 (UTC)] [Jeedom] │ Accessoire visible, pas coché pour Homebridge
    [Mon Jul 17 2017 19:35:08 GMT+0000 (UTC)] [Jeedom] │ Vérification d'existance de l'accessoire dans Homebridge...
    [Mon Jul 17 2017 19:35:08 GMT+0000 (UTC)] [Jeedom] │ Accessoire non existant dans Homebridge
    [Mon Jul 17 2017 19:35:08 GMT+0000 (UTC)] [Jeedom] │ Accessoire Ignoré
    [Mon Jul 17 2017 19:35:08 GMT+0000 (UTC)] [Jeedom] └─────────

> **Tip**
>
> L’Accessoire 1 est visible mais la case "Envoyer vers Homebridge"
> n’est pas cochée. L’accessoire ne sera donc pas ajouté dans
> homebridge.

    [Mon Jul 17 2017 19:35:08 GMT+0000 (UTC)] [Jeedom] ┌──── Maison > Accessoire 2 (222)
    [Mon Jul 17 2017 19:35:08 GMT+0000 (UTC)] [Jeedom] │ Vérification d'existance de l'accessoire dans Homebridge...
    [Mon Jul 17 2017 19:35:08 GMT+0000 (UTC)] [Jeedom] │ Accessoire non existant dans Homebridge
    [Mon Jul 17 2017 19:35:08 GMT+0000 (UTC)] [Jeedom] │ Nouvel accessoire (Accessoire 2)
    [Mon Jul 17 2017 19:35:08 GMT+0000 (UTC)] [Jeedom] [INFO]  Ajout service :Accessoire 2 subtype:222-918|0|920- cmd_id:918 UUID:00000049-0000-1000-8000-0026BB765291
    [Mon Jul 17 2017 19:35:08 GMT+0000 (UTC)] [Jeedom] [INFO]     Caractéristique :On valeur initiale:false
    [Mon Jul 17 2017 19:35:08 GMT+0000 (UTC)] [Jeedom] │ Ajout de l'accessoire (Accessoire 2)
    [Mon Jul 17 2017 19:35:08 GMT+0000 (UTC)] [Jeedom] └─────────

> **Tip**
>
> L’Accessoire 2 est visible et la case "Envoyer vers Homebridge" est
> cochée. L’accessoire sera donc ajouté dans homebridge.

    [Mon Jul 17 2017 19:45:27 GMT+0000 (UTC)] [Jeedom] ┌──── Maison > Accessoire 3 (333)
    [Mon Jul 17 2017 19:45:27 GMT+0000 (UTC)] [Jeedom] [WARN] Pas de type générique "Info/Prise Etat"
    [Mon Jul 17 2017 19:45:27 GMT+0000 (UTC)] [Jeedom] │ Accessoire sans Type Générique
    [Mon Jul 17 2017 19:45:27 GMT+0000 (UTC)] [Jeedom] │ Vérification d'existance de l'accessoire dans Homebridge...
    [Mon Jul 17 2017 19:45:27 GMT+0000 (UTC)] [Jeedom] │ Accessoire non existant dans Homebridge
    [Mon Jul 17 2017 19:45:27 GMT+0000 (UTC)] [Jeedom] │ Accessoire Ignoré
    [Mon Jul 17 2017 19:45:27 GMT+0000 (UTC)] [Jeedom] └─────────

> **Tip**
>
> L’Accessoire 3 est visible et la case "Envoyer vers Homebridge" est
> cochée. Mais il n’y a pas de type générique "Etat" (ou celui-ci n’est
> pas visible). L’accessoire ne sera donc pas intégré dans Homebridge.
> Pour corriger ce problème, ajoutez le type générique "info/prise Etat"
> à l’accessoire (ou cochez la case "visible").

    [Mon Jul 17 2017 19:49:49 GMT+0000 (UTC)] [Jeedom] ┌──── Maison > Accessoire 4 (444)
    [Mon Jul 17 2017 19:49:49 GMT+0000 (UTC)] [Jeedom] [WARN] Pas de type générique "Info/Lumière Etat" ou "Info/Lumière Couleur"
    [Mon Jul 17 2017 19:49:49 GMT+0000 (UTC)] [Jeedom] [WARN] Pas de type générique "Action/Prise Bouton On" ou reférence à l'état non définie sur la commande On
    [Mon Jul 17 2017 19:49:49 GMT+0000 (UTC)] [Jeedom] │ Vérification d'existance de l'accessoire dans Homebridge...
    [Mon Jul 17 2017 19:49:49 GMT+0000 (UTC)] [Jeedom] │ Accessoire non existant dans Homebridge
    [Mon Jul 17 2017 19:49:49 GMT+0000 (UTC)] [Jeedom] │ Nouvel accessoire (Accessoire 4)
    [Mon Jul 17 2017 19:49:49 GMT+0000 (UTC)] [Jeedom] [INFO]  Ajout service :Accessoire 4 subtype:444-919|0|921- cmd_id:919 UUID:00000049-0000-1000-8000-0026BB765291
    [Mon Jul 17 2017 19:49:49 GMT+0000 (UTC)] [Jeedom] [INFO]     Caractéristique :On valeur initiale:false
    [Mon Jul 17 2017 19:49:49 GMT+0000 (UTC)] [Jeedom] │ Ajout de l'accessoire (Accessoire 4)
    [Mon Jul 17 2017 19:49:49 GMT+0000 (UTC)] [Jeedom] └─────────

> **Tip**
>
> Il y a une incohérence entre les types génériques. Les types "actions"
> ne correpondent pas au type "info". Pour corriger le problème,
> modifiez les types génériques de l’accessoire en gardant une cohérence
> entres les types actions et info.

    sh: 1: homebridge: not found

> **Tip**
>
> Les dépendances homebridge ne sont pas installées ou certains fichiers
> sont manquants. Cliquez sur relancer.

![dépendances homebridge](../images/dépendances-homebridge.png)

    > **Tip**
    >
    > Si vous avez un problème avec un périphérique malgré tout :
    > décochez "Envoyer à HomeBridge" | relancez le daemon | décochez
    > "Envoyer à HomeBridge" | relancez le daemon : il sera recréé tout
    > proprement (et dans la pièce par défaut de Maison).

-   Ajout d’avertissements et de messages d’attention si on s’approche
    du nombre fatidique de 100 accessoires envoyés dans homebridge
    (HomeKit ne supporte pas plus de 100 accessoires).

-   Au démarrage du daemon, vérification si avahi-daemon et dbus sont
    bien lancés, sinon, les démarrer.

-   A l’install des dépendances, passer avahi-daemon et dbus à enabled
    si pas le cas.

-   Corrections diverses, simplifications et optimisations.

En savoir plus sur l’avancement de l’application Mobile cliquez-ici &gt;
<https://github.com/jeedom/issues/projects/2?fullscreen=true>
