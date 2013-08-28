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

FILE_TAR_GZ="$BACKUP_DIR/fhq_php_`date +%Y%m%d-%H%M%S`.tar.gz"
echo "FILE_TAR_GZ = $FILE_TAR_GZ"

tar -zcvf $FILE_TAR_GZ $1

#copy config
CONFIG_DIR="`pwd`/../fhq.configfolder"
echo "CONFIG_DIR = $CONFIG_DIR"
if [ ! -d $CONFIG_DIR ]; then
  mkdir $CONFIG_DIR
fi
cp -rf "$1/config/*" $CONFIG_DIR


# remove old files 
rm -rf $1

# replacment files
FILES_PHP="`pwd`/php/fhq/*"
echo "FILES_PHP = $FILES_PHP"
cp -rf $FILES_PHP $1

#copy config comback
cp -rf $CONFIG_DIR "$1/config/*"

#remove temporary folder
rm -rf $CONFIG_DIR

echo "completed"