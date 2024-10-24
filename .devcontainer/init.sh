set -xe

git submodule init && git submodule update
composer install
npm install --no-save
#npm run build

rm -rf temp/cache