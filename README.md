# moodle_docker

Automated Distribution of Moodle, Plugins and Teaching Materials for the KIB3 Courses (Currently only Zusatzqualifikation, we will add support for DQR5 soon).

## Installation 
0. Install [Docker desktop](https://www.docker.com/products/docker-desktop/)
   (FOR WINDOWS USERS: Requires to [activate optional windows feature](https://learn.microsoft.com/en-us/windows/application-management/add-apps-and-features) `Windows Subsystem for Linux` - see the [German WSL Installation Guide](https://github.com/SE-Stuttgart/kib3_moodle_docker/blob/main/install_wsl.md))
1. Clone this repository (or download as a .zip file and extract)
2. Change directory to the directory containing the ``config.env`` file
3. Copy a full moodle course backup (either for ZQ, DQR5, or both) into this directory (file ending with `.mbz`)
5. Open `config.env` and edit the variables to your liking
  - Make sure to change the values of `COURSE_BACKUP_FILE_ZQ` / `COURSE_BACKUP_FILE_DQR` to the name of the backup files you copied in step 3. If you only copied a a file for the ZQ, empty the value for COURSE_BACKUP_FILE_DQR="" (and vice versa).
  - We recommend not changing the default Moodle & Plugin versions and plugin selection, as this could lead to errors.
  - Change the value of `MOODLE_SERVER_URL` to the URL or IP adress of your server hosting this moodle instance (including protocol: `http` / `https` and port, if not `80`).
  - Change the value of `MOODLE_SERVER_PORT` to the port you want to serve moodle from. Default for http is `80`, if you want to test locally, choose something different (e.g. `8081`).
  - If you want to use SSL (protocol `https`):
    - Change `MOODLE_SERVER_SSL` to `true`.
    - Copy your SSL certificate (file ending in `.crt`) and SSL certificate private key into the same folder as the course backup (step 3).
    - Change the values of `MOODLE_SERVER_SSL_CERTIFICATE_FILE` and `MOODLE_SERVER_SSL_PRIVATE_KEY_FILE` to point to the files you just copied.
  - IMPORTANT: CHANGE THE FOLLIWING PASSWORDS AND USERNAMES:
    - `MOODLE_DOCKER_DBROOTPASS`
    - `MOODLE_DOCKER_DBUSER`
    - `MOODLE_DOCKER_DBPASS`
    - `MOODLE_ADMIN_USER`
    - `MOODLE_ADMIN_PASSWORD`
    - `MOODLE_WEBSERVICE_PASSWORD` (**DO NOT CHANGE `MOODLE_WEBSERVICE_USER`**)
    - `JUPYTER_API_TOKEN`
    - `POSTGRES_PASSWORD`
    - `JWT_SECRET`
5. Execute `./bin/moodle-docker-compose up -d` (this might take a while).
   
  (FOR WINDOWS USERS: if you get an error message like _WSL Kernel version is too low_, you can upgrade it by opening a terminal end executing the command ``wsl --update``. Then, re-try this step)

   Wait for everything to be completed: After the terminal says the container is running, open the Docker desktop app, go to the `Containers` tab on the left panel:
   <img width="1538" alt="Bildschirmfoto 2023-06-23 um 10 34 41" src="https://media.github.tik.uni-stuttgart.de/user/3040/files/d66942ae-a6c3-4007-95fb-97b46e5c8a28">
  Then, click the `moodle-docker` container. This opens a log view. Watch this log to see when the installation finished:
 ![Bildschirmfoto 2023-08-23 um 16 56 48](https://media.github.tik.uni-stuttgart.de/user/3040/files/b8014a87-fd4e-4981-b144-dc641cfe4d41)
 
6. Once the container is running, you can access it through e.g. http://localhost:8081 (if using default confiuration, otherwise navigate to the value you specified in  `MOODLE_SERVER_URL`) in your browser.

7. Use the admin credentials from ``config.env`` to log in. You should see the restored course now.

8. To stop / restart the container, just open the Docker desktop app and press the STOP / START button for this container

NOTE: If moodle feels slow (MacOS / Windows), allocate more resources (CPU, Memory) through the docker settings (e.g., through the Docker desktop app)


## Deleting (Container, Image and all user data)
1. Open the Docker Desktop app, go to the `Containers` tab on the left panel.
   Then, select the checkbox next to the `moodle-docker` container:
   <img width="1286" alt="Bildschirmfoto 2023-09-14 um 09 49 32" src="https://media.github.tik.uni-stuttgart.de/user/3040/files/358857da-16e4-4687-a31c-9e15a39150a8">

   Afterwards, it should look like this:
   <img width="1288" alt="Bildschirmfoto 2023-09-14 um 09 50 55" src="https://media.github.tik.uni-stuttgart.de/user/3040/files/a2e6faf0-bf06-46e4-a11e-2cc42705513c">
  
2. Click `Delete`:
<img width="1297" alt="Bildschirmfoto 2023-09-14 um 09 51 29" src="https://media.github.tik.uni-stuttgart.de/user/3040/files/f3f0703d-fb6d-4ffd-b274-fea02ddcdb2c">

3. Go to the `Images` tab on the left panel.
   Then, select the checkbox next to the `moodle-docker-webserver` image:
   
   <img width="1300" alt="Bildschirmfoto 2023-09-14 um 09 59 21" src="https://media.github.tik.uni-stuttgart.de/user/3040/files/eb0362df-e603-49e2-b34a-51d74dc1c5d9">

4. Click `Delete`:

<img width="1295" alt="Bildschirmfoto 2023-09-14 um 10 02 08" src="https://media.github.tik.uni-stuttgart.de/user/3040/files/1cd3b8d9-aae1-407d-8e1c-f936c59f75ac">

5. Go to the `Volumes` tab on the left panel.
  Then, select the checkbox next to the `moodle-docker_moodle-database` volume: 
  
  <img width="1294" alt="Bildschirmfoto 2023-09-14 um 10 03 10" src="https://media.github.tik.uni-stuttgart.de/user/3040/files/5f8da9d5-f52d-4dd9-a75d-a81f9780f367">
  
6. Click `Delete`:

<img width="1281" alt="Bildschirmfoto 2023-09-14 um 10 03 59" src="https://media.github.tik.uni-stuttgart.de/user/3040/files/d1b8c3a9-5679-41c8-a83e-c2c70e7063b2">

7. OPTIONAL: If you plan to install a newer version of this docker container, please open a terminal and execute `docker builder prune --all`.
   When asked to continue, press `y`:
   
   <img width="718" alt="Bildschirmfoto 2023-09-14 um 10 05 33" src="https://media.github.tik.uni-stuttgart.de/user/3040/files/d3584fe2-517c-49b0-bb1f-358446f9feae">

  After this step, you can get a new version of this container and start over with the installation procedure detailed above.

