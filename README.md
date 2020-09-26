# What is this?

One guy was asking about some crawler on #symfony channel, so I just created
this example repository which uses `symfony/panther` component for that.

## Requirements

* [docker-compose](https://docs.docker.com/compose/install/)
* If you are not using docker-compose, then just check what is done in `Makefile`

### Recommendations

* `*nix platform` - most likely you're going to host your application on *nix
  platform - so I would recommend to do development also on that platform.
* `Makefile` support - if you don't have this you need to look `Makefile` file
  to see what each `make` command is doing.
  
## Installation

This installation guide expects that you're using `docker-compose` and your
operation system has `Makefile` support.

### 1. Clone repository

Use your favorite IDE and get checkout from GitHub or just use following
command

```bash
git clone https://github.com/tmac14/home-accounting
```

### 2. Start application

For this just run following command:

```bash
make start
```

This command will create two (2) containers to run this application. Those 
containers are following:

* php (this is for actual application)
* nginx (this will serve application)

### 3. Using application

By default `make start` command starts those two containers and exposes 
following to your `localhost`:

* 8000 (nginx + php-fpm)

This application is usable within your browser on `http://localhost:8000`
address.

### 4. Getting shell to container

After you've run `make start` command you can list all running containers with
`docker ps` command.

To get `shell` access inside one of those containers you can run following
command:

```bash
make bash
```

### 5. Building containers

For time to time you probably need to build containers again. This is something
that you should do everytime if you have some problems to get containers up and
running. This you can do with following command:

```bash
make start-build
```

## License

[The MIT License (MIT)](LICENSE)

Copyright © 2020 Tarmo Leppänen
