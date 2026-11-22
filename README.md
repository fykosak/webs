# FYKOS webs

Codebase pro Nette weby pro účely FYKOSu a Výfuk.

Weby jsou naprogramovány v PHP pomocí frameworku Nette. Běží pod Apache v rámci
docker kontejnerů.

## Vývoj

### Prerekvizity

Aby bylo možné spustit weby, je potřeba mít nainstalovaný docker. Pokud jste na
Linuxu, využijte [návodu na instalaci](https://docs.docker.com/engine/install/).

Pokud jste na Windows, je asi nejlepším způsobem nainstalovat 
[WSL](https://learn.microsoft.com/en-us/windows/wsl/install) (Ubuntu nebo
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

## Nahrávání fotek

Fotky a obecně média se nachází ve složce `media/<web>/`, fotky potom v
`media/<web>/photos/`. Při zveřejňování fotek je potřeba je nahrát do správné
podsložce. To, v jak pojmenovaných složkách mají fotky být, je specifikováno v
`ImageService.php`.

Pokud jsou fotky nějakého specifického typu (např. u Fyziklání), vkládají se
ještě do jedné podsložky, viz `EventImageType.php`.

Po nahrání fotek je potřeba spustit script `tools/resizeImages.php`, který
vygeneruje potřebné velikosti fotek, jinak se je nepovede prohlížeči načíst.
Tomuto scriptu se jako první argument dává cesta, ve které se obrázky nachází.
Pokud tuto akci děláte na serveru, je potřeba ji provést pomocí `docker exec`
se specifikovaným uživatelem, aby bylo možné spustit PHP.

```
docker exec -u <docker-user-uid> -it webs php tools/resizeImages.php media/<web>/photos/<subdirectory>
```
