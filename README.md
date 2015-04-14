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


# Full manual for debian (apache2 + php5 + mysql5):

Database

	$ sudo apt-get install mysql-server
	$ sudo apt-get install mysql-client
	

php + apache
	
	$ sudo apt-get install php5
	$ sudo apt-get install apache2
	$ sudo apt-get install libapache2-mod-php5
	
php + mysql
	
	$ sudo apt-get install php5-mysql
	
for captcha
	
	$ sudo apt-get install php5-gd
	
for mailing
	
	$ sudo apt-get install php-pear
	$ sudo pear install Mail-1.2.0
	$ sudo pear install Net_SMTP

restart apache
	
	$ sudo /etc/init.d/apache2 restart
	
connect to mysql and create database
	
	$ sudo mysql -p -u root

and execute next queries:

	> CREATE DATABASE `freehackquest` CHARACTER SET utf8 COLLATE utf8_general_ci;
	> CREATE USER 'freehackquest_u'@'localhost' IDENTIFIED BY 'freehackquest_u';
	> GRANT ALL PRIVILEGES ON freehackquest.* TO 'freehackquest_u'@'localhost'
	> WITH GRANT OPTION;
	> FLUSH PRIVILEGES;

get sources

	$ sudo git clone https://github.com/sea-kg/fhq.git fhq.git
	$ sudo cd fhq.git
	
create struct of database

	$ sudo mysql -u root -p freehackquest < freehackquest.sql
	
copy sources to /var/www

	$ sudo cp -R www/* /var/www/
	$ sudo rm /var/www/index.html
	$ sudo cd /var/www/config
	$ sudo cp config.php.inc config.php

-> here configure config.php

change access to folders

	$ sudo cd /var/www/files
	$ sudo chmod 777 /var/www/dumps
	$ sudo chmod 777 /var/www/users
	$ sudo chmod 777 /var/www/games
	$ sudo chmod 777 /var/www/quests

Configure updalod options

Please change in /etc/php5/apache2/php.ini

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
			DocumentRoot /var/www/fhq/
			ServerName fhq.keva.su
			ErrorLog /var/log/apache2/fhq.keva.su-error_log
			CustomLog /var/log/apache2/fhq.keva.su-access_log common

			<Directory "/var/www/fhq/files">
					AllowOverride None
					Options -Indexes
					Order allow,deny
					Allow from all
			</Directory>

			<Directory /var/www/fhq/config>
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

1. Find a number

	$ sudo iptables -L INPUT -n -v --line-numbers
	
2. And remove

	$ sudo iptables -D INPUT number_of_your_entry

And of course, keep

	$ sudo iptables-save

# NOTE: Firewall on CentOS 7 for database (use firewalld)

CentOS + MariaDB + Firewall.
CentOS 7 + firewalld:

	$ sudo firewall-cmd --zone=public --add-port=3306/tcp --permanent
	$ sudo firewall-cmd --reload
