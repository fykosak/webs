# Devcontainers jak na to

## Jak používat?

Najděte záložku ports/porty a klikněte na stránku, kterou chcete zobrazit. Pokud to nelze, tak v následujícím seznamu najděte stránku, kterou chcete zobrazit a zapamatujte si port. Následně se připojte na <http://localhost:číslo_portu>. Pokud používáte některé internetové rozhraní, je možné, že se v průběhu přesměrovávání mezi stránkami objeví port na konci adresy, přestože je psán jinde v daném prostředí. V takovém případě port stačí umazat a s trochou štěstí se zde znovu neobjeví.

- 8080: "<http://vyfuk.org>"
- 8081: "<http://online.fyziklani.cz>"
- 8082: "<http://online.fyziklani.org>"
- 8083: "<http://fyziklani.cz>"
- 8084: "<http://fyziklani.org>"
- 8085: "<http://dsef.cz>"
- 8086: "<http://dsef.org>"
- 8087: "<http://fykos.cz>"
- 8088: "<http://fykos.org>"

## Jak zprovoznit lokálně?

    Následující návody nejsou úplně otestovány. V případě že si nevíte rady nebo něco nefunguje, tak se nebojte napsat o pomoc. Pokud znáte lepší řešení, tak se také ozvěte.

### linux

1. Nainstalujte docker: Postupujte podle [tohoto návodu (https://docs.docker.com/engine/install/)](https://docs.docker.com/engine/install/) nebo v případě Debian/Ubuntu cca následovně:

    ``` bash
    # Add Docker's official GPG key
    sudo apt-get update
    sudo apt-get install ca-certificates curl gnupg
    sudo install -m 0755 -d /etc/apt/keyrings
    curl -fsSL https://download.docker.com/linux/debian/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
    sudo chmod a+r /etc/apt/keyrings/docker.gpg

    # Add the repository to Apt sources (hodí se změnit bookworm za svojí verzi):
    echo \
        "deb [arch="$(dpkg --print-architecture)" signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/debian \
        "bookworm" stable" | \
        sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
    sudo apt-get update

    # Install
    sudo apt-get install docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
    ```

2. nainstalujte vscode [https://code.visualstudio.com/download](https://code.visualstudio.com/download)
3. použijte ctrl+shift+p -> Dev Containers: Reopen in Container

Je správně, že se dole na liště bude točit "Configuring Dev Container (show log)", protože postStartCommand spouští webpack, který sleduje změny. Po prvním sestavení assets je možné tento proces ukončit.

### windows

1. Nainstalujte wsl [https://learn.microsoft.com/en-us/windows/wsl/install](https://learn.microsoft.com/en-us/windows/wsl/install)
2. nainstalujte vscode [https://code.visualstudio.com/download](https://code.visualstudio.com/download)
3. Ve wsl nainstalujte Docker (viz. návod pro Linux)
4. Ve wsl nainstalujte a nastavte ssh, tak aby bylo vidět z windows, bez zadání hesla (použitím klíčů).
5. Přidejte následující skript (např jako code.sh)

    ``` bash
    [ -f /var/run/docker.pid ] || sudo /etc/init.d/docker start
    if [ ! "$(ps -elf | grep -v grep | grep /usr/sbin/sshd)" ];
        then sudo /etc/init.d/ssh start;
    fi
    filename=$1
    parentdir=$(dirname "${filename}")
    if [ -d "${filename}" ]; then
    codeopenpath=$(cd "${filename}" && pwd)
    elif [ -d "${parentdir}" ]; then
    codeopenpath=$(cd "${parentdir}" && pwd)/$(basename "${filename}")
    fi
    cmd.exe /Q /C code --folder-uri vscode-remote://ssh-remote+localhost/$codeopenpath &
    tokill=$!
    sleep 1
    kill $tokill
    ```

    a případně si přidejte alias `alias code='/home/user/.local/bin/startcode.sh` do `.bashrc` nebo `.profile`
6. Do wsl naklonujte tento repositář
7. Ve wsl spustě výše uvedený skrypt/příkaz code + umístění složky kterou chcete otevřít(popřípadě nic pokud chcete otevřít aktuální složku)
8. použijte ctrl+shift+p -> Dev Containers: Reopen in Container

Je správně, že se dole na liště bude točit "Configuring Dev Container (show log)", protože postStartCommand spouští webpack, který sleduje změny. Po prvním sestavení assets je možné tento proces ukončit.
