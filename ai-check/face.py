#!/usr/bin/env python3
# coding: utf-8

# In[6]:

import sys
import cv2

name = sys.argv[1]
#print(name)
# 加载视频，参数为视频文件路径
cameraCapture = cv2.VideoCapture(name)
# cv2级联分类器CascadeClassifier,xml文件为训练数据
face_cascade = cv2.CascadeClassifier('./haarcascade_frontalface_alt.xml')
# 读取数据
success, frame = cameraCapture.read()
flag_sum = 0
catch_sum = 0
while success and cv2.waitKey(1) == -1:
    # 读取数据
    ret, img = cameraCapture.read()
    # 进行人脸检测
    faces = face_cascade.detectMultiScale(img, 1.3, 5)
    #检测到人脸返回true，未检测到返回false
    flag=(faces!=())
    # 绘制矩形框
    #for (x, y, w, h) in faces:
    #    img = cv2.rectangle(img, (x, y), (x+w, y+h), (255, 0, 0), 2)
    # 设置显示窗口
    #cv2.namedWindow('camera', 0)
    #cv2.resizeWindow('camera', 840, 480)
    # 显示处理后的视频
    #cv2.imshow('camera', img)
    #检测到人脸返回true，未检测到返回false
    if(flag == True):
        catch_sum = catch_sum + 1
    flag_sum = flag_sum + 1
    # 读取数据
    success, frame = cameraCapture.read()

# 释放视频
cameraCapture.release()
# 释放所有窗口
#cv2.destroyAllWindows()
print(1.0*catch_sum/flag_sum)

# In[ ]:





# In[ ]:




