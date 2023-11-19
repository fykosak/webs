set -xe

git submodule init && git submodule update
composer install
npm install
#npm run build

if [ ! -f app/config/config.fof.local.neon ]; then
cp .devcontainer/config.local.neon.example app/config/config.fof.local.neon
fi
if [ ! -f app/config/config.fol.local.neon ]; then
cp .devcontainer/config.local.neon.example app/config/config.fol.local.neon
fi
if [ ! -f app/config/config.dsef.local.neon ]; then
cp .devcontainer/config.local.neon.example app/config/config.dsef.local.neon
fi
if [ ! -f app/config/config.vyfuk.local.neon ]; then
cp .devcontainer/config.local.neon.example app/config/config.vyfuk.local.neon
fi
if [ ! -f app/config/config.fykos.local.neon ]; then
cp .devcontainer/config.local.neon.example app/config/config.fykos.local.neon
fi

app/i18n/compile.sh
rm -rf temp/cache