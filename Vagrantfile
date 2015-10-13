# -*- mode: ruby -*-
# vi: set ft=ruby :

#REQUIRED:
#vagrant plugin install vagrant-hostsupdater

#Vagrantfile API/syntax version. Don't touch unless you know what you're doing!

VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
    config.vm.box = "ubuntu/trusty32"

    config.vm.provider "virtualbox" do |vb|
        vb.customize [
          "modifyvm", :id,
          "--memory", 1024
        ]
      end

    config.vm.network :private_network, ip: "192.168.3.15"
    config.vm.hostname = "vagrant-ubuntu-trusty-32"

    config.vm.network :forwarded_port, guest: 3306, host: 3308

    config.vm.provision "shell", path: "./build/vagrant/vagrant-install-software.sh"
    config.vm.provision "shell", path: "./build/vagrant/vagrant-setup-dev-environment.sh"

end