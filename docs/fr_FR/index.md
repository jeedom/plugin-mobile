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

Image                           | type générique     | Partie Dev plugin  | Description        |
------------------------------- | ------------------ | ------------------ | ------------------ |
![LIGHT](../images/LIGHT_1.jpg) | -Lumière Bouton On<br/>- Lumière Bouton Off | - LIGHT_ON<br/>- LIGHT_OFF| présence de deux boutons "ON" et "Off" pas de retour d'état. |




-Ljdkhek-
| ![LIGHT            | -Lumière Bouton On | -LIGHT\_ON         | présence de deux   |
| 1](../images/LIGHT |                    |                    | boutons "On" et    |
| _1.jpg)            | -Lumière Bouton    | -LIGHT\_OFF        | "Off", pas de      |
|                    | Off                |                    | retour d’état.     |
+--------------------+--------------------+--------------------+--------------------+
| ![LIGHT            | -Lumière Bouton On | -LIGHT\_ON         | Retour d’état      |
| 2](../images/LIGHT |                    |                    | présent, le bouton |
| _2.jpg)            | -Lumière Bouton    | -LIGHT\_OFF        | de gauche permet   |
|                    | Off                |                    | de switcher entre  |
|                    |                    | -LIGHT\_STATE      | on et off          |
|                    | -Lumière Etat      |                    |                    |
+--------------------+--------------------+--------------------+--------------------+
| ![LIGHT            | -Lumière Bouton    | -LIGHT\_TOGGLE     | Retour d’état      |
| 2](../images/LIGHT | Toggle             |                    | présent, le bouton |
| _2.jpg)            |                    | -LIGHT\_STATE      | de gauche permet   |
|                    | -Lumière Etat      |                    | de switcher entre  |
|                    |                    |                    | on et off via le   |
|                    |                    |                    | Toggle             |
+--------------------+--------------------+--------------------+--------------------+
| ![LIGHT            | -Lumière Bouton On | -LIGHT\_ON         | Retour d’état      |
| 3](../images/LIGHT |                    |                    | présent, le bouton |
| _3.jpg)            | -Lumière Bouton    | -LIGHT\_OFF        | de gauche permet   |
|                    | Off                |                    | de switcher entre  |
|                    |                    | -LIGHT\_STATE      | on et off et le    |
|                    | -Lumière Etat      |                    | slider permet de   |
|                    |                    | -LIGHT\_SLIDER     | contrôler          |
|                    | -Lumière Slider    |                    | l’intensité        |
+--------------------+--------------------+--------------------+--------------------+
| ![LIGHT            | -Lumière Bouton On | -LIGHT\_ON         | Retour d’état      |
| 4](../images/LIGHT |                    |                    | présent, le bouton |
| _4.jpg)            | -Lumière Bouton    | -LIGHT\_OFF        | de gauche permet   |
|                    | Off                |                    | de switcher entre  |
|                    |                    | -LIGHT\_STATE      | on et off et le    |
|                    | -Lumière Etat      |                    | slider permet de   |
|                    |                    | -LIGHT\_SLIDER     | contrôler          |
|                    | -Lumière Slider    |                    | l’intensité. Dans  |
|                    |                    | -LIGHT\_COLOR      | le cercle la       |
|                    | -Lumière Couleur   |                    | couleur de la      |
|                    | (info)             | -LIGHT\_SET\_COLOR | lampe est présente |
|                    |                    |                    | et lors d’un clic  |
|                    | -Lumière Couleur   | -LIGHT\_MODE       | dans celui-ci vous |
|                    | (action)           |                    | pouvez changer la  |
|                    |                    |                    | couleur et activer |
|                    | -Lumière Mode      |                    | un mode.           |
|                    | (optionnel, il     |                    |                    |
|                    | sert à avoir des   |                    |                    |
|                    | modes de lumière,  |                    |                    |
|                    | par exemple        |                    |                    |
|                    | arc-en-ciel sur    |                    |                    |
|                    | les Philips Hue)   |                    |                    |
+--------------------+--------------------+--------------------+--------------------+

