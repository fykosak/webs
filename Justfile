set shell := ["bash", "-uc"]

# https://just.systems/

chooser := "sk --preview-window right:65% --preview 'just --show {} | (bat --language Makefile --color always --decorations never || cat)'"
uid := `id -u`
gid := `id -g`
user := uid + ":" + gid
runner := "podman"
tag := "webs"
name := "webs"

_default:
    @just --list

# Interactive recipe selector (if you have `skim` installed)
choose:
    @-just --choose --chooser "{{ chooser }}"

# Build OCI image for webs
build:
    {{ runner }} build \
        --file docker/Dockerfile \
        --tag "{{ tag }}"

# Start container, optionally passing flags
start *FLAGS:
    {{ runner }} run \
        --volume .:/var/www/html \
        --volume ./docker/config/apache.conf:/etc/apache2/sites-available/000-default.conf \
        --volume ./docker/config/php.ini:/usr/local/etc/php/php-local.ini \
        --volume ./docker/config/xdebug.ini:/usr/local/etc/php/conf.d/xdebug.ini \
        --volume ./docker/config/msmtprc:/etc/msmtprc \
        --add-host "vyfuk.local:127.0.0.1" \
        --add-host "fykos.local:127.0.0.1" \
        --add-host "fol.local:127.0.0.1" \
        --add-host "fof.local:127.0.0.1" \
        --add-host "dsef.local:127.0.0.1" \
        --env 'TZ=Europe/Prague' \
        --env 'NETTE_DEVEL=1' \
        --env 'MODE=dev' \
        --cap-add=NET_BIND_SERVICE \
        --publish 8080:8080 \
        --publish 8081:8081 \
        --publish 8082:8082 \
        --publish 8083:8083 \
        --publish 8084:8084 \
        --user "{{ user }}" \
        --userns keep-id \
        --name "{{ name }}" \
        --replace \
        {{ FLAGS }} \
        "{{ tag }}"

# Stop container
stop:
    -{{ runner }} kill "{{ name }}" &> /dev/null
    -{{ runner }} rm "{{ name }}" &> /dev/null

# Restart container
restart: stop start

# Ensure that container is running in the background
ensure:
    @{{ runner }} ps \
        --filter "name={{ name }}" \
        --filter "status=running" \
        --noheading | grep "{{ name }}" &> /dev/null || just start --detach

# Execute provided commands in container
exec +CMD='bash': ensure
    {{ runner }} exec \
        --interactive \
        --tty \
        "{{ name }}" \
        {{ CMD }}

# Prepare dev env
setup: build ensure
    just exec npm install
    just exec composer install
    just exec npm run build

# Watch compileable files
dev: ensure
    just exec npm run dev

# Create config for FKSDB login
login:
    #!/usr/bin/env bash
    set -eumo pipefail
    echo "Login to FKSDB"
    read -r -p "login: " fksdblogin
    read -r -p "password: " -s fksdbpassword
    echo
    target="app/config/local"
    mkdir -p "$target"
    for service in vyfuk fykos fol fof dsef; do
        file="$target/$service.neon"
        echo "# Generated from Justfile" > "$file"
        i() {
            echo "$1" >> "$file"
        }
        i "parameters:"
        i "    # FKSDB API endpoint"
        i "    fksdbDownloader:"
        i "        login: '$fksdblogin'"
        i "        password: '$fksdbpassword'"
        i "        url: 'https://db.fykos.cz/api/'"
        i "    problemManagerDownloader:"
        i "        login: ''"
        i "        password: ''"
        i "        url: ''"
        echo "configured $service"
    done
    echo "config files appeared in $target"

# Delete containers an OCI images
[confirm("you sure you want to delete dev containers [y/N]")]
clean: stop
    -{{ runner }} rmi {{ tag }}
