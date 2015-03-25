#!/bin/bash
echo "This script can help to your create link on fhq/www folder"

WWWFOLDER=""

if [ $1 -eq 1 ]; then
	WWWFOLDER=/var/www/html/fhq
elif [ $1 -eq 2 ]; then
	WWWFOLDER=/var/www/fhq
else
	echo "Help:"
	echo "	$0 1 - link to /var/www/html/fhq"
	echo "	$0 2 - link to /var/www/fhq"
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