: Les Lumières

+--------------------+--------------------+--------------------+--------------------+
| Image              | type générique     | Partie Dev plugin  | Description        |
|                    | attendu            | tiers              |                    |
+====================+====================+====================+====================+
| ![ENERGY           | -Prise Bouton On   | -ENERGY\_ON        | présence de deux   |
| 1](../images/ENERG |                    |                    | boutons "On" et    |
| Y_1.jpg)           | -Prise Bouton Off  | -ENERGY\_OFF       | "Off", pas de      |
|                    |                    |                    | retour d’état.     |
+--------------------+--------------------+--------------------+--------------------+
| ![ENERGY           | -Prise Bouton On   | -ENERGY\_ON        | Retour d’état      |
| 2](../images/ENERG |                    |                    | présent, le bouton |
| Y_2.jpg)           | -Prise Bouton Off  | -ENERGY\_OFF       | de gauche permet   |
|                    |                    |                    | de switcher entre  |
|                    | -Prise Etat        | -ENERGY\_STATE     | on et off          |
+--------------------+--------------------+--------------------+--------------------+
| ![ENERGY           | -Prise Bouton On   | -ENERGY\_ON        | Retour d’état      |
| 3](../images/ENERG |                    |                    | présent, le bouton |
| Y_3.jpg)           | -Prise Bouton Off  | -ENERGY\_OFF       | de gauche permet   |
|                    |                    |                    | de switcher entre  |
|                    | -Prise Etat        | -ENERGY\_STATE     | on et off et le    |
|                    |                    |                    | slider permet de   |
|                    | -Prise Slider      | -ENERGY\_SLIDER    | contrôler          |
|                    |                    |                    | l’intensité        |
+--------------------+--------------------+--------------------+--------------------+

: Les Prises

+--------------------+--------------------+--------------------+--------------------+
| Image              | type générique     | Partie Dev plugin  | Description        |
|                    | attendu            | tiers              |                    |
+====================+====================+====================+====================+
| ![FLAP             | -Volet Bouton      | -FLAP\_UP          | présence de trois  |
| 1](../images/FLAP_ | Monter             |                    | boutons "Monter",  |
| 1.jpg)             |                    | -FLAP\_DOWN        | "Descendre" et     |
|                    | -Volet Bouton      |                    | "Stop", retour     |
|                    | Descendre          | -FLAP\_STOP        | d’état optionnel.  |
|                    |                    |                    |                    |
|                    | -Volet Bouton Stop | -FLAP\_STATE       |                    |
|                    |                    | (optionnel)        |                    |
|                    | -Volet             |                    |                    |
|                    | Etat(optionnel)    |                    |                    |
+--------------------+--------------------+--------------------+--------------------+
| ![FLAP             | -Volet Bouton      | -FLAP\_UP          | Présence d’un      |
| 2](../images/FLAP_ | Monter             |                    | slider, avec un    |
| 2.jpg)             |                    | -FLAP\_DOWN        | bouton             |
|                    | -Volet Bouton      |                    | Monter/Descendre   |
|                    | Descendre          | -FLAP\_STOP        | en switch (avec    |
|                    |                    |                    | icône d’état)      |
|                    | -Volet Bouton Stop | -FLAP\_STATE       |                    |
|                    |                    |                    |                    |
|                    | -Volet Etat        | -FLAP\_SLIDER      |                    |
|                    |                    |                    |                    |
|                    | -Volet Bouton      |                    |                    |
|                    | Slider             |                    |                    |
+--------------------+--------------------+--------------------+--------------------+

: Les Volets

