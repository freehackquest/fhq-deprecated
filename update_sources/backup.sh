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
