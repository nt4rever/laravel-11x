#!/usr/bin/env sh
set -e

mkdir -p /data/.minio.sys/buckets;

cp -r /policies/* /data/.minio.sys/;

cp -r /export/* /data/;

minio server /data --console-address ":9001";
