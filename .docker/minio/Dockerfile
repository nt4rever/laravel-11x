FROM minio/minio:RELEASE.2021-11-09T03-21-45Z

COPY ./.docker/minio/entrypoint.sh /entrypoint.sh

RUN chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]
