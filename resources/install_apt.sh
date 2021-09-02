PROGRESS_FILE=/tmp/dependancy_camera_in_progress
if [ ! -z $1 ]; then
	PROGRESS_FILE=$1
fi
touch ${PROGRESS_FILE}
echo 0 > ${PROGRESS_FILE}
echo "Launch install of camera dependancy"
sudo apt-get update
echo 50 > ${PROGRESS_FILE}
sudo apt-get install -y ffmpeg
echo 70 > ${PROGRESS_FILE}
sudo apt-get install -y libav-tools
echo 80 > ${PROGRESS_FILE}
sudo apt-get install -y php-gd
echo 100 > ${PROGRESS_FILE}
echo "Everything is successfully installed!"
rm ${PROGRESS_FILE}
sudo systemctl restart apache2
