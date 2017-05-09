FreeHackQuest installation
===

This is an open source platform for competitions in computer security.


# developers and designers

Evgenii Sopov

# Thanks for

	Sergey Belov,
	Igor Polyakov,
	Maxim Samoilov (Nitive),
	Dmitrii Mukovkin,
	Team Keva,
	Alexey Gulyaev,
	Alexander Menschikov,
	Ilya Bokov,
	Extrim Code,
	Taisiya Lebedeva


"free-hack-quest" of "fhq" it is a web-engine for running CTF-challenges

# Short Manual:

1. Download sources.

2. copy www/* to your web folder

3. change access (linux 777) to folders:
	files/games
	files/dumps
	files/quests
	files/users

4. execute SQL-script: 'freehackquest.sql'

5. copy 'config/config.php.ini' to 'config/config.php' and configure it

6. Install backend, please follow https://github.com/freehackquest/backend

7. Login in the FHQ (admin:admin123).

8. Create new user with role 'admin' and with correct email

9. Relogon with new user and remove old admin.

10. Create new game.

11. Create new quest and check it.

# Full manual for debian (apache2 + php7.0 + mysql):

Database

	$ sudo apt-get install mysql-server
	$ sudo apt-get install mysql-client
	

php + apache
	
	$ sudo apt install php7.0
	$ sudo apt install apache2
	$ sudo apt install libapache2-mod-php7.0
	
php + mysql
	
	$ sudo apt install php7.0-mysql
	
for captcha and zip
	
	$ sudo apt install php7.0-gd
	$ sudo apt install php7.0-zip
	
for mailing
	
	$ sudo apt install php-pear
	$ sudo pear install Mail
	$ sudo pear install Net_SMTP

restart apache
	
	$ sudo service apache2 restart
	
connect to mysql and create database
	
	$ sudo mysql -p -u root

and execute next queries:

	> CREATE DATABASE `freehackquest` CHARACTER SET utf8 COLLATE utf8_general_ci;
	> CREATE USER 'freehackquest_u'@'localhost' IDENTIFIED BY 'freehackquest_password_database';
	> GRANT ALL PRIVILEGES ON freehackquest.* TO 'freehackquest_u'@'localhost' WITH GRANT OPTION;
	> FLUSH PRIVILEGES;

get sources

	$ sudo git clone https://github.com/sea-kg/fhq.git fhq.git
	$ sudo cd fhq.git
	
create struct of database

	$ sudo mysql -u root -p freehackquest < freehackquest.sql

install composer for google authorization

	$ sudo apt install composer
	$ cd www/
	$ composer install
	
copy sources to /var/www

	$ sudo cp -R www/* /var/www/html/
	$ sudo rm /var/www/html/index.html
	$ sudo cd /var/www/html/config
	$ sudo cp config.php.inc config.php

-> here configure config.php

change access to folders

	$ sudo cd /var/www/files
	$ sudo chmod 777 /var/www/html/dumps
	$ sudo chmod 777 /var/www/html/users
	$ sudo chmod 777 /var/www/html/games
	$ sudo chmod 777 /var/www/html/quests

Configure updalod options

Please change in /etc/php/7.0/apache2/php.ini

	upload_max_filesize = 2M
change to

	upload_max_filesize = 100M

and

	post_max_size = 8M
change to

	post_max_size = 100M


Login in the FHQ (admin:admin123).

Create new user with role 'admin' and with correct email

Relogon with new user and remove old admin.

Create new game.

Create new quest and check it.


# NOTE: Example configs Apache on Ubuntu and another systems

Setting up a domain name.

Add to /etc/apache2/sites-available/default next text or create new file /etc/apache2/sites-available/fhq.config":

	<VirtualHost *:80>
			Options -Indexes FollowSymLinks MultiViews
			DocumentRoot /var/www/html/
			ServerName fhq.keva.su
			ErrorLog /var/log/apache2/fhq.keva.su-error_log
			CustomLog /var/log/apache2/fhq.keva.su-access_log common

			<Directory "/var/www/html/files">
					AllowOverride None
					Options -Indexes
					Order allow,deny
					Allow from all
			</Directory>

			<Directory /var/www/html/config>
					Order deny,allow
					Deny from all
			</Directory>
	</VirtualHost>

# NOTE: Configure firewall for database (iptables)

Some settings.

Linux and iptables.

Add to the Firewall permission for the server (if it is not on the local machine)

	$ sudo iptables -A INPUT -p tcp -s 0/0 --sport 1024:65535 -d 172.16.53.102 \
	--dport 3306 -m state --state NEW,ESTABLISHED -j ACCEPT
	$ sudo iptables -A OUTPUT -p tcp -s 172.16.53.102 --sport 3306 -d 0/0 \
	--dport 1024:65535 -m state --state ESTABLISHED -j ACCEPT
	$ sudo iptables-save

Check that the records were

	$ sudo iptables -L INPUT -n -v --line-numbers
	$ sudo iptables -L OUTPUT -n -v --line-numbers

To delete an record:

Find a number

	$ sudo iptables -L INPUT -n -v --line-numbers
And remove

	$ sudo iptables -D INPUT number_of_your_entry
And of course, keep

	$ sudo iptables-save

# NOTE: Firewall on CentOS 7 for database (use firewalld)

CentOS + MariaDB + Firewall.
CentOS 7 + firewalld:

	$ sudo firewall-cmd --zone=public --add-port=3306/tcp --permanent
	$ sudo firewall-cmd --reload

# NOTE: 'backup.sh' helper script for backup (without git just sources update)

	#!/bin/bash
	# must running in root of repository folder

	echo "pwd = $(pwd)/$1/php/*"
	echo "1 = $1"

	# backup
	BACKUP_DIR="`pwd`/../fhq.autobackups"
	echo "BACKUP_DIR = $BACKUP_DIR"

	if [ ! -d $BACKUP_DIR ]; then
	  mkdir $BACKUP_DIR
	fi

	# backup php_sources
	if [ -d $1 ]; then
	  FILE_TAR_GZ="$BACKUP_DIR/fhq_php_`date +%Y%m%d-%H%M%S`.tar.gz"
	  echo "FILE_TAR_GZ = $FILE_TAR_GZ"
	  tar -zcvf $FILE_TAR_GZ $1
	fi

	# dump mysql  
	if [ -d $1 ]; then
	  MYSQLDUMP_SQL="$BACKUP_DIR/fhq_sql_`date +%Y%m%d-%H%M%S`.sql"
	  MYSQLDUMP_TAR_GZ="$BACKUP_DIR/fhq_sql_`date +%Y%m%d-%H%M%S`.tar.gz"
	  echo "MYSQLDUMP_SQL = $MYSQLDUMP_SQL"
	  echo "MYSQLDUMP_TAR_GZ = $MYSQLDUMP_TAR_GZ"
	  mysqldump -ufreehackquest_u \
	  -pfreehackquest_u \
	  freehackquest \
	  > $MYSQLDUMP_SQL
	  tar -zcvf $MYSQLDUMP_TAR_GZ $MYSQLDUMP_SQL
	  rm $MYSQLDUMP_SQL
	fi

example run_backup.sh:

	#!/bin/bash

	cd fhq.github.temp
	bash update_sources/backup.sh '/var/www/html/fhq'

# NOTE: 'update.sh' - helper script for update sources

	#!/bin/bash

	# must running in root of repository folder

	echo "pwd = $(pwd)/$1/php/*"
	echo "1 = $1"

	#copy config
	CONFIG_DIR_TEMP="`pwd`/../config.fhq.temp"
	CONFIG_DIR_OLD="$1/config"
	echo "CONFIG_DIR_TEMP = $CONFIG_DIR_TEMP"
	echo "CONFIG_DIR_OLD = $CONFIG_DIR_OLD"

	if [ ! -d $CONFIG_DIR_TEMP ]; then
	  mkdir $CONFIG_DIR_TEMP
	fi

	if [ -d $CONFIG_DIR_OLD ]; then
	  cp -rf $CONFIG_DIR_OLD/* $CONFIG_DIR_TEMP
	fi

	#copy folder files
	FILES_DIR_TEMP="`pwd`/../files.fhq.temp"
	FILES_DIR_OLD="$1/files"
	echo "FILES_DIR_TEMP = $FILES_DIR_TEMP"
	echo "FILES_DIR_OLD = $FILES_DIR_OLD"

	if [ ! -d $FILES_DIR_TEMP ]; then
	  mkdir $FILES_DIR_TEMP
	fi

	if [ -d $FILES_DIR_OLD ]; then
	  cp -rf $FILES_DIR_OLD/* $FILES_DIR_TEMP
	fi

	# remove old files 
	if [ -d $1 ]; then
	  rm -rf $1
	fi

	# cp files
	FILES_PHP="`pwd`/php/fhq/*"
	echo "FILES_PHP = $FILES_PHP"

	if [ ! -d $1 ]; then
	  mkdir $1
	  cp -rf $FILES_PHP $1
	fi

	#copy files comeback
	if [ -d $FILES_DIR_OLD ]; then
	  cp -rf $FILES_DIR_TEMP/* $FILES_DIR_OLD
	  chown www-data:www-data -R $FILES_DIR_OLD
	fi

	#remove temporary folder
	rm -rf $FILES_DIR_TEMP

	#copy config comeback
	if [ -d $CONFIG_DIR_OLD ]; then
	  cp -rf $CONFIG_DIR_TEMP/* $CONFIG_DIR_OLD
	fi

	#remove temporary folder
	rm -rf $CONFIG_DIR_TEMP

	echo "completed"

example run 'update.sh':

	#!/bin/bash

	# clone or pull data
	if [ -d fhq.github.temp ]; then
	   cd fhq.github.temp
	   git checkout .
	   git pull
	else
	   git clone https://github.com/sea-kg/fhq fhq.github.temp
	   cd fhq.github.temp
	fi

	chmod +x update_sources/update.sh
	chmod +x update_sources/backup.sh
	bash update_sources/backup.sh '/var/www/html/fhq'
	bash update_sources/update.sh '/var/www/html/fhq'


# 'make_links.sh' - helper script for make link to /var/www/html/fhq or /var/www/html/fhq

	#!/bin/bash
	echo "This script can help to your create link on fhq/www folder"

	WWWFOLDER=""

	if [ $1 -eq 1 ]; then
		WWWFOLDER=/var/www/html/fhq
	elif [ $1 -eq 2 ]; then
		WWWFOLDER=/var/www/html/fhq
	else
		echo "Help:"
		echo "	$0 1 - link to /var/www/html/fhq"
		echo "	$0 2 - link to /var/www/html/fhq"
		exit;
	fi

	if [ -L "$WWWFOLDER" ]; then
		echo "rm old link $WWWFOLDER"
		sudo rm $WWWFOLDER
	fi
	if [ -d "$WWWFOLDER" ]; then
		echo "Please, remove (or move) folder $WWWFOLDER and try again"
		exit;
	fi

	echo "link to $WWWFOLDER"
	sudo ln -s "`pwd`/www" "$WWWFOLDER"

# other platforms for ctf

http://phptrends.com/dig_in/ctf

https://github.com/Nakiami/mellivora

https://github.com/Hazelwire/Hazelwire

https://github.com/echothrust/athcon-ctf

http://openinfreno.sourceforge.net/

# PS Biography platform

Brief history and biography platform.

Будучи студентом в 2010-2011 году я был в составе команды keva.

После нескольких игр я имел представление о том что такое игры ctf.

Начинал с ресурса http://hax.tor.hu/ ресурс мне понравился.

В итоге я начал думать над идеей создать платформу для игр но такую что бы можно было
ее использовать как архив заданий для тренировки новичков да и так что бы можно было выбирать задания по силам.

Также я обсуждал это идею с командой. Прошло время, после учебы решил продолжать заниматься ctf но уже в организации и подготовки команды к играм.

И все таки вернулся к своей идеи (FreeHackQuest) и написал первую версию (2012), что бы провести в университете игру.

При поддержке Алексея Гуляева (Второго) и Витали Шишкина а также Константина Крючкова, Никиты Чижова и других членов команды мы развесили объявления и набрали новичков.

В следующем году (2013) платформа была полностью переписана и проведен FHQ 2013 опять же для рекрутинга, но в этот раз играли не только с нашего университета но и с других городов.

После этого мы оставли висеть платформу в режими онлайн http://fhq.keva.su (идея была Алексея Гуляева), что позволило обратить внимание на keva.

Эта же платформа (с рядом доработок) была использована при проведении SibirCTF 2014. Там были написаны модули для проведения attack-defence игр, но в последней версии были удалены из за сложности конфигруирования и начличия дополнительного демона.

В 2014 год дизайн был полностью сменен в очередной раз, доработки и прочее. Проведен в последний раз FHQ 2014.

В 2015 решил закончить и заморозить разработку как платформы архива игр и проведения локальных небольших ctf в виде jeopady.
