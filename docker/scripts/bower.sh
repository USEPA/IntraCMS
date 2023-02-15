#!/bin/bash
BOWERDIRS=(`grep '"name": "bower-asset/' composer.lock | awk '{print $NF}' | cut -f2 -d'"'`)
for BOWERDIR in "${BOWERDIRS[@]}"
    do
    if [[ -d "vendor/${BOWERDIR}" ]] 
    then 
    cp -r vendor/${BOWERDIR} docroot/libraries/
    fi
done
