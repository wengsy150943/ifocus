#! /usr/bin/env python3
import cv2
import argparse

parser = argparse.ArgumentParser()
parser.add_argument(
    '-src',
    '--source',
    dest='video_source',
    default=0,
    help='Device index of the camera.')
    
args = parser.parse_args()
# 加载视频，参数为视频文件路径
cameraCapture = cv2.VideoCapture(args.video_source)
# cv2级联分类器CascadeClassifier,xml文件为训练数据
face_cascade = cv2.CascadeClassifier('haarcascade_frontalface_alt.xml')
# 读取数据
success, frame = cameraCapture.read()
t=0
f=0
while success and cv2.waitKey(1) == -1:
    # 读取数据
    ret, img = cameraCapture.read()
    # 进行人脸检测
    faces = face_cascade.detectMultiScale(img, 1.3, 5)
    
    if(faces!=()):
        t=t+1
    else:
        f=f+1
            
    # 绘制矩形框
    #for (x, y, w, h) in faces:
    #    img = cv2.rectangle(img, (x, y), (x+w, y+h), (255, 0, 0), 2)
    # 设置显示窗口
    #cv2.namedWindow('camera', 0)
    #cv2.resizeWindow('camera', 840, 480)
    # 显示处理后的视频
    #cv2.imshow('camera', img)
    
    # 读取数据
    success, frame = cameraCapture.read()
#print(t,f)
if((t==0)or(f==0)):
    if(t==0):
        print("False")
    else:
        print("True")
else:
    if((t/(f+t))>0.5):
        print("True")
    else:
        print("False")
# 释放视频
cameraCapture.release()
# 释放所有窗口
cv2.destroyAllWindows()






