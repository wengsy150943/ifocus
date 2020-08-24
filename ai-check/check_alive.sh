#!/bin/bash

while true
do
name=$(ls -l ../srs/trunk/objs/nginx/html/live/ | grep [*.]ts$ |tail -n 1|awk '{print $9}')
sleep 1
echo $name
python3 face.py  ../srs/trunk/objs/nginx/html/live/${name}
done
