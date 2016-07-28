#!/bin/bash

touch /tmp/homebridge_in_progress
echo 0 > /tmp/homebridge_in_progress
echo "Lancement de l'installation/mise à jour des dépendances homebridge"

sudo apt-get install -y avahi-daemon avahi-discover libnss-mdns libavahi-compat-libdnssd-dev
echo 10 > /tmp/homebridge_in_progress
actual=`nodejs -v`;
actual=`nodejs -v`;
echo "Version actuelle : ${actual}"
if [[ $actual == *"4."* || $actual == *"5."* ]]
then
  echo "Ok, version suffisante";
else
  echo "KO, version obsolète à upgrader";
  echo "Suppression du Nodejs existant et installation du paquet recommandé"
  sudo apt-get -y --purge autoremove nodejs npm
  sudo npm rm -g homebridge
  sudo npm rebuild
  arch=`arch`;
  echo 30 > /tmp/mySensors_dep
  if [[ $arch == "armv6l" ]]
  then
    echo "Raspberry 1 détecté, utilisation du paquet pour armv6"
    sudo rm /etc/apt/sources.list.d/nodesource.list
    wget http://node-arm.herokuapp.com/node_latest_armhf.deb
    sudo dpkg -i node_latest_armhf.deb
    sudo ln -s /usr/local/bin/node /usr/local/bin/nodejs
    rm node_latest_armhf.deb
  fi
  if [[ $arch == "aarch64" ]]
  then
    wget http://dietpi.com/downloads/binaries/c2/nodejs_5-1_arm64.deb
    sudo dpkg -i nodejs_5-1_arm64.deb
    sudo ln -s /usr/local/bin/node /usr/local/bin/nodejs
    rm nodejs_5-1_arm64.deb
  fi
  if [[ $arch != "aarch64" && $arch != "armv6l" ]]
  then
    echo "Utilisation du dépot officiel"
    curl -sL https://deb.nodesource.com/setup_5.x | sudo -E bash -
    sudo apt-get install -y nodejs
  fi
  
  new=`nodejs -v`;
  echo "Version actuelle : ${new}"
fi
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