#!/bin/bash

# must running in root of repository folder

echo "pwd = $(pwd)/$1/php/*"
echo "1 = $1"

# backup
BACKUP_DIR="`pwd`/../fhq.autobackups"

if [ ! -d $BACKUP_DIR ]; then
  mkdir $BACKUP_DIR
fi
FILE_TAR_GZ="$BACKUP_DIR/fhq_php_`date +%Y-%m-%d`.tar.gz"

if
echo "FILE_TAR_GZ = $FILE_TAR_GZ"
tar -zcvf $FILE_TAR_GZ $1

# copy files
FILES_PHP="`pwd`/php/*"
cp -rf $FILES_PHP $1

echo "all"