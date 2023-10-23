set -xe

git submodule init && git submodule update
composer install
npm install
#npm run build

cp app/config/config.local.neon.example app/config/config.fof.local.neon
cp app/config/config.local.neon.example app/config/config.fol.local.neon
cp app/config/config.local.neon.example app/config/config.dsef.local.neon
cp app/config/config.local.neon.example app/config/config.vyfuk.local.neon
cp app/config/config.local.neon.example app/config/config.fykos.local.neon

app/i18n/compile.sh
rm -rf temp/cache