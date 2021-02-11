# Development Environment

## Requirements
The host system is expected to have **Docker** and **Docker Compose** installed.

## Installation

1\. Unzip database dump to load as MySQL entrypoint
```bash
$ unzip data/policom_b2b.zip -d data/
```
:information_source: _This is temporary. Should be setup by Docker._
\
\
\
2\. **Start the development environment** with Docker
```bash
$ docker-compose up -d
```
:information_source: This command should be used anytime you wish to start the environment.
\
\
\
3\. Remove strict sql_mode from MySQL
```bash
mysql> SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));
```
:information_source: _This is temporary. Should be setup by Docker._
\
\
\
4\. Install Composer dependencies
```bash
$ docker-compose exec app composer install
```