# Server Status Module

## Description
Server Status Module provides a serie of scripts to request information about the usage of the server. Currently, the only options available are: to ask for particular services being executed in the hosting machine and consult the uptime log of the system.

## Requirements
- A Linux-based OS
- An HTTP server runing PHP
- 

## Installation

For the instalation of the Server Status Module you only have to run install.sh, you can use the flag -h to see a list of the available options. You should indicate the folder (or a subfolder) where your HTTP server is running. In addition you can also specify where you want the Module to be installed (home by default) and/or if you want to restrict the request to some IP adresses.

It is very important **not to move** the Module files after the installation, otherwise you will have to edit the user's crontab manually to indicate the new paths of the files.

```
$ ./ install.sh -h
"Instalation of the Server Status Module"

"install.sh /path/to/www/html [options] "

"options:"
"-h, --help                show brief help"
"-d, --directory=DIR       specify a directory to store the Module (home by default)"
"-r, --restricted-ip=IP      specify an ip to restrict server requests (non restricted by default)"
```
## Usage

- services.php: request for the availability of any service
 POST Parameters: s=service1 s=service2

- frequency.php: change the frequency for checking the uptime of the system
 POST Parameter: h=number_of_hours
 Requisites: number_of_hours must be divisible by 24

## FAQ's

** I have moved the Module, what do I have to modify in the crontab file? **
You will have to change the paths of the *uptime.sh* and *checkstatus.sh* files using **crontab -e**. Example:
```
"59 23 * * * /home/user/geo-c/uptime.sh /var/www/html/geoc/" >> mycron
"00 * * * * /home/user/geo-c/checkstatus.sh 1" >> mycron
```


