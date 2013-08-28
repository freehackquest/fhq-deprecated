#!/bin/bash

# clone or pull data
if [ -d fhq.github ]; then
   cd fhq.github
   git pull
   cd ..
else
   git clone https://github.com/sea-kg/fhq fhq.github   
fi

chmod +x fhq.github/update_sources/update.sh
bash fhq.github/update_sources/update.sh '/var/www/fhq'
