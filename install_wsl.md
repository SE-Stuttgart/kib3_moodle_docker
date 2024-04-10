# Installation docker-moodle in Windows 11 mit WSL
Die folgende Anleitung wurde freundlicherweise von Herrn Stöhr bereitgestellt. Vielen Dank!

Vorraussetzung: Account bei Docker.com --> https://www.docker.com/

**Wichtig: die folgenden Aktivitäten außerhalb vor dem Start von WSL durchführen!**

Docker-Desktop starten
cmd --> Eingabeaufforderung als Administrator starten

## Überprüfung der WSL-Installation
1. `wsl --status` --> Überprüfen des WSL-Status
2. `wsl --set-default-version <Version>` --> Festlegen der WSL-Standardversion auf 2
3. `wsl --install` --> WSL installieren, wenn Nichts angezeigt wird oder wsl.exe --install -d  Debian, wenn man Debian haben möchte, ansonsten kann auch jede andere verfügbare Distribution gewählt werden
4. `wsl --update` --> bei Notwendigkeit Aktualisieren von WSL (ist bei soeben durchgeführter Installation nicht notwendig)
5. `wsl --set-default Debian` --> Festlegen der Standard-Linux-Verteilung, ist nicht notwendig, wenn wsl.exe --install -d  Debian durchgeführt wurde
6. `wsl --list --verbose` --> Auflisten der installierten Linux-Verteilungen
7. `wsl --list --online` --> Auflisten der verfügbaren Linux-Verteilungen

## Optional:
- `wsl --help` --> Help-Befehl
-  `wsl --shutdown` --> Herunterfahren
-  `wsl hostname -i` oder `cat /etc/resolv.conf`  --> Ermitteln der IP-Adresse

Quelle: https://learn.microsoft.com/de-de/windows/wsl/

## Jetzt gehts los:
1. öffne Docker-Desktop --> `Einstellungen-->Resources-->WSL integration-->ACTIVATE Debian`
2. Windows-Explorer öffnen, gehe in Linux und mit Rechtsklick auf Debian-Verzeichnis "Im Terminal öffnen"  
3. `wsl` --> startet WSL im home-Verzeichnis
   `cd /home/admin/` --> ins admin-Verzeichnis wechseln
4. `git clone https://github.com/SE-Stuttgart/kib3_moodle_docker.git` --> Clone von https://github.com/SE-Stuttgart/kib3_moodle_docker.git einfügen
5. Moodle-Backupdatei `sicherung-moodle-kib3-zq-v3.0.mit.plugins.2024-02-15.mbz` nach `Debian\home\admin\kib3_moodle_docker\moodle-docker` kopieren
6. `cd /home/admin/kib3_moodle_docker/moodle-docker` --> Wechseln ins Verzeichnis `moodle-docker`
7. `./bin/moodle-docker-compose up -d` --> Dieses Tool ermöglicht es Ihnen, mehrere Docker-Container als eine Anwendung zu definieren und zu verwalten. Sie verwenden eine Compose-Datei, die in YAML geschrieben ist, um die Konfiguration für diese Container festzulegen. In der Compose-Datei können Sie Dinge wie Dienste, Netzwerke, Volumes und Umgebungsvariablen 	definieren.
8. Docker-Desktop neu starten
9. Container `moodle-docker` starten
10. Im Browser zu (http://localhost:8081/)[http://localhost:8081/] navigieren --> über Container link localhost öffnen
11. Moodle wird angezeigt, Anmeldung als admin mit Passwort wie in `conf.env` beschrieben

**Bei Problemen Neuinstallation auf eine saubere Docker-Desktop-Installation achten!**
- Settings - Software updates "Check for updates"
- Troubleshoot - "Clean / Purge data" oer "Reset to factory defaults"
- Löschen von Verzeichnissen mit Inhalt --> `sudo rm -r [Ordner Name]`
