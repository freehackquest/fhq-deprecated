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

if [ -d $1 ]; then
  FILE_TAR_GZ="$BACKUP_DIR/fhq_php_`date +%Y%m%d-%H%M%S`.tar.gz"
  echo "FILE_TAR_GZ = $FILE_TAR_GZ"
  tar -zcvf $FILE_TAR_GZ $1
fi

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
CONFIG_DIR_TEMP="`pwd`/../files.fhq.temp"
CONFIG_DIR_OLD="$1/files"
echo "CONFIG_DIR_TEMP = $CONFIG_DIR_TEMP"
echo "CONFIG_DIR_OLD = $CONFIG_DIR_OLD"

if [ ! -d $CONFIG_DIR_TEMP ]; then
  mkdir $CONFIG_DIR_TEMP
fi

if [ -d $CONFIG_DIR_OLD ]; then
  cp -rf $CONFIG_DIR_OLD/* $CONFIG_DIR_TEMP
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

#copy config comeback
if [ -d $CONFIG_DIR_OLD ]; then
  cp -rf $CONFIG_DIR_TEMP/* $CONFIG_DIR_OLD
fi

#remove temporary folder
rm -rf $CONFIG_DIR_TEMP

echo "completed"