+--------------------+--------------------+--------------------+--------------------+
| Image              | type générique     | Partie Dev plugin  | Description        |
|                    | attendu            | tiers              |                    |
+--------------------+--------------------+--------------------+--------------------+
| ![FLOOD](../images | -Inondation        | -FLOOD             | Permet d’avoir son |
| /FLOOD.jpg)        |                    |                    | capteur            |
|                    | -Température       | -TEMPERATURE       | d’inondation       |
|                    | (optionnel)        | (optionnel)        | complet sur une    |
|                    |                    |                    | seule ligne.       |
|                    | -Humidité          | -HUMIDITY          |                    |
|                    | (optionnel)        | (optionnel)        |                    |
|                    |                    |                    |                    |
|                    | -Sabotage          | -SABOTAGE          |                    |
|                    | (optionnel)        | (optionnel)        |                    |
+--------------------+--------------------+--------------------+--------------------+

: Inondation

+--------------------+--------------------+--------------------+--------------------+
| Image              | type générique     | Partie Dev plugin  | Description        |
|                    | attendu            | tiers              |                    |
+--------------------+--------------------+--------------------+--------------------+
| ![LOCK](../images/ | -Serrure Etat      | -LOCK\_STATE       | Retour d’état      |
| LOCK.jpg)          |                    |                    | présent, le bouton |
|                    | -Serrure Bouton    | -LOCK\_OPEN        | de gauche permet   |
|                    | Ouvrir             |                    | de switcher entre  |
|                    |                    | -LOCK\_CLOSE       | on et off          |
|                    | -Serrure Bouton    |                    |                    |
|                    | Fermer             |                    |                    |
+--------------------+--------------------+--------------------+--------------------+

: Serrure

+--------------------+--------------------+--------------------+--------------------+
| Image              | type générique     | Partie Dev plugin  | Description        |
|                    | attendu            | tiers              |                    |
+--------------------+--------------------+--------------------+--------------------+
| ![SIREN](../images | -Sirène Etat       | -SIREN\_STATE      | Retour d’état      |
| /SIREN.jpg)        |                    |                    | présent, le bouton |
|                    | -Sirène Bouton On  | -SIREN\_ON         | de gauche permet   |
|                    |                    |                    | de switcher entre  |
|                    | -Sirène Bouton Off | -SIREN\_OFF        | on et off          |
+--------------------+--------------------+--------------------+--------------------+

: Sirène

+--------------------+--------------------+--------------------+--------------------+
| Image              | type générique     | Partie Dev plugin  | Description        |
|                    | attendu            | tiers              |                    |
+--------------------+--------------------+--------------------+--------------------+
| ![SMOKE](../images | -Fumée             | -SMOKE             | Permet d’avoir son |
| /SMOKE.jpg)        |                    |                    | capteur de fumées  |
|                    | -Température       | -TEMPERATURE       | complet sur une    |
|                    | (optionnel)        | (optionnel)        | seule ligne.       |
+--------------------+--------------------+--------------------+--------------------+

: Fumée

+--------------------+--------------------+--------------------+--------------------+
| Image              | type générique     | Partie Dev plugin  | Description        |
|                    | attendu            | tiers              |                    |
+--------------------+--------------------+--------------------+--------------------+
| ![TEMPERATURE](../ | -Température       | -TEMPERATURE       | Voir image.        |
| images/TEMPERATURE |                    |                    |                    |
| .jpg)              | -Humidité          | -HUMIDITY          |                    |
|                    | (optionnel)        | (optionnel)        |                    |
+--------------------+--------------------+--------------------+--------------------+

: Température

+--------------------+--------------------+--------------------+--------------------+
| Image              | type générique     | Partie Dev plugin  | Description        |
|                    | attendu            | tiers              |                    |
+--------------------+--------------------+--------------------+--------------------+
| ![PRESENCE](../ima | -Présence          | -PRESENCE          | Voir image.        |
| ges/PRESENCE.jpg)  |                    |                    |                    |
|                    | -Température       | -HUMIDITY          |                    |
|                    | (optionnel)        | (optionnel)        |                    |
|                    |                    |                    |                    |
|                    | -Luminosité        | -TEMPERATURE       |                    |
|                    | (optionnel)        | (optionnel)        |                    |
|                    |                    |                    |                    |
|                    | -Humidité          | -UV (optionnel)    |                    |
|                    | (optionnel)        |                    |                    |
|                    |                    | -BRIGHTNESS        |                    |
|                    | -UV (optionnel)    | (optionnel)        |                    |
|                    |                    |                    |                    |
|                    | -Sabotage          | -SABOTAGE          |                    |
|                    | (optionnel)        | (optionnel)        |                    |
+--------------------+--------------------+--------------------+--------------------+

