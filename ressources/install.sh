#!/bin/bash
echo "########### Installation en cours ##########"
sudo apt-get update
sudo dpkg --configure -a
sudo apt-get install -y php5-gd
sudo dpkg --configure -a
echo "########### Fin ##########"
