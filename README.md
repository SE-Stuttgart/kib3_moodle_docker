# moodle_docker
Automated Distribution of Moodle, Plugins and Teaching Materials

## Installation 
0. Install Docker desktop: https://www.docker.com/products/docker-desktop/
1. Clone this repository
2. Change directory to the directory containing the ``config.env`` file
3. Copy a full course backup into this directory
4. Open `config.env` and edit the variables to your liking (e.g., name of moodle, admin credentials, ...)
  - MAKE SURE TO CHANGE THE VALUE OF `COURSE_BACKUP_FILE` to the name of the backup file
5. Execute `bin/moodle-docker-compose up -d` (this might take quite a while).
   Wait for everything to be completed (after the terminal says the container is running, open the Docker desktop app, go to the `Containers` tab on the left panel:
   <img width="1538" alt="Bildschirmfoto 2023-06-23 um 10 34 41" src="https://media.github.tik.uni-stuttgart.de/user/3040/files/d66942ae-a6c3-4007-95fb-97b46e5c8a28">
  Then, click the `moodle-docker` container. This opens a log view. Watch this log to see when the installation finished:
 <img width="1288" alt="Bildschirmfoto 2023-06-23 um 11 01 27" src="https://media.github.tik.uni-stuttgart.de/user/3040/files/2f483b41-7912-4806-bf2d-195aa3b81130">
 
6. Once the container is running, you can access it through http://localhost:8000 in your browser

7. Use the admin credentials from ``config.env`` to log in

8. To stop / restart the container, just open the Docker desktop app and press the STOP / START button for this container

NOTE: If moodle feels slow (MacOS / Windows), allocate more resources (CPU, Memory) through the docker settings (e.g., through the Docker desktop app)
