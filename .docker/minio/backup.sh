#!/bin/sh

docker compose cp minio:/data/laravel ./.docker/minio/export
