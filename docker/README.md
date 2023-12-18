# Docker
`Dockerfile` a `docker-compose` pro jednoduché spuštění webů.

## Vývojový mód
- nutnost: nainstalovaný `docker` a `docker-compose-plugin`
- vytvoření aktuální image: `docker compose build` (potřeba spustit před prvním spuštěním)
- spuštění: `docker compose up`
- přístup: `localhost` na portech `8080-8084`
- spouštění příkazů uvnitř dockeru (`composer install`, `npm build` atd.): `docker exec -it webs <příkaz>`
    - nainstalování composer balíčků: `docker exec -it webs composer install`
    - nainstalování npm balíčků: `docker exec -it webs npm install`
    - build CSS a JS: `docker exec -it webs npm run build`
    - dostání se do konzole přímo uvnitř kontejneru: `docker exec -it webs /bin/bash`
