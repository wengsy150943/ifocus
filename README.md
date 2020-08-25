# ifocus

ifocus的代码全部放在里面了，如果配好环境的话，应该可以进行后端代码的演示。

## ai-check/AIDetect

需要python3.5及cv2库。

算法相关的代码。

## srs

需要nginx，其安装请直接看官网http://nginx.org/en/linux_packages.html，中文安装教程不值得信赖。

直播的代码，来自https://github.com/ossrs/srs。部署时可能需要先重新安装，再用该文件夹覆盖,已开启hls，切片的ts文件在./trunk/objs/nginx/html中。

## ifocus-back

需要apache/php/mariadb-server/mysql，请注意防火墙和默认目录、权限等问题。

后端代码，可以在./index.html查看说明和演示。
