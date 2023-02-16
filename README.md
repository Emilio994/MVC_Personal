# Scaffholding

## Avvio del progetto :

    Comandi da lanciare : 

        - npm install per dipendenze js
        - composer install per dipendenza php

## Metodo di utilizzo(ottimale) :

    Sviluppo :
        - Avere una copia locale del DB
        - Lanciare comando da terminale (posizionandosi nella root del progetto) php -S localhost:8000 (porta a discrzione)
        - npm run dev

    Produzione :
        npm run build per minificazione degli assetti e caricamento su ambiente


## Cartelle & Files :

    @ app -> progetto : [
        app/config -> file di configurazione dei path e connessione al DB,
        app/core -> controllers e modelli,
        app/filesystem -> archiviazione documenti
        app/helpers -> tools di debug etc...
        app/public -> ROUTES E ASSETTI(css/js/immagini/fonts/svg) compilati
        app/views -> template per le view da renderizzare
        app/src -> template front-end e assetti da compilare(css/js/immagini/fonts/svg)
    ]

    @ gulp_tasks -> task di gulp per la compilazione degli assetti statici
    
    @ node_modules -> dipendenze javascript
    
    @ vendor -> dipendenze php

    # gulpfile -> bunlder per gli assetti front-end e compilazione dalla cartella src alla public

    # package.json -> elenco dipendenze javascript
    # package-lock.son -> elenco dipendenze(e sotto dipendenze) in uso (creato a seconda della versione di npm & node da cui è stato lanciato npm install)

    # composer.json -> elenco dipendenze php
    # composer.lock -> elenco dipendenze(e sotto dipendenze) in uso (creato a seconda della versione di php & composer da cui è stato lanciato composer install)

## Flusso :

    App -> Riceve la richiesta http
    App -> Passa la richiesta al router
    Router -> Match la route / 404 on fail
    App -> riceve il match dal router => [Controller::class, metodo]
    App -> invoca il controller passandogli i parametri della richiesta
    App -> restitusci la VIEW

## Comandi per sviluppo :

    - npm run dev -> PULISCE i vecchi assetti compilandoli con quelli presenti nella cartella src

## Regole :

    - Le cartelle e le classi all'interno della cartellina core devono essere nominate con lettera maiuscola

## Da implementare :

    Backend = [
        CORS policy
        Supporto per chiamate PUT/PATCH/DELETE/OPTIONS
        MIDDLEWARE
        API
        MODALITA' DI SVILUPPO
    ]

    Frontend = [
        Bundling svg
        Compressione immagini
    ]

    


