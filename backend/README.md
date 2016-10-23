# FHQ Backend Deamon (Experimental)

This app writed on Qt5 use QWebSocket

## Requirements

	$ sudo apt install g++ make qtchooser qt5-default libqt5websockets5 libqt5websockets5-dev

## Build

	$ qmake
	$ make

## configure

	$ sudo ln -s `pwd`/etc/freehackquestd /etc/freehackquestd
	$ sudo ln -s `pwd`/etc/init.d/freehackquestd /etc/init.d/freehackquestd
	$ sudo ln -s `pwd`/freehackquestd /usr/bin/freehackquestd
	$ sudo nano /etc/fhqd/conf.ini
	$ sudo /etc/init.d/freehackquestd start
