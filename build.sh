#!/usr/bin/env bash

sudo docker build -t slim3boilerplate . --no-cache
sudo docker-compose up -d
ergo run --domain .local
