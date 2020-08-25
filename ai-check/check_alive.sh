#!/bin/bash

while true
do
echo ""
name=$(ls -l ../srs/trunk/objs/nginx/html/live/ | grep [*.]ts$ |tail -n 1|awk '{print $9}')
rate=`python3 face.py  ../srs/trunk/objs/nginx/html/live/${name}`
echo -n $rate
if [ `echo $rate"<0.6"|bc` -eq 1 ];then
	break;
fi
done
