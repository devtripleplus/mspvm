#!/bin/bash
clear
echo "****************************"
echo  "**" "We are going to        **"
echo  "**" "start installing MSPVM **"
echo  "**" "Please stand by....    **"    
echo "****************************"
read -rsn1 -p"Press any key to continue" variable;echo

echo "Let's remove all the dependancies that we do not require and stop their services"
service httpd stop

yum update -y
yum install php70u-common php70u-fpm -y 
yum install php70u-mbstring php70u-xml -y
yum install php70u-mbstring -y
yum install php70u-json -y
yum install mysql -y
yum -y install http://download.fedoraproject.org/pub/epel/6/x86_64/epel-release-6-8.noarch.rpm
yum -y install php php-fpm nginx mysql-server vim openssl php-mysql zip unzip pdns pdns-backend-mysql sendmail php-mcrypt rsync wget gcc make gcc-c++ zlib-devel perl-ExtUtils-Embed gettext curl-devel php-mbstring git screen vixie-cron crontabs
yum install phpmyadmin -y

echo "We are going to start the desired services to run MSPVM."
service php-fpm start
service nginx start
service mysqld start


echo "We will now start services at boot"
chkconfig php-fpm on
chkconfig nginx on
chkconfig mysqld on

yum install firewall-cmd -y

iptables -A RH-Firewall-1-INPUT -m state --state NEW -m tcp -p tcp --dport 80 -j ACCEPT
iptables -A INPUT -m state --state NEW -m tcp -p tcp --dport 80 -j ACCEPT
-A INPUT -m state --state NEW -m tcp -p tcp --dport 443 -j ACCEPT
iptables -A INPUT -m state --state NEW -m tcp -p tcp --dport 443 -j ACCEPT
iptables -I INPUT -p tcp -m tcp --dport 80 -j ACCEPT

/etc/init.d/iptables restart


echo "Let's install ioncube loader....."


# Color Reset
Color_Off='\033[0m'       # Text Reset

# Regular Colors
Red='\033[0;31m'          # Red
Green='\033[0;32m'        # Green
Cyan='\033[0;36m'         # Cyan


# PHP Modules folder
MODULES=$(php -i | grep extension_dir)

# PHP Version
PHP_VERSION=$(php -r "echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;")

# System Architecture
ARCH=$(getconf LONG_BIT)
getFiles() {
  # if machine type is 64-bit, download and extract 64-bit files
  if [ $ARCH == 64 ]; then
    echo -e "${Cyan} \n Downloading.. ${Color_Off}"
    wget http://downloads3.ioncube.com/loader_downloads/ioncube_loaders_lin_x86-64.tar.gz

    echo -e "${Cyan} Extracting files.. ${Color_Off}"
    tar xvfz ioncube_loaders_lin_x86-64.tar.gz

  # else, get 32-bit files
  else
    echo -e "${Cyan} \n Downloading.. ${Color_Off}"
    wget http://downloads3.ioncube.com/loader_downloads/ioncube_loaders_lin_x86.tar.gz

    echo -e "${Cyan} Extracting files.. ${Color_Off}"
    tar xvfz ioncube_loaders_lin_x86.tar.gz
  fi

  echo -e "${Cyan} \n Copying files to PHP Modules folder.. ${Color_Off}"
  # Copy files to modules folder
  sudo cp "ioncube/ioncube_loader_lin_${PHP_VERSION}.so" $MODULES
}

success() {
  echo -e "${Green} \n IonCube has been installed. Restarting PHP and Nginx.... ${Color_Off}"
}

restart() {
  service php5-fpm restart
}

# RUN
getFiles
success
restart
end;

echo "Let's start configuring MSPVM and place it in the correct directory"



cd /var/www/html;

wget https://www.myserverplanet.com/installers/mspvm-master.zip

unzip mspvm-master.zip

chown -R mspvm/
chown -R wwww-data mspvm/
chown -R nginx mspvm/
chgrp -R nginx mspvm/
chmod -R 0777 mspvm/

echo "Let's setup that database."

mysql -e "create database msp;"

cd /mspvm
php artisan migrate
php artisan db:seed
php artisan db:seed --class=DemoSeeder
