Plugin zur Verwendung der Jeedom Mobile-Anwendung.

Die mobile Jeedom-Anwendung erfordert die Installation dieses Plugins, um
Die Box kann mit der mobilen Anwendung kommunizieren.

Konfiguration des mobilen Plugins 
==============================

Nach der Installation des Plugins müssen Sie es nur noch aktivieren :

![mobile1](../images/mobile1.png)

**Configuration**

Um das Plugin zu konfigurieren, müssen Sie die Telefone hinzufügen, die
wird in der Lage sein, auf Jeedom zuzugreifen.

So fügen Sie ein Telefon hinzu : **Plugins** → **Communication** → **App
Mobile** → **Ajouter**

![mobile2](../images/mobile2.png)

Hier sind die einzugebenden Parameter :

-   **Name der mobilen Ausrüstung** : Telefonname

-   **Activer** : Aktivieren des Zugriffs für dieses Mobiltelefon

-   **Mobiler Typ** : Auswahl des Telefonbetriebssystems (iOS, Android)

-   **Utilisateur** : Benutzer, der diesem Zugriff zugeordnet ist

> **Tip**
>
> Die Wahl des Benutzers ist wichtig, da sie die bestimmt
> Geräte, zu denen dieser gemäß seinen Rechten Zugang hat.

![mobile3](../images/mobile3.png)

Nach dem Speichern erhalten Sie einen QRCode
die Anwendung, um sich selbst zu konfigurieren.

Konfiguration der von der App empfangenen Plugins und Befehle 
=======================================================

Nach der Initialisierung des Handy Plugins haben Sie die Möglichkeit
Überarbeiten Sie generische Arten von Steuerelementen, Plugins und Teilen.

![mobile10](../images/mobile10.png)

Durch Klicken auf ein Plugin können Sie es zum Chatten autorisieren oder nicht
mit der mobilen App und konfigurieren Sie jeden der generischen Typen
mit seinen Befehlen verbunden.

![mobile11](../images/mobile11.png)

Durch Klicken auf ein Teil können Sie es autorisieren oder nicht
in der mobilen Anwendung vorhanden, und konfigurieren Sie jeden der Typen
Generika im Zusammenhang mit seinen Bestellungen.

![mobile12](../images/mobile12.png)

Konfiguration der mobilen App 
=====================================

Sie finden die Anwendungen auf den mobilen Jalousien :

**Android Google Play**

![Google Play FR](../images/Google_Play_FR.png)

**Apple App Store**

![App Store FR](../images/App_Store_FR.png)

Erster Start der App 
--------------------------

Beim ersten Start der mobilen Anwendung wird ein Tutorial angeboten
um Sie bei der Konfiguration zu unterstützen.

Nach dem Herunterladen und Installieren Ihrer Jeedom Handy App,
Starten Sie die Anwendung auf Ihrem Smartphone.

Sie gelangen dann in ein Konfigurations-Tutorial, das wir
raten zu folgen. Einige Schritte wurden zuvor ausgeführt.

Sie haben dann die Wahl zwischen einer manuellen Konfiguration oder
automatisch per QRcode. Wenn Sie die Konfiguration per QRcode wählen,
Flashen Sie einfach den QR-Code im Handy App-Plugin
zuvor erstellte Smartphone-Geräte. In diesem Fall wird die Anwendung
Rufen Sie automatisch die gesamte Konfiguration Ihres Jeedom und ab
automatisch verbinden. Wenn es über WLAN mit Ihrem Zuhause verbunden ist,
Die Anwendung verwendet automatisch die Jeedom-Ethernet-Adresse
intern in Ihrem Netzwerk. Wenn Sie in 4G oder 3G verbunden sind, dort
verwendet Ihre externe Adresse, um eine Verbindung zu Ihrem Jeedom herzustellen (von
Beispiel über den Jeedom DNS-Dienst, wenn Sie ihn verwenden). Wenn Sie möchten
Für die manuelle Konfiguration müssen Sie in diesem Fall die eingeben
Geben Sie die internen und externen IP-Adressen Ihres Jeedom weiter. Diese Option
ist einer informierten Öffentlichkeit vorbehalten.

Die Anwendung wird synchronisiert und Sie gelangen auf die Startseite
(vorangestellt von einem Mini-Präsentationsleitfaden).

Die mobile Jeedom-App ist jetzt betriebsbereit.

Favoriten 
-----------

In der Anwendung können Sie Favoriten (Verknüpfungen von
Befehle, Plugins, Szenarien).

Hier ist das Verfahren zum Erstellen :

Klicken Sie auf einem der + auf dem Startbildschirm der Anwendung :

![mobile dashboard 1](../images/mobile_dashboard_1.PNG)

