touch /tmp/dependancy_camera_in_progress
echo 0 > /tmp/dependancy_camera_in_progress
echo "Launch install of camera dependancy"
sudo apt-get update
echo 50 > /tmp/dependancy_camera_in_progress
sudo apt-get install -y libav-tools
echo 100 > /tmp/dependancy_camera_in_progress
echo "Everything is successfully installed!"
rm /tmp/dependancy_camera_in_progress