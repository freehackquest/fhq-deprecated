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
  chown www-data:ww-data -R $FILES_DIR_OLD
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
