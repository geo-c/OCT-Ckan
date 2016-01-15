#!/bin/bash

crontab -l > mycron

dir="/home/"
ip=""
user=`whoami`
dir+=$user
dir+="/"

if [ $# -ge "1" ]; then
	if [ $1 = "-h" ] || [ $1 = "--help" ]; then
		echo "Installation of the Server Status Toolkit"
		echo " "
		echo "install.sh /path/to/www/html [options] "
		echo " "
		echo "options:"
		echo "-h, --help                show brief help"
		echo "-d, --directory=DIR       specify a directory to store the toolkit"
		echo "-r, --restricted-ip=IP      specify an ip to restrict server requests"
		exit 0
	else
		www=$1
		shift
		while test $# -gt 0; do
			case "$1" in
				-d)
				        shift
				        if test $# -gt 0; then
				                dir=$1
				        else
				                echo "no directory specified"
				                exit 1
				        fi
				        shift
				        ;;
				--directory*)
				        dir=`echo $1 | sed -e 's/^[^=]*=//g'`
				        shift
				        ;;
				-r)
				        shift
				        if test $# -gt 0; then
				                ip=$1
				        else
				                echo "no ip address specified"
				                exit 1
				        fi
				        shift
				        ;;
				--restricted-ip*)
				        export ip=`echo $1 | sed -e 's/^[^=]*=//g'`
				        shift
				        ;;
				*)
				        break
				        ;;
			esac
		done

		mkdir $www"/geoc/" 2> /dev/null
		mkdir $dir"/geo-c/" 2> /dev/null
		cp "services.php" $www"/geoc/"
		cp "frequency.php" $www"/geoc/"
		cp "index.html" $www"/geoc/"
		if [ -n "$ip" ]; then
		    echo $ip > $www"/geoc/restrictedIP.file"
		fi
		cp "uptime.sh" $dir"/geo-c/"
		cp "checkstatus.sh" $dir"/geo-c/"
		cp "frequency.sh" $dir"/geo-c/"
		echo $dir"/geo-c/" > $www"/geoc/geocpath.file"
		chmod u+x $dir"/geo-c/uptime.sh"
		chmod u+x $dir"/geo-c/checkstatus.sh"
		chmod u+x $dir"/geo-c/frequency.sh"
		echo "59 23 * * * $dir/uptime.sh $www/geoc/" >> mycron
		echo "00 * * * * $dir/checkstatus.sh 1" >> mycron
		echo "daemon ALL = (ALL) NOPASSWD: $dir/geo-c/frequency.sh" | (sudo EDITOR="tee -a" visudo) > /dev/null
	fi
fi

crontab mycron
rm mycron

