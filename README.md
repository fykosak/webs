web-fyziklani
=======

Nette multisite codebase for the [Fyziklani](https://fyziklani.cz) and [Online Physics Brawl](https://online.fyziklani.cz) competitions.

This project uses modified bootstrap file to support multiple websites sharing components and models.

## Requirements
 - `PHP 7.4` or `PHP 8.0`
 - `Apache` with `mod_rewrite`
 - sql database

## Installation

1. Clone the repository `git clone --recurse-submodules ...`
2. Configure the web server (see below for example configuration)
3. Create a configuration files and fill all the necessary information
   - `cp app/config/config.local.neon.example app/config/config.fof.local.neon`
   - `cp app/config/config.local.neon.example app/config/config.fol.local.neon`
4. Follow the build instructions

### Example Apache configuration
Following configuration expects repository located in `/var/www/web-fyziklani`.
```apacheconf
<Directory /var/www/web-fyziklani>
        AllowOverride All
        Require all granted
</Directory>

<VirtualHost *:80>
        ServerName online.fyziklani.cz.local
        ServerAlias online.fyziklani.org.local
        DocumentRoot /var/www/web-fyziklani/www/fol
        SetEnv NETTE_DEVEL 1
</VirtualHost>

<VirtualHost *:80>
        ServerName fyziklani.cz.local
        ServerAlias fyziklani.org.local
        DocumentRoot /var/www/web-fyziklani/www/fof
        SetEnv NETTE_DEVEL 1
</VirtualHost>
```

Do not forget to modify your `/etc/hosts` file to point to the correct IP address of your server.
```etc/hosts
127.0.0.1    online.fyziklani.cz.local
127.0.0.1    online.fyziklani.org.local
127.0.0.1    fyziklani.cz.local
127.0.0.1    fyziklani.org.local
```

These domains need to be configured in `app/config/config.*.local.neon` under `parameters.domains` in order to work.

## Build instructions

*You need `composer` and `npm` with `node` to build this project.*

1. Run `composer install` to install php dependencies.
2. Run `npm install` to install javascript dependencies and build tools.
3. Run `npm run build` to compile css and js files.
4. Run `npm run compile_translation`.

You can use `npm run dev` to automatically rebuild files when they are changed.
