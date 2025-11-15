#!/bin/sh
set -e

echo "-------------------------ENTRYPOINT\n"

# Linux / WSL2 : change /var/run/docker.sock ownership if present to allow testcontainers to work
if [ -S /var/run/docker.sock ]; then
    chown ${UID:-1000}:${DOCKER_GID:-999} /var/run/docker.sock || true
fi

# keep container alive
exec frankenphp run --config /etc/caddy/Caddyfile --adapter caddyfile
