#!/bin/bash

# clone or pull data
if [ -d fhq.github ]; then
   cd fhq.github
   git checkout .
   git pull
else
   git clone https://github.com/sea-kg/fhq fhq.github
   cd fhq.github
fi

chmod +x update_sources/update.sh
bash update_sources/update.sh '/var/www/fhq'
