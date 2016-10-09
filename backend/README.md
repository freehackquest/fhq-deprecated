# FHQ Backend Deamon (Experimental)

This app writed on Qt5 use QWebSocket

## Requirements

	$ sudo apt install g++ make qtchooser qt5-default libqt5websockets5 libqt5websockets5-dev

## Build

	$ qmake
	$ make

## configure

	$ sudo ln -s `pwd`/etc/fhqd /etc/fhqd
	$ sudo nano /etc/fhqd/conf.ini