: Présence

+--------------------+--------------------+--------------------+--------------------+
| Image              | type générique     | Partie Dev plugin  | Description        |
|                    | attendu            | tiers              |                    |
+--------------------+--------------------+--------------------+--------------------+
| ![OPENING](../imag | -Porte / Fenêtre   | -OPENING /         | Voir image, (à     |
| es/OPENING.jpg)    |                    | OPENING\_WINDOW    | savoir que vous    |
|                    | -Température       |                    | pouvez choisir     |
|                    | (optionnel)        | -TEMPERATURE       | entre fenêtre et   |
|                    |                    | (optionnel)        | porte).            |
+--------------------+--------------------+--------------------+--------------------+

: Ouvrant

+--------------------+--------------------+--------------------+--------------------+
| Image              | type générique     | Partie Dev plugin  | Description        |
|                    | attendu            | tiers              |                    |
+--------------------+--------------------+--------------------+--------------------+
| ![HEATING](../imag | -Chauffage fil     | -HEATING\_ON       | Les boutons ON/OFF |
| es/HEATING.jpg)    | pilote Bouton ON   |                    | et Etat permettent |
|                    |                    | -HEATING\_OFF      | de créer le bouton |
|                    | -Chauffage fil     |                    | tout à gauche du   |
|                    | pilote Bouton OFF  | -HEATING\_STATE    | template et les    |
|                    |                    |                    | "Chauffage fil     |
|                    | -Chauffage fil     | -HEATING\_OTHER    | pilote Bouton"     |
|                    | pilote Etat        |                    | sont là pour       |
|                    |                    |                    | rajouter des       |
|                    | -Chauffage fil     |                    | boutons (5 max)    |
|                    | pilote Bouton      |                    |                    |
|                    | (optionnel)        |                    |                    |
+--------------------+--------------------+--------------------+--------------------+

: Fil pilote

LES JOKERS 
----------

+--------------------+--------------------+--------------------+--------------------+
| Image              | type générique     | Partie Dev plugin  | Description        |
|                    | attendu            | tiers              |                    |
+--------------------+--------------------+--------------------+--------------------+
| ![ACTION](../image | -Action générique  | -GENERIC\_ACTION   | Le bouton prend la |
| s/ACTION.jpg)      |                    |                    | forme du type de   |
|                    |                    |                    | l’action. Par      |
|                    |                    |                    | défaut c’est un    |
|                    |                    |                    | toggle, si c’est   |
|                    |                    |                    | un message alors   |
|                    |                    |                    | vous avez une      |
|                    |                    |                    | enveloppe, si      |
|                    |                    |                    | slider vous avez   |
|                    |                    |                    | un slider etc.     |
+--------------------+--------------------+--------------------+--------------------+

: Générique Action

+--------------------+--------------------+--------------------+--------------------+
| Image              | type générique     | Partie Dev plugin  | Description        |
|                    | attendu            | tiers              |                    |
+--------------------+--------------------+--------------------+--------------------+
| ![INFO](../images/ | -Information       | -GENERIC\_INFO     | Le bouton prend la |
| INFO.jpg)          | générique          |                    | forme du type de   |
|                    |                    |                    | l’info.            |
+--------------------+--------------------+--------------------+--------------------+

: Générique Info

Homebridge 
==========

Présentation Homebridge 
-----------------------

**Homebridge** est un demon inclu avec le plugin Mobile qui permet
d’interagir avec votre domotique via l’assistant vocal Siri sous iOS. Le
HomeKit a été introduit depuis iOS 8, mais est véritablement
opérationnel depuis iOS 10 via l’application Home.

