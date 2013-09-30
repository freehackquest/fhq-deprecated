#!/bin/bash

# clone or pull data
if [ -d fhq.github.temp ]; then
   cd fhq.github.temp
   git checkout .
   git pull
else
   git clone https://github.com/sea-kg/fhq fhq.github.temp
   cd fhq.github.temp
fi

chmod +x update_sources/update.sh
chmod +x update_sources/backup.sh
bash update_sources/backup.sh '/var/www/fhq'
bash update_sources/update.sh '/var/www/fhq'
