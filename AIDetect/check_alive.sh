#!/bin/bash

detect=$1
key=$2
if [ ${detect} = "face" ]
then
cd ./FaceDetect
else
cd ./HandDetect
fi


while true
do
echo ""
name=$(ls -l ../../srs/trunk/objs/nginx/html/${key}/ | grep [*.]ts$ |tail -n 1|awk '{print $9}')
if [ ${detect} = "face" ]
then
rate=`python3 ./detect.py --source ../../srs/trunk/objs/nginx/html/${key}/livestream-10.ts`
else
rate=`python3 ./detect.py --source  ../../srs/trunk/objs/nginx/html/${key}/${name}`
fi
echo -n $rate
#if [ `echo $rate"<0.6"|bc` -eq 1 ];then
#	break;
#fi
done