![homekit logo](../images/homekit-logo.jpg)

Le plugin Homebridge de jeedom permet donc d’exposer des équipements
jeedom qui seront vus comme des accessoires compatibles au protocole
**HomeKit**.

> **Important**
>
> **Homebridge n’est pas officiellement supporté par Apple. A tout
> moment Apple peut bloquer ce protocole.**

### Que peut-on faire avec Homebridge 

Homebridge peut s’utiliser avec une application compatible Homekit ou
avec l’assistant vocal Siri.

Depuis IOS10, l’application Domicile (inclue par défaut avec IOS) permet
le pilotage d’équipement compatible Homekit.

![cuisine homekit](../images/cuisine-homekit.jpg)

Les équipements peuvent être classés par pièce.

![piece homekit](../images/piece-homekit.jpg)

Un bon nombre d’accessoires sont pris en charge.

![garage homekit](../images/garage-homekit.png)

Siri peut aussi interagir. Vous pouvez lui poser des questions :

![siri 01](../images/siri-01.jpg)

Siri peut également faire des actions :

![siri 02](../images/siri-02.jpg)

Homekit à l’avantage d’être utilisable à l’extérieur du domicile. Seule
condition, il faut disposer d’un concentrateur. L’iPad et l’AppleTV (et
bientot le HomePod) peuvent servir de concentrateur. Pour cela, ils
doivent être connectés au même compte iCloud.

Homekit est utilisable sur iPhone, iPad, Apple Watch et Apple TV (via
Siri remote).

> **Tip**
>
> **Homekit est le nom officiel du protocole développé par Apple.
> HomeBridge est son équivalent Open Source développé par nfarina. Ce
> dernier a étendu le projet HAP-NodeJS qui est le moteur
> d’HomeBridge.**

Prérequis 
---------

Afin d’utiliser homebridge, vous devez déclarer au moins un équipement
sous iOS, vous verrez dans la configuration du plugin une section
Homebridge. (**Plugins** → **Gestion des plugins** → **App Mobile**)

![appareil ios](../images/appareil-ios.png)

**Systèmes compatibles avec homebridge :**

-   Raspberry Pi 2 et 3 (Le Pi 3 est conseillé)

-   Box jeedom Mini\

-   Box jeedom pro

-   Box jeedom smart

-   Box jeedom pro V2

-   Tout système basé sur débian 8

> **Important**
>
> **Les installations sous Docker et Raspberry Pi 1 ne sont pas
> compatibles avec cette version de Homebridge.**

Configuration de Homebridge 
---------------------------

Dans la configuration du plugin mobile, assurez vous d’avoir la dernière
version des dépendances. En cas de doute, vous pouvez les réinstaller en
cliquant sur "relancer".

**Le temps d’installation des dépendances peut varier en fonction du
matériel utilisé.**

![dépendances homebridge](../images/dépendances-homebridge.png)

Une fois les dépendances installées, le démon se lance. Si le statut
n’est pas sur "OK", cliquez sur "redémarrer".

![démon homebridge](../images/démon-homebridge.png)

### Sélection des accessoires 

Vous devez sélectionner les équipements que vous souhaitez utiliser avec
Homebridge.

**Configuration via les plugins**

![config plugin](../images/config-plugin.png)

**Configuration via les pièces / objets**

![config piece](../images/config-piece.png)

Cliquez sur les plugins ou les pièces/objets où se trouvent les
équipements que vous souhaitez ajouter.

![config plugin 1](../images/config-plugin-1.png)

Pour sélectionner les équipements que vous voulez utiliser sur
Homebridge, cochez la case "Envoyer à Homebridge".

> **Tip**
>
> Pour ajouter ou supprimer un accessoire dans Homebrige, sélectionnez
> l’accessoire en question dans Plugins ou Objet/pièces et cochez ou
> décochez "Envoyer à Homebridge". Cliquez sur sauvegarder. Une fois
> sauvegardé (et le démon relancé), votre accessoire sera intégré ou
> supprimé de Homebridge.

