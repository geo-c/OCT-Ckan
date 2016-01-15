#!/bin/bash

crontab -l | sed -r 's/checkstatus.sh [1-9]?[0-9]/checkstatus.sh '$1'/' | crontab -
