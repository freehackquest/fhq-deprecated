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

# copy files
FILES_PHP="`pwd`/php/*"
echo "FILES_PHP = $FILES_PHP"
cp -rf $FILES_PHP $1
echo "all"