En cliquant sur l’équipement, vous verrez les types génériques utilisés
pour la communication entre votre jeedom et Homebridge.

![typegen 1](../images/typegen-1.png)

La majorité des types génériques est déjà renseignée. Dans certains cas,
vous devrez les configurer manuellement (pour le plugin Virtuel par
exemple).

Voici les types génériques disponibles :

Pour les informations :

![typeginfo](../images/typeginfo.png)

Pour les actions :

![ypegeaction](../images/ypegeaction.png)

> **Important**
>
> Chaque équipement doit disposer d’au moins un type générique de type
> "Etat". S’il n’y a pas d’Etat, l’équipement ne sera pas disponible sur
> Homebridge. Il faut une certaine cohérence dans le choix des types
> génériques. Dans le cas d’une lumière par exemple, vous devez avoir
> l’info "Lumière état" et les actions "Lumières bouton OFF" et "Lumière
> bouton ON". Si cette cohérence n’est pas respectée, l’équipement peut
> ne pas apparaître dans Homebridge.

![ypegelumi](../images/ypegelumi.png)

### Types génériques compatibles avec Homebridge 

#### Lumières 

Support des couleurs basique et buggé, doit être réécrit et documenté

#### Prises 

+-------------------------+-------------------------+--------------------------+
| Type générique          | Obligatoire             | Valeurs possibles        |
+=========================+=========================+==========================+
| Info/Prise Etat         | `OUI`                   | 0 = Eteint               |
|                         |                         |                          |
|                         |                         | 1 = Allumé               |
+-------------------------+-------------------------+--------------------------+
| Action/Prise Bouton On  | `OUI`                   | Référence vers l’Etat    |
+-------------------------+-------------------------+--------------------------+
| Action/Prise Bouton Off | `OUI`                   | Référence vers l’Etat    |
+-------------------------+-------------------------+--------------------------+
| Action/Prise Slider     | `NON Utilisé`           | N/A                      |
+-------------------------+-------------------------+--------------------------+

#### Volets 

+-------------------------+-------------------------+--------------------------+
| Type générique          | Obligatoire             | Valeurs possibles        |
+=========================+=========================+==========================+
| Info/Volet Etat         | `OUI`                   | 0 = Fermé                |
|                         |                         |                          |
|                         |                         | &gt;95 = Ouvert          |
+-------------------------+-------------------------+--------------------------+
| Action/Volet Bouton     | `NON Utilisé`           | N/A                      |
| Monter                  |                         |                          |
+-------------------------+-------------------------+--------------------------+
| Action/Volet Bouton     | `NON Utilisé`           | N/A                      |
| Descendre               |                         |                          |
+-------------------------+-------------------------+--------------------------+
| Action/Volet Bouton     | `NON Utilisé`           | N/A                      |
| Stop                    |                         |                          |
+-------------------------+-------------------------+--------------------------+
| Action/Volet Bouton     | `OUI`                   | Référence vers l’Etat    |
| Slider                  |                         |                          |
+-------------------------+-------------------------+--------------------------+

#### Volets BSO 

Pas encore supportés

#### Chauffage fil pilote 

N’existe pas en HomeKit

#### Serrures 

+-------------------------+-------------------------+--------------------------+
| Type générique          | Obligatoire             | Valeurs possibles        |
+=========================+=========================+==========================+
| Info/Serrure Etat       | `OUI`                   | pas 1 = Non Sécurisée    |
|                         |                         |                          |
|                         |                         | 1 = Sécurisée            |
+-------------------------+-------------------------+--------------------------+
| Action/Serrure Bouton   | `OUI`                   | Référence vers l’Etat    |
| Ouvrir                  |                         |                          |
+-------------------------+-------------------------+--------------------------+
| Action/Serrure Bouton   | `OUI`                   | Référence vers l’Etat    |
| Fermer                  |                         |                          |
+-------------------------+-------------------------+--------------------------+

#### Sirènes 

N’existe pas en HomeKit

#### Thermostats 

