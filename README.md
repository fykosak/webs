# FYKOS webs

Codebase pro Nette weby pro účely FYKOSu a Výfuk.

Weby jsou naprogramovány v PHP pomocí frameworku Nette. Běží pod Apache v rámci
docker kontejnerů.

## Vývoj

### Prerekvizity

Aby bylo možné spustit weby, je potřeba mít nainstalovaný docker. Pokud jste na
Linuxu, využijte [návodu na instalaci](https://docs.docker.com/engine/install/).

Pokud jste na Windows, je asi nejlepším způsobem nainstalovat WSL (Ubuntu nebo
Debian) a využít návodu pro Linux. Alternativně lze nainstalovat [Docker
desktop](https://docs.docker.com/desktop/setup/install/windows-install/) přímo
na Windows.

Pro MacOS je možné použít pouze [Docker desktop](https://docs.docker.com/desktop/setup/install/mac-install/).

Při instalaci na Linuxu chcete hlavně balíčky `docker` a
`docker-compose-plugin` (případně např. u Arch Linuxu pojmenováno
`docker-compose`). Po instalaci je možné, že nebude docker spuštěn, stačí jej
`systemctl` zapnout nebo restartovat počítač.

Po instalaci je možné, že váš linuxový uživatel nebude mít přístup k dockeru.
Je nutné se přidat do unixové skupiny `docker`, abyste dostali přístup k
souboru `/var/run/docker.sock`.

### První nastavení

V root složce se nachází script `setup.sh`. Ten po spuštění vykoná několik kroků:

- vytvoří konfigurační soubory a doplní do nich přístupové údaje
- vytvoří docker compose soubor a vytvoří příslušné kontejnery
- pomocí kontejneru nainstaluje přes `composer` a `npm` příslušné balíčky a zkompiluje css/js

Postup:

1. Spusťte setup script v **hlavní složce** pomocí `./setup.sh`
2. Vyplňte hodnoty požadované scriptem
3. Běžte do podložky `docker` (`cd docker`)
4. Spusťte docker kontejnery pomocí `docker compose up`
5. V prohlížeči běžte na url `http://localhost:<port>`, kde `<port>` je číslo od 8080 až 8084:
    - Výfuk -- [http://localhost:8080](http://localhost:8080)
    - FYKOS -- [http://localhost:8081](http://localhost:8081)
    - Fyziklání Online -- [http://localhost:8082](http://localhost:8082)
    - Fyziklání -- [http://localhost:8083](http://localhost:8083)
    - DSEF -- [http://localhost:8084](http://localhost:8084)
6. Spuštěný docker compose v terminálu lze ukončit pomocí CTRL+C

### Následný vývoj

Po prvotním nastavení stačí opakovat kroky 3--6 ze sekce první nastavení. Není
tedy třeba pokaždé spouštět script `setup.sh`.

Pokud chcete, aby vám weby běžely nepřetržitě a nemuseli jste mít otevřený
terminál, ve je docker spuštěn, lze pomocí `docker compose up -d` ve složce
`docker` spustit kontejnery na pozadí. Následně je lze zastavit přes
`docker compose stop`.

Více informací o tom, jak používat docker a jak v něm spouštět příkazy
naleznete v [Docker README](docker/README.md).

## Manuální instalace

Script `setup.sh` provádí úkony, které by bylo nutné udělat jinak ručně. Jedná
se o:

- ve složce `app/config/` zkopírování konfiguračního souboru `config.local.neon.sample` do složky `local` pod názvy `fykos.neon`, `vyfuk.neon`, `fol.neon`, `fof.neon`, `dsef.neon`
- zkopíruje soubor `docker/docker-compose.yml.sample` do `docker/docker-compose.yml` a vyplní `UID` a `GID` uživatele
- buildne kontejnery přes `docker compose build`
- pomocí vytvořeného kontejneru nainstaluje balíčky pro composer a npm a spustí `npm run build`

## Development using podman

As a convenience for `podman` based setups, you can use [just](https://just.systems/)
command runner for your development environment. For `docker` see below. All
commands are documented by running `just` in this directory.

If you want to speedrun the setup, here is a tasklist:

1. Install `just` and `podman`
2. Configure your FKSDB login with `just login` (this may break with `'` in password)
3. Download deps via `just setup`
4. When developing, start with `just dev`
5. End your session using `just stop`

To use `docker` instead of `podman`, instead of `just cmd` write `just runner=docker cmd`.
This is untested, so contact @rkuklik if you run into issues.

To access the websites (after running `just dev`), following links can be used:
