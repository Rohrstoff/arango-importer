# Arango Importer

## Software Requirements

- **[Composer](https://getcomposer.org/)**
- **[Docker](https://www.docker.com/products/docker-desktop)**

## Components

### Docker

The project contains a docker folder, where the docker image to run this project is located. The Docker image contains the following containers:

- [php:7.4-fpm](https://hub.docker.com/_/php)
- [nginx:1.17.0](https://hub.docker.com/_/nginx)
- [arangodb:3.6.3](https://hub.docker.com/_/arangodb)

### PHP

Following PHP packages have been installed using Composer:

- [Altorouter](https://altorouter.com/)
- [Twig](https://twig.symfony.com/)
- [ArangoDB-PHP](https://www.arangodb.com/docs/stable/drivers/php.html)

## Running the importer

To run the importer enter the following commands in a command-line tool (PowerShell, Bash, Terminal etc.):

- Inside the root folder: <code>composer install</code> _(To install the necessary PHP packages)_
- Inside the <code>docker</code> folder: <code>docker-compose up -d</code> _(To run the docker image)_
- Run the following command <code>docker network inspect docker_backend</code>
- Search for the IP of the ArangoDB Container
- Replace the value of <code>ConnectionOptions::OPTION_ENDPOINT</code> in <code>ArangoHelper.php</code> with the current IP of the container

Now the importer can be accessed under http://localhost:8080. Select the JSON-file to be imported in ArangoDB and submit the form. To check whether the Collections and the Graph have been created correctly, the ArangoDB Web Interface can be used. It can be accessed under http://localhost:8529. Username and password can be found in the File ArangoHelper.
