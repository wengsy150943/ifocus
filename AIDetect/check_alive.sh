#!/bin/bash

detect=$1
key=$2
if [ ${detect} = "face" ]
then
cd ./FaceDetect
else
cd ./HandDetect
fi

rate=True
while [ ${rate} = "True" ]
do
echo ""
name=$(ls -ltr ../../srs/trunk/objs/nginx/html/${key}/ | grep [*.]ts$ |tail -n 1|awk '{print $9}')
if [ ${detect} = "face" ]
then
rate=`python3 ./detect.py --source ../../srs/trunk/objs/nginx/html/${key}/${name}`
else
rate=`python3 ./detect.py --source  ../../srs/trunk/objs/nginx/html/${key}/${name}`
fi
echo -n $rate
done
php ../../ifocus-back/end_live.php
