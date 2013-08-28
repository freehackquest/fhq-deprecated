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
bash update_sources/update.sh '/var/www/fhq'
