unlink /etc/php5/fpm/pool.d/php-fpm-pool.conf
cp /vagrant/build/vagrant/addons/php-fpm-pool.conf /etc/php5/fpm/pool.d/vagrant.conf

unlink /etc/nginx/sites-enabled/nginx-host.conf
unlink /etc/nginx/sites-enabled/default
unlink /etc/nginx/sites-available/nginx-host.conf
cp /vagrant/build/vagrant/addons/nginx-host.conf /etc/nginx/sites-available/nginx-host.conf
ln -s /etc/nginx/sites-available/nginx-host.conf /etc/nginx/sites-enabled/nginx-host.conf

service php5-fpm restart
service nginx restart

cd /vagrant
phing -f build/build.xml -Denvironment="develop"