Sie gelangen auf die Auswahlseite für den Verknüpfungstyp :

![mobile dashboard 2](../images/mobile_dashboard_2.PNG)

Zum Beispiel werden wir Maßnahmen ergreifen, also bietet es uns
Räume / Objekte :

![mobile dashboard 3](../images/mobile_dashboard_3.PNG)

Sie müssen nur die gewünschte Aktion auswählen
Verknüpfung :

![mobile dashboard 4](../images/mobile_dashboard_4.PNG)

Es ist dann möglich, die Farbe anzupassen (für die
drei Farben sind verfügbar) :

![mobile dashboard 5](../images/mobile_dashboard_5.PNG)

Sowie die beiden dazugehörigen Texte :

![mobile dashboard 6](../images/mobile_dashboard_6.PNG) ![mobile
Dashboard 7](../ images / mobile_dashboard_7.PNG)

Hier haben Sie jetzt eine Verknüpfung Ihrer Bestellung (in der
Version 1.1 Es wird erwartet, dass Ein / Aus-Befehle auf dem angezeigt werden
gleicher Schlüssel).

![mobile dashboard 8](../images/mobile_dashboard_8.PNG)

So konfigurieren Sie generische Typen richtig 
============================================

Generische Typen im Handy Plugin 
------------------------------------------

Besser als Worte, hier ist ein Beispiel für typische Generika für a
Licht mit all seinen Bedienelementen (siehe auch Tabelle Light plus
unten) :

![generic type in plugin](../images/generic_type_in_plugin.jpg)

Anwendungsvorlagentabellen 
---------------------------------------

### Die Lichter #

Bild                           | Typ Gattung               | Dev Plugin Teil            | Beschreibung          |
:-----------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![LIGHT](../images/LIGHT_1.jpg) | `Lumière Bouton On`<br/>`Button Off Light` | `LIGHT_ON`<br/>`LIGHT_OFF`| Vorhandensein von zwei Tasten "EIN" und "Aus" keine Statusrückmeldung. |
![LIGHT](../images/LIGHT_2.jpg) | `Lumière Bouton On`<br/>`Button Off Light`<br/>`State Light` | `LIGHT_ON`<br/>`LIGHT_OFF`<br/>`LIGHT_STATE` | Bei vorhandener Statusrückmeldung wechselt die linke Taste zwischen Ein und Aus |
![LIGHT](../images/LIGHT_2.jpg) | `Lumière Bouton Toggle`<br/>`State Light` | `LIGHT_TOGGLE`<br/>`LIGHT_STATE` | Bei vorhandener Statusrückmeldung wechselt die linke Taste zwischen Ein und Aus |
![LIGHT](../images/LIGHT_3.jpg) | `Lumière Bouton On`<br/>`Button Off Light`<br/>`State Light`<br/>`Light Slider` | `LIGHT_ON`<br/>`LIGHT_OFF`<br/>`LIGHT_STATE`<br/>`LIGHT_SLIDER` | Bei vorhandener Statusrückmeldung können Sie mit der linken Taste zwischen Ein und Aus wechseln und mit dem Schieberegler die Intensität steuern |
![LIGHT](../images/LIGHT_4.jpg) | `Lumière Bouton On`<br/>`Button Off Light`<br/>`State Light`<br/>`Light Slider`<br/>`Lichtfarbe (info)`<br/>`Lichtfarbe (Aktion)`<br/>`Lichtmodus` (optional, wird verwendet, um Lichtmodi zu haben, zum Beispiel Regenbogen auf Hue philips) | `LIGHT_ON`<br/>`LIGHT_OFF`<br/>`LIGHT_STATE`<br/>`LIGHT_SLIDER`<br/>`LIGHT_COLOR`<br/>`LIGHT_SET_COLOR`<br/>`LIGHT_MODE` | Bei vorhandener Statusrückmeldung können Sie mit der linken Taste zwischen Ein und Aus wechseln und mit dem Schieberegler die Intensität steuern. Im Kreis ist die Farbe der Lampe vorhanden. Wenn Sie darauf klicken, können Sie die Farbe ändern und einen Modus aktivieren |

### Die Steckdosen #

Bild                           | Typ Gattung               | Dev Plugin Teil            | Beschreibung          |
:-----------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![ENERGY](../images/ENERGY_1.jpg) | `Prise Bouton On`<br/>`Button Off Socket`| `ENERGY_ON`<br/>`ENERGY_OFF`| Vorhandensein von zwei Tasten "EIN" und "Aus" keine Statusrückmeldung. |
![ENERGY](../images/ENERGY_2.jpg) | `Prise Bouton On`<br/>`Button Off Socket`<br/>`State Taking` | `ENERGY_ON`<br/>`ENERGY_OFF`<br/>`ENERGY_STATE` | Bei vorhandener Statusrückmeldung wechselt die linke Taste zwischen Ein und Aus |
![ENERGY](../images/ENERGY_3.jpg) | `Prise Bouton On`<br/>`Button Off Socket`<br/>`State Taking`<br/>`Slider Socket` | `ENERGY_ON`<br/>`ENERGY_OFF`<br/>`ENERGY_STATE`<br/>`ENERGY_SLIDER` | Bei vorhandener Statusrückmeldung können Sie mit der linken Taste zwischen Ein und Aus wechseln und mit dem Schieberegler die Intensität steuern |

### Die Fensterläden #

Bild                           | Typ Gattung               | Dev Plugin Teil            | Beschreibung          |
:-----------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![FLAP](../images/FLAP_1.jpg)   | `Volet Bouton Monter`<br/>`Down-Button-Bereich`<br/>`Stop Button Pane`<br/>`State pane` (optional) | `FLAP_UP`<br/>`FLAP_DOWN`<br/>`FLAP_STOP`<br/>`FLAP_STATE` (optional) | Vorhandensein von drei Tasten "Auf", "Ab", "Stopp", optionale Statusrückmeldung. |
![FLAP](../images/FLAP_2.jpg)   | `Volet Bouton Monter`<br/>`Down-Button-Bereich`<br/>`Stop Button Pane`<br/>`State pane`<br/>`Slider Button Pane` | `FLAP_UP`<br/>`FLAP_DOWN`<br/>`FLAP_STOP`<br/>`FLAP_STATE`<br/>`FLAP_SLIDER` | Vorhandensein eines Schiebereglers mit einer Auf / Ab-Taste in Umschalten (mit Statussymbol) |

### Hochwasser #

Bild                           | Typ Gattung               | Dev Plugin Teil            | Beschreibung          |
:-----------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![FLOOD](../images/FLOOD.jpg)   | `Innondation`<br/>`Temperatur` (optional)<br/>`Luftfeuchtigkeit` (optional)<br/>"Sabotage" (optional)|`FLOOD`<br/>`TEMPERATUR` (optional)<br/>`FEUCHTIGKEIT` (optional)<br/>`FEUCHTIGKEIT` (optional) | Ermöglicht es Ihnen, Ihren gesamten Hochwassersensor in einer einzigen Leitung zu haben.

### Sperren #

Bild                         | Typ Gattung               | Dev Plugin Teil            | Beschreibung          |
:---------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![LOCK](../images/LOCK.jpg)   | `Sperren Etat`<br/>`Open Button Lock`<br/>`Lock Button Close` | `LOCK_STATE`<br/>`LOCK_OPEN`<br/>`LOCK_CLOSE` | Bei vorhandener Statusrückmeldung wechselt die linke Taste zwischen Ein und Aus |

### Meerjungfrau #

Bild                         | Typ Gattung               | Dev Plugin Teil            | Beschreibung          |
:---------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![SIREN](../images/SIREN.jpg)   | `Meerjungfrau Etat`<br/>`Siren Button On`<br/>`Siren Button Off` | `SIREN_STATE`<br/>`SIREN_ON`<br/>`SIREN_OFF` | Bei vorhandener Statusrückmeldung wechselt die linke Taste zwischen Ein und Aus |

### Rauch #

Bild                           | Typ Gattung               | Dev Plugin Teil            | Beschreibung          |
:-----------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![SMOKE](../images/SMOKE.jpg)   | `Rauch`<br/>`Temperatur` (optional)|`SMOKE`<br/>`TEMPERATUR` (optional) | Ermöglicht es Ihnen, Ihren kompletten Rauchsensor in einer einzigen Leitung zu haben.

### Temperatur #

Bild                                       | Typ Gattung               | Dev Plugin Teil            | Beschreibung          |
:-----------------------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![TEMPERATURE](../images/TEMPERATURE.jpg)   | `Temperatur`<br/>`Luftfeuchtigkeit` (optional)|`TEMPERATURE`<br/>`FEUCHTIGKEIT` (optional) | Siehe Bild.

### Präsenz #

Bild                                 | Typ Gattung               | Dev Plugin Teil            | Beschreibung          |
:-----------------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![PRESENCE](../images/PRESENCE.jpg)   | `Präsenz`<br/>`Temperatur` (optional)<br/>`Helligkeit` (optional)<br/>`Luftfeuchtigkeit` (optional)<br/>`UV` (optional)<br/>"Sabotage" (optional)|`PRESENCE`<br/>`TEMPERATUR` (optional)<br/>`BRIGHTNESS` (optional)<br/>`FEUCHTIGKEIT` (optional)<br/>`UV` (optional)<br/>`SABOTAGE` (optional) | Siehe Bild.

