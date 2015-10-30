#!/bin/bash
#
sudo apt-get update
sudo apt-get -y upgrade

echo "[Info] Installing mc"
sudo apt-get -y install mc

echo "[Info] Installing git"
sudo apt-get -y install git

echo "[Info] Installing nginx"
sudo apt-get -y install nginx

echo "[Info] Installing memcached"
apt-get -y install memcached

echo "[Info] Installing php"
sudo apt-get -y install php5 php5-fpm php-apc php-pear php5-cli php5-curl php5-dev php5-gd php5-imagick php5-mcrypt php5-memcache php5-pgsql php5-pspell php5-sqlite php5-tidy php5-xdebug php5-xmlrpc php5-json php5-xsl php5-mysql

cp /vagrant/build/vagrant/addons/Tar.php /usr/share/php/Archive/Tar.php

echo "[Info] Installing phing"
sudo pear channel-discover pear.phing.info
sudo pear install [--alldeps] phing/phing

echo "[Info] Installing mysql"
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password password root'
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password root'
sudo apt-get -y install mysql-server
sudo apt-get -y install mysql-client
sed -i 's/bind-address/#bind-address/g' /etc/mysql/my.cnf
mysql -u root -proot -e "UPDATE mysql.user SET Host='%' WHERE Host='vagrant-ubuntu-trusty-32' AND User='root';"
mysql -u root -proot -e "FLUSH PRIVILEGES;"
service mysql restart

apt-get -f -y install