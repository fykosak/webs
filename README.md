# FYKOS-webs

Nette multisite codebase for the [Fyziklani](https://fyziklani.cz) and [Online Physics Brawl](https://online.fyziklani.cz) competitions.

This project uses modified bootstrap file to support multiple websites sharing components and models.

## Requirements
 - `PHP 8.1`
 - `Apache` with `mod_rewrite`
 - sql database

## Installation

1. Clone the repository `git clone --recurse-submodules ...`
2. Configure the web server (see below for example configuration)
3. Create a configuration files and fill all the necessary information
   - `cp app/config/config.local.neon.example app/config/config.fof.local.neon`
   - `cp app/config/config.local.neon.example app/config/config.fol.local.neon`
   - `cp app/config/config.local.neon.example app/config/config.dsef.local.neon`
4. Follow the build instructions

### Example Apache configuration
Following configuration expects repository located in `/var/www/fykos-webs`.
```apacheconf
<Directory /var/www/fykos-webs>
        AllowOverride All
        Require all granted
</Directory>

<VirtualHost online.fyziklani.cz.local online.fyziklani.org.local>
        ServerName online.fyziklani.cz.local
        ServerAlias online.fyziklani.org.local
        DocumentRoot /var/www/fykos-webs/www/fol
        SetEnv NETTE_DEVEL 1
</VirtualHost>

<VirtualHost fyziklani.cz.local fyziklani.org.local>
        ServerName fyziklani.cz.local
        ServerAlias fyziklani.org.local
        DocumentRoot /var/www/fykos-webs/www/fof
        SetEnv NETTE_DEVEL 1
</VirtualHost>

<VirtualHost dsef.cz.local dsef.org.local>
        ServerName dsef.cz.local
        ServerAlias dsef.org.local
        DocumentRoot /var/www/fykos-webs/www/dsef
        SetEnv NETTE_DEVEL 1
</VirtualHost>

<VirtualHost fykos.cz.local fykos.org.local>
        ServerName fykos.cz.local
        ServerAlias fykos.org.local
        DocumentRoot /var/www/fykos-webs/www/fykos
        SetEnv NETTE_DEVEL 1
</VirtualHost>
```

Do not forget to modify your `/etc/hosts` file to point to the correct IP address of your server.
```etc/hosts
127.0.0.1    online.fyziklani.cz.local
127.0.0.1    online.fyziklani.org.local
127.0.0.1    fyziklani.cz.local
127.0.0.1    fyziklani.org.local
127.0.0.1    dsef.cz.local
127.0.0.1    dsef.org.local
127.0.0.1    fykos.cz.local
127.0.0.1    fykos.org.local
```

These domains need to be configured in `app/config/config.*.local.neon` under `parameters.domains` in order to work.

## Build instructions

*You need `composer` and `npm` with `node` to build this project.*

1. Run `composer install` to install php dependencies.
2. Run `npm install` to install javascript dependencies and build tools.
3. Run `npm run build` to compile css and js files.

You can use `npm run dev` to automatically rebuild files when they are changed.

## Detailed manual for WSL, Ubuntu

Installing Prerequisites
1. open wsl
2. if not installed, install `apache2` (`sudo apt install apache2`)
3. if not installed, install `php8.1` (`sudo apt install php8.1`, you might also need `php8.1-dom` and `php8.1-soap`)
4. if not installed, install `mysql` (google how to do that - e.g. via `sudo apt install mysql-server`)
5. if not installed, install `composer` (google how to do that - sudo apt install composer does not work as of July 2022)
6. if not installed, install `node`, version at least 16. Alternatively, install nvm and then `nvm use 16`
7. if not installed, install `gettext` (via `sudo apt install gettext`), check `locale -a` if you have `cs_CZ` and `en_US` installed, otherwise use `sudo locale-gen cs_CZ`, `sudo locale-gen cs_CZ.UTF-8` and then `sudo update-locale`
8. pull this repository to a location where you want to have it (e.g. `cd C:/data/fykos && git pull <repourl>`)
* Note: you may encounter various problems, e.g. php not being executed (try `sudo apt install libapache2-mod-php` and `sudo a2enmod php8.1`) or with "ERROR: Module mpm_event is enabled" (try `sudo a2dismod mpm_event` and `sudo a2enmod mpm_prefork`, and then `sudo service apache2 restart`)

Configuring Apache
* Explanation: the webserver reads all files from `sites-enabled` and loads the configuration from them.
Other files in these directories are symlinks which point to files in `sites-available` (handy for turning off
the page by deleting the symlink without deleting the actual configuration)
1. open wsl
2. create a new file `sudo nano /etc/apache2/sites-enabled/fykos-webs.conf`
3. paste the example apache configuration (above) into this file
4. change the Directory and all DocumentRoots to be where you have downloaded your repo (e.g. `<Directory /mnt/c/data/fykos/webs>` and `DocumentRoot /mnt/c/data/fykos/webs/www/fol`)
4. save it

Configuring hosts file
* Explanation: Since wsl has its own IP adress, and we use a browser over on Windows, we need
to configure Windows's DNS to route us over to wsl. 
1. open the file `C:/Windows/System32/drivers/etc/hosts` as administrator
2. paste the example contents of `/etc/hosts` and save
3. open wsl and type `ip a` and find out the IP adress of WSL (usually something like eth0: ... *inet 172.22.207.240/20* ...)
4. replace all occurrences of `127.0.0.1` in `C:/Windows/System32/drivers/etc/hosts` with the found IP (e.g. `172.22.207.240`)
5. Sadly, steps 3 and 4 need to be repeated everytime wsl gets restarted, because a new IP is generated every time.


Configuring MySql
* Explanation: some parts of the web need an access to a database to work.
1. open wsl
2. start mysql (`sudo mysql`). You may encounter errors, in which case google how to solve them.
3. you are now in mysql shell (the line starts with `mysql>`). 
4. Create databases for FOL and FOF: (a) `create database fol ;` (b) `create database fof ;`
5. Create user with a password, e.g.: `CREATE USER 'fykos'@'localhost' IDENTIFIED BY 'password';`
6. Set privileges for the newly created user:  (a) `GRANT ALL PRIVILEGES ON fol.* TO 'fykos'@'localhost' WITH GRANT OPTION;` (b) same, only with fof
7. Close mysql by ctrl+D


Inserting tables and data
* Explanation: In FOL, we the database also needs to have some tables and preferably dummy data.
1. open wsl terminal and `sudo mysql`
2. type `use fol` (tells mysql to modify the `fol` database)
3. copy and paste the contents of the file `data/sql/schema_fol.sql` into mysql shell and press Enter (creates tables)
4. copy and paste the contents of the file `data/sql/example_fol.sql` into mysql shell and press Enter (inserts data)


Configuring neon files
* Explanation: these files contain secret data such as passwords and connection strings. 
It is something like appsettings.json. These data are then used in the application to 
connect to various resources, such as the database.
* `.local` files always override the configurations from files without the `.local`. We only 
edit the `.local` files, which are intentionally excluded from git.
1. tell somebody to send you at least the `fksdbDownloader` credentials and fill them out
2. fill out the database name in the connection strings (e.g. `dsn: 'mysql:host=localhost;dbname=fof'`)
3. fill out the `parameters.database` section with the credentials that you've used in MySql (`user: fykos`, `password: password`)
4. set gameApiURL to an empty string: `gameApiURL: ''`


## Troubleshooting

* "could not find driver" ... if this error is shown `Nette\Database\ConnectionException could not find driver Caused by PDOException`, it is likely because you do not have something installed, see https://stackoverflow.com/questions/2852748/pdoexception-could-not-find-driver