### Öffnen #

Bild                                       | Typ Gattung               | Dev Plugin Teil            | Beschreibung          |
:-----------------------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![OPENING](../images/OPENING.jpg)   | `Porte / Fenêtre`<br/>`Temperatur` (optional)|`OPENING / OPENING_WINDOW`<br/>`TEMPERATUR` (optional) | Siehe Bild (dh Sie können zwischen Fenster und Tür wählen).

### Pilotdraht #

Bild                               | Typ Gattung               | Dev Plugin Teil            | Beschreibung          |
:---------------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![HEATING](../images/HEATING.jpg)   | `Chauffage fil pilote Bouton ON`<br/>`Taste zum Ausheizen des Pilotkabels AUS`<br/>`Heizungs-Pilotdrahtzustand`<br/>`Knopf Pilot Drahtheizung` (optional) | `HEATING_ON`<br/>`HEATING_OFF`<br/>`HEATING_STATE`<br/>`HEATING_OTHER`|Mit den Schaltflächen ON / OFF und State können Sie die Schaltfläche ganz links in der Vorlage erstellen. Mit der Taste "Pilot Pilot Wire Heating" können Sie Schaltflächen hinzufügen (max. 5).

DIE JOKERS 
----------

### Allgemeine Aktion #

Bild                             | Typ Gattung               | Dev Plugin Teil            | Beschreibung          |
:-------------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![ACTION](../images/ACTION.jpg)   | `Action Générique`           | `GENERIC_ACTION`             | Le bouton prend la forme du type de l'action. Par défaut c'est un toggle, si c'est un message alors vous avez une enveloppe, si slider vous avez un slider etc...

### Allgemeine Informationen #

Bild                         | Typ Gattung               | Dev Plugin Teil            | Beschreibung          |
:---------------------------: | :--------------------------- | :--------------------------- | :------------------: |
![INFO](../images/INFO.jpg)   | `Information Générique`           | `GENERIC_INFO`             | Le bouton prend la forme du type de l'info.


Fehlerbehebung 
===============

Handy Hilfe 
-----------

**→ Ich bin auf Android-Version der App (1.0.1 oder 1,0.0) Ich kann nicht
Kein Zugriff auf meine Zimmer oder gar auf die Konfiguration der App.**

> **Caution**
>
> Sie hatten ein Popup, das Sie vor Bedenken hinsichtlich der Parameter warnte
> Zugänglichkeit, müssen Sie nur zum gehen
> Eingabehilfeneinstellungen Ihres Mobiltelefons und deaktivieren Sie diese
> Anwendungen, die diese Option verwenden. (Eine Korrektur wird vorgenommen
> bald auf der App)

**→ Ich habe eine Nachricht in einer der Zeilen meiner Module, die mir dies mitteilt
Fehlen eines generischen Typs !**

> **Tip**
>
> Wenn Sie diese Nachricht lesen, erfahren Sie, für welchen generischen Typ sie fehlt
> Erstellen Sie eine kompatible Vorlage. Wenden Sie es einfach an.
> Siehe [Dokument Kapitel Typ
> Générique](https://www.jeedom.com/doc/documentation/plugins/mobile/fr_FR/mobile#_configuration_des_plugins_et_commandes_que_reçoit_l_app).

**→ Ich habe ein Problem mit einem der sogenannten voll integrierten Plugins (Wetter,
Thermostat, Alarm, Kamera) !**

> **Tip**
>
> Zögern Sie nicht, auf Ihr Modul zuzugreifen und auf zu klicken
> Speichern Sie erneut, um die Typen wieder einzuschließen
> Credits für das Modul.

**→ Es ist unmöglich, Informationen zur Begrüßung der App zu veröffentlichen !**

> **Tip**
>
> Dies ist normal und wird in Version 1.1 verfügbar sein.

**→ Ich habe die Anwendung, die viel Speicherplatz in meinem beansprucht
Telefon !**

> **Tip**
>
> In Version 1.0 ist ein Fehler aufgetreten.0 und 1,0.1 auf dem Spiel
> Kamera. Das Problem wird mit 1.0 nicht wieder auftreten.2, zu löschen
> Verstecke es, ohne die App zu überteuern. Gehe einfach zur Konfiguration
> Klicken Sie in Ihrer mobilen App auf "Cache löschen"".

**→ Ich habe Bedenken hinsichtlich der ersten Synchronisierung in der App oder von SQL im mobilen Plugin !**

> **Tip**
>
> Sie müssen generische Typen eingeben und das Plugin zum Senden von Generika autorisieren. Sehen Sie sich das Dokument etwas höher an.
