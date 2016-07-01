#!/bin/bash

touch /tmp/compilation_ozw_in_progress
echo 0 > /tmp/homebridge_in_progress
echo "Lancement de l'installation/mise à jour des dépendances homebridge"

sudo apt-get install -y libavahi-compat-libdnssd-dev
echo 10 > /tmp/homebridge_in_progress
curl -sL https://deb.nodesource.com/setup_6.x | sudo -E bash -
echo 20 > /tmp/homebridge_in_progress
sudo apt-get install -y nodejs
echo 40 > /tmp/homebridge_in_progress
sudo npm install -g request
echo 50 > /tmp/homebridge_in_progress
sudo rm -Rf /usr/lib/node_modules/homebridge-jeedom/.git
echo 60 > /tmp/homebridge_in_progress
sudo npm install -g --unsafe-perm homebridge
echo 70 > /tmp/homebridge_in_progress
sudo npm install -g https://github.com/jeedom/homebridge-jeedom
echo 80 > /tmp/homebridge_in_progress
echo "Installation Homebridge OK"
echo 100 > /tmp/homebridge_in_progress
rm /tmp/homebridge_in_progress