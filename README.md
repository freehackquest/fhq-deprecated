FreeHackQuest installation
===

"free-hack-quest" of "fhq" it is a web-engine for running CTF-challenges

Short Manual:

1. Download sources.

2. copy www/* to your web folder

3. change access (linux 777) to folders:
	files/games
	files/dump
	files/quests
	files/users

4. execute SQL-script: 'freehackquest.sql'

5. copy 'config/config.php.ini' to 'config/config.php' and configure it

6. Login in the FHQ (admin:admin123).

7. Create new user with role 'admin' and with correct email

8. Relogon with new user and remove old admin.

9. Create new game.

10. Create new quest and check it.


Full manual for debian (apache2 + php5 + mysql5):

# apt-get install git-core
# apt-get install php5
# apt-get install apache2
# apt-get install mysql-server
# apt-get install mysql-client
# apt-get install php5-mysql
# apt-get install php5-gd
# apt-get install libapache2-mod-php5
# /etc/init.d/apache2 restart
# mysql -p -u root

and execute next queries:
	CREATE DATABASE `freehackquest` CHARACTER SET utf8 COLLATE utf8_general_ci;
	CREATE USER 'freehackquest_u'@'localhost' IDENTIFIED BY 'freehackquest_u';
	GRANT ALL PRIVILEGES ON freehackquest.* TO 'freehackquest_u'@'localhost' WITH GRANT OPTION;
	FLUSH PRIVILEGES;

# git clone https://github.com/sea-kg/fhq.git fhq.git
# cd fhq.git
# mysql -u root -p freehackquest < freehackquest.sql


# cp -R www/* /var/www/
# rm /var/www/index.html
# cd /var/www/config
# cp config.php.inc config.php

-> here configure config.php