Support basique, aucune configuration

#### Cameras 

Support par "Plateforme Homebridge supplémentaire"

#### Modes 

N’existe pas en HomeKit

#### Alarmes 

Support en lecture d’état, changement d’état à venir

#### Météo 

Pas encore supporté (certains éléments existent en HomeKit mais pas
tous)

#### Portails ou Garages 

+-------------------------+-------------------------+--------------------------+
| Type générique          | Obligatoire             | Valeurs possibles        |
+=========================+=========================+==========================+
| Info/Portail état       | `OUI`                   | 0 = Fermé                |
| ouvrant                 |                         |                          |
|                         |                         | 252 = Fermeture en cours |
| Info/Garage état        |                         |                          |
| ouvrant                 |                         | 253 = Stoppé             |
|                         |                         |                          |
| (même traitement)       |                         | 254 = Ouverture en cours |
|                         |                         |                          |
|                         |                         | 255 = Ouvert             |
+-------------------------+-------------------------+--------------------------+
| Action/Portail ou       | `OUI`                   | Référence vers l’Etat    |
| garage bouton toggle    |                         |                          |
+-------------------------+-------------------------+--------------------------+
| Action/Portail ou       | `NON Utilisé`           | N/A                      |
| garage bouton           |                         |                          |
| d’ouverture             |                         |                          |
+-------------------------+-------------------------+--------------------------+
| Action/Portail ou       | `NON Utilisé`           | N/A                      |
| garage bouton de        |                         |                          |
| fermeture               |                         |                          |
+-------------------------+-------------------------+--------------------------+

#### Generic 

+-------------------------+-------------------------+--------------------------+
| Type générique          | Obligatoire             | Valeurs possibles        |
+=========================+=========================+==========================+
| Info/Puissance          | `NON`                   | Watts                    |
| Electrique              |                         |                          |
+-------------------------+-------------------------+--------------------------+
| Info/Consommation       | `NON`                   | KWh                      |
| Electrique              |                         |                          |
|                         |                         |                          |
| (cachée)                |                         |                          |
+-------------------------+-------------------------+--------------------------+
| Info/Température        | `NON`                   | -50→100 °C               |
+-------------------------+-------------------------+--------------------------+
| Info/Luminosité         | `NON`                   | 0.0001→ 100000 lux       |
+-------------------------+-------------------------+--------------------------+
| Info/Présence           | `NON`                   | 0 = Pas de mouvement     |
|                         |                         |                          |
|                         |                         | 1 = Mouvement            |
+-------------------------+-------------------------+--------------------------+
| Info/Batterie           | `NON`                   | %                        |
|                         |                         |                          |
| (caché)                 |                         |                          |
+-------------------------+-------------------------+--------------------------+
| Info/Batterie en charge | `NON`                   | 0 = NON                  |
|                         |                         |                          |
| (caché, à venir)        |                         | pas 0 = OUI              |
+-------------------------+-------------------------+--------------------------+
| Info/Détection de fumée | `NON`                   | pas 1 = Pas de fumée     |
|                         |                         | détectée                 |
|                         |                         |                          |
|                         |                         | 1 = fumée détectée       |
+-------------------------+-------------------------+--------------------------+
| Info/Inondation         | `NON`                   | pas 1 = Pas de fuite     |
|                         |                         | détectée                 |
|                         |                         |                          |
|                         |                         | 1 = fuite détectée       |
+-------------------------+-------------------------+--------------------------+
| Info/Humidité           | `NON`                   | %                        |
+-------------------------+-------------------------+--------------------------+
| Info/Porte              | `NON`                   | pas 1 = Contact          |
|                         |                         |                          |
| Info/Fenêtre            |                         | 1 = Pas de contact       |
|                         |                         |                          |
| (même traitement)       |                         |                          |
+-------------------------+-------------------------+--------------------------+
| Info/Sabotage           | `NON`                   | 0 = Pas de sabotage      |
|                         |                         |                          |
| (à venir)               |                         | pas 0 = Sabotage         |
+-------------------------+-------------------------+--------------------------+
| Info/Choc               | `NON`                   | N/A                      |
|                         |                         |                          |
| (N’existe pas en        |                         |                          |
| HomeKit)                |                         |                          |
+-------------------------+-------------------------+--------------------------+
| Info/Générique          | `NON`                   | N/A                      |
|                         |                         |                          |
| (N’existe pas en        |                         |                          |
| HomeKit)                |                         |                          |
+-------------------------+-------------------------+--------------------------+
| Action/Générique        | `NON`                   | N/A                      |
|                         |                         |                          |
| (N’existe pas en        |                         |                          |
| HomeKit)                |                         |                          |
+-------------------------+-------------------------+--------------------------+

