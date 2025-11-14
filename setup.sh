#!/bin/bash
set -euo pipefail

COLOR_RED='\033[0;31m'
COLOR_YELLOW='\033[0;33m'
COLOR_GREEN='\033[0;32m'
COLOR_NONE='\033[0m'

heading() {
	printf "\n${COLOR_YELLOW}--- $1 ---${COLOR_NONE}\n"
}

check_command() {
	if ! command -v $1 >/dev/null 2>&1
	then
		echo "Error: command $1 could not be found, please install it"
		exit 1
	else
		echo "$1 found"
	fi
}

docker_run() {
	docker run --user "$(id -u):$(id -g)" -t --volume .:/var/www/html webs $@
}


heading "Check dependencies"

SCRIPT_DIR=$( cd -- "$( dirname -- "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )
if [ "$SCRIPT_DIR" != "$(pwd)" ]
then
	echo "You are not in the scripts directory, please cd into it"
	echo "cd $SCRIPT_DIR"
	exit 1
fi

check_command docker

heading "Setting up configuration"

read -r -p "FKSDB login: " fksdb_login
read -r -p "FKSDB password: " -s fksdb_password
echo ""

configDirectory='app/config/local/'
mkdir -p $configDirectory

for service in vyfuk fykos fol fof dsef; do
	file="$configDirectory/$service.neon"
	echo "Generating configuration for $service"
	cp "app/config/config.local.neon.example" $file
	sed -i "s/<fksdb-login>/$fksdb_login/" $file
	sed -i "s/<fksdb-password>/$fksdb_password/" $file
done

mkdir -p "temp"


heading "Setting up docker"

cp "docker/docker-compose.yml.sample" "docker/docker-compose.yml"
sed -i "s/<uid>/$(id -u)/" "docker/docker-compose.yml"
sed -i "s/<gid>/$(id -g)/" "docker/docker-compose.yml"


heading "Build docker containers"
(cd docker && docker compose build --pull)


heading "Run composer"
docker_run composer install


heading "Run npm"
docker_run npm install
docker_run npm run build


heading "Setup finished"
printf "${COLOR_GREEN}Setup finished successfully${COLOR_NONE}\n"
echo "Next steps:"
echo "- Move to the docker directory and start docker containers by running:"
echo "  - cd docker"
echo "  - docker compose up"
echo "- When wanting to stop the containers, press CTRL+C"
