FreeHackQuest installation
===

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

6. Login in the FHQ (admin:admin123).

7. Create new user with role 'admin' and with correct email

8. Relogon with new user and remove old admin.

9. Create new game.

10. Create new quest and check it.


Full manual for debian (apache2 + php5 + mysql5):

$ sudo apt-get install git-core
$ sudo apt-get install php5
$ sudo apt-get install apache2
$ sudo apt-get install mysql-server
$ sudo apt-get install mysql-client
$ sudo apt-get install php5-mysql
$ sudo apt-get install php5-gd
$ sudo apt-get install libapache2-mod-php5
$ sudo apt-get install php-pear
$ sudo pear install Mail-1.2.0
$ sudo pear install Net_SMTP
$ sudo /etc/init.d/apache2 restart
$ sudo mysql -p -u root

and execute next queries:
	CREATE DATABASE `freehackquest` CHARACTER SET utf8 COLLATE utf8_general_ci;
	CREATE USER 'freehackquest_u'@'localhost' IDENTIFIED BY 'freehackquest_u';
	GRANT ALL PRIVILEGES ON freehackquest.* TO 'freehackquest_u'@'localhost' WITH GRANT OPTION;
	FLUSH PRIVILEGES;

	$ sudo git clone https://github.com/sea-kg/fhq.git fhq.git
	$ sudo cd fhq.git
	$ sudo mysql -u root -p freehackquest < freehackquest.sql
	$ sudo cp -R www/* /var/www/
	$ sudo rm /var/www/index.html
	$ sudo cd /var/www/config
	$ sudo cp config.php.inc config.php

-> here configure config.php

$ sudo cd /var/www/files
$ sudo chmod 777 /var/www/dumps
$ sudo chmod 777 /var/www/users
$ sudo chmod 777 /var/www/games
$ sudo chmod 777 /var/www/quests

Please change in /etc/php5/apache2/php.ini
	upload_max_filesize = 2M
->
	upload_max_filesize = 100M

and

	post_max_size = 8M
->
	post_max_size = 100M


Login in the FHQ (admin:admin123).
Create new user with role 'admin' and with correct email
Relogon with new user and remove old admin.
Create new game.
Create new quest and check it.



Examples for 

