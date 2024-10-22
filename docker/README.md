# Docker
`Dockerfile` a `docker-compose` pro jednoduché spuštění webů.

## Vývoj webů
- nutnost: nainstalovaný `docker` a `docker-compose-plugin`
- vytvoření aktuální image: `docker compose build` (potřeba spustit před prvním spuštěním)
- spuštění: `docker compose up`
- doplnění přihlašovacích údajů do FKSDB do `app/config/local/*.neon` (nutno případné promazání cache v `temp` )
- přístup: `localhost` na portech `8080-8084`
- spouštění příkazů uvnitř dockeru (`composer install`, `npm build` atd.): `docker exec -it webs <příkaz>`
    - nainstalování composer balíčků: `docker exec -it webs composer install`
    - nainstalování npm balíčků: `docker exec -it webs npm install`
    - build CSS a JS:
        - jednorázově: `docker exec -it webs npm run build`
        - kontinuálně: `docker exec -it webs npm run dev`
    - dostání se do konzole přímo uvnitř kontejneru: `docker exec -it webs /bin/bash`

## Produkce
### Setup
- vytvoření hlavní složky pro vše, například `data`
- clone repozitáře `webs` do této složky `data`, ve výsledku tedy `data/webs`
- zkopírování souboru `docker-compose.prod.yml` do `data/docker-compose.yml`
    ```
    cp webs/config/docker-compose.prod.yml docker-compose.yml
    ```
- vytvoření všech potřebných složek
    ```
    mkdir -p config/local temp log photos/dsef photos/fof photos/fol photos/fykos photos/vyfuk
    ```
- zkopírování Apache konfigurace
    ```
    cp webs/config/apache.conf config/
    ```
- vytvoření složky `config/local` pro lokální konfigurace všech webů a její naplnění
    ```
    cp webs/app/config/config.local.neon.example config/local/dsef.neon
    cp webs/app/config/config.local.neon.example config/local/fol.neon
    cp webs/app/config/config.local.neon.example config/local/fof.neon
    cp webs/app/config/config.local.neon.example config/local/fykos.neon
    cp webs/app/config/config.local.neon.example config/local/vyfuk.neon
    ```
- změna potřebné konfigurace ve všech souborech a zapnutí `docker compose`
- nainstalování composer a npm balíčků
- build npm

## Posílání mailů
Aby fungovalo posílání mailů, je potřeba nastavit SMTP server, který bude PHP,
respektive příkaz `sendmail` dodáván balíčkem `msmtp` a `msmtp-mta`. To se dělá
v `msmtprc` (viz https://wiki.archlinux.org/title/msmtp). Jako výchozí hodnota
je nastaveno `host.docker.internal`, tedy IP adresa hostovacího zařízení, na
kterém musí běžet SMTP server.
