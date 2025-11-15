FROM dunglas/frankenphp:php8.4 AS base

# user/group
ARG UID=1000
ARG GID=1000
ARG APP_USER=symfony

# Install sudo to allow our entrypoint to set ownership on docker.sock
# Create a symfony user to avoid working as root
RUN addgroup --gid $GID $APP_USER \
 && adduser --uid $UID --gid $GID --disabled-password --gecos "" $APP_USER

WORKDIR /app

# ---- build stage: install dependencies (cached layer) ----
FROM base as deps

# Install PHP extensions for Symfony + PostgreSQL + Redis
RUN install-php-extensions \
    pdo_pgsql \
    redis \
    opcache \
    intl \
    zip

# Install Xdebug
RUN install-php-extensions xdebug

# Configure PHP
COPY docker/ci/php.ini /usr/local/etc/php/php.ini

# copy only files needed for composer install to benefit from layer cache
COPY symfony/composer.json symfony/composer.lock /app/

RUN composer install --no-interaction --prefer-dist --no-progress --no-scripts --ignore-platform-reqs \
    && rm -rf /root/.composer/cache/*


# ---- test/runtime stage: copy full app and vendor from deps ----
FROM base AS runner

# copy full project
COPY symfony /app

# copy vendor from deps
COPY --from=deps /app/vendor /app/vendor
COPY --from=deps /app/composer.lock /app/composer.lock
COPY --from=deps /app/composer.json /app/composer.json

WORKDIR /app

RUN chown $APP_USER:$APP_USER /app

USER $APP_USER

CMD ["sleep", "infinity"]