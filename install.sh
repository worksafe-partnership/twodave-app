#!/bin/bash

echo "Installing Composer deps..."

docker run --rm \
    -v ${PWD}:/app \
    --workdir /app \
    composer:1.4 composer install

echo "Installing NodeJS deps..."

docker run --rm \
    -v ${PWD}:/app \
    --workdir /app \
    node:12.0 npm install && npm run production