> **Important**
>
> Les références vers l’état dans les actions est primordiale !! sinon
> pas de lien entre l’état et ses actions possible.

![reference etat](../images/reference-etat.png)

### Configuration du plugin mobile 

Une fois tous les objets configurés avec leurs bons types génériques,
retournez dans **→ Equipement → Configuration.**

![config pluginhb](../images/config-pluginhb.png)

-   **Utilisateur** : Permet à Homebridge d’utiliser l’ApiKey d’un
    utilisateur de votre Jeedom.

-   **Nom Homebridge** : Permet de renommer votre pont Homebridge.

> **Important**
>
> Le changement de nom Homebridge vous obligera à reconfigurer vos
> applications Homekit.

-   **PIN Homebridge** : Permet de personnaliser le code PIN Homebridge.

> **Important**
>
> Les PIN suivants ne sont pas accéptés par Apple : 000-00-000,
> 111-11-111, 222-22-222 → 999-99-999, 123-45-678, 876-54-321. Son
> changement vous obligera à reconfigurer vos applications Homekit

-   **Réparer** : Permet une réparation de Homebridge en modifiant
    les identifiants.

> **Important**
>
> Il faut retirer le bridge de votre application "Maison" avant et l’y
> remettre ensuite.

-   **Réparer & réinstaller** : Supprime et réinstalle
    complètement Homebridge.

> **Important**
>
> A n’effectuer que sur conseil d’un membre du forum et il faut retirer
> le bridge de votre application "Maison" avant et l’y remettre ensuite.

-   **Plateforme Homebridge supplémentaire** : Permet de rajouter
    manuellement un équipement.

> **Important**
>
> Réservé à un public averti. Il n’y aura aucun support pour cette
> partie. (permet par exemple d’ajouter des cameras à Homebridge)

Une fois les cases **Utilisateur, nom Homebridge et PIN Homebridge**
correctement renseignées, finaliser la configuration en cliquant sur
**sauvegarder**.

### Ajout de jeedom dans homekit 

Il existe plusieurs applications sur l’appstore compatible homekit. Nous
allons utiliser l’application "domicile".

Pour inclure jeedom, ouvrir l’application "Maison" et cliquez sur
ajouter un accessoire.

![home 1](../images/home-1.jpg)

Scannez le code PIN

![home 2](../images/home-2.jpg)

Sélectionnez votre jeedom

![home 3](../images/home-3.jpg)

> **Important**
>
> L’exemple a été réalisée avec la version beta d’IOS 11. Sur IOS 10 la
> procécure est quasi identique. Il suffit de sélectionner votre jeedom
> et de scanner le PIN.

![home 4](../images/home-4.jpg)

> **Important**
>
> Comme expliqué plus haut dans la doc, Homebridge n’est pas reconnu
> officiellement par Apple. Un message vous indique que l’accessoire
> n’est pas cetifié. Cliquez sur "poursuivre l’ajout".

![home 5](../images/home-5.jpg)

Il suffit maintenant de placer correctement les accessoires dans les
bonnes pièces. Vous avez la possibilité de le créer en cliquant sur
pièce et créer pièce.

Vous pouvez retrouver [ici](https://support.apple.com/fr-fr/HT204893) la
documentation complète de l’application "Maison" d’Apple.

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
