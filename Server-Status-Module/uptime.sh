#!/bin/bash

if [ ! -f $1"uptime2.log" ]; then
    echo -e "SERVER\t\tDATE\t\tUP-HOURS" > $1"uptime2.log"
fi

hours=0

while IFS='' read -r line || [[ -n "$line" ]]; do
    hours="$(expr $hours + $line)"
done < "uptime2.tmp"

if [ $hours -gt "24" ]; then
    hours="24"
fi

server="$(uname -n)"
date="$(date +'%m-%d-%y')"

echo -e "$server\t\t$date\t\t$hours" >> $1"uptime2.log"

rm -f uptime2.tmp
