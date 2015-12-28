touch /tmp/dependancy_mobile_in_progress
#!/bin/bash

echo "Launch install of mobile"
sudo apt-get update
sudo apt-get upgrade
sudo apt-get install -y php5-gd

sudo dpkg --configure -a

echo "Everything is successfully installed!"
rm /tmp/dependancy_mobile_in_progress
