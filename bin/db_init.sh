#!/bin/bash

source .env
export DB_NAME DB_USER DB_PASS DB_HOST
envsubst < database/init.sql | sudo mariadb -u root

