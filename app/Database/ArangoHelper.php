<?php

namespace App\Database;

use ArangoDBClient\Collection;
use ArangoDBClient\CollectionHandler;
use ArangoDBClient\Connection;
use ArangoDBClient\ConnectionOptions;
use ArangoDBClient\DocumentHandler;
use ArangoDBClient\UpdatePolicy;

class ArangoHelper
{
	private $connection;
	private $collectionHandler;
	private $documentHandler;

	public function __construct()
	{
		$connectionOptions = [
			// database name
			ConnectionOptions::OPTION_DATABASE => '_system',
			// server endpoint to connect to
			ConnectionOptions::OPTION_ENDPOINT => 'tcp://172.22.0.3:8529', //ip can be found using 'docker network inspect docker_backend'
			// authorization type to use (currently supported: 'Basic')
			ConnectionOptions::OPTION_AUTH_TYPE => 'Basic',
			// user for basic authorization
			ConnectionOptions::OPTION_AUTH_USER => 'root',
			// password for basic authorization
			ConnectionOptions::OPTION_AUTH_PASSWD => 'test05',
			// connection persistence on server. can use either 'Close' (one-time connections) or 'Keep-Alive' (re-used connections)
			ConnectionOptions::OPTION_CONNECTION => 'Keep-Alive',
			// connect timeout in seconds
			ConnectionOptions::OPTION_TIMEOUT => 3,
			// whether or not to reconnect when a keep-alive connection has timed out on server
			ConnectionOptions::OPTION_RECONNECT => true,
			// optionally create new collections when inserting documents
			ConnectionOptions::OPTION_CREATE => true,
			// optionally create new collections when inserting documents
			ConnectionOptions::OPTION_UPDATE_POLICY => UpdatePolicy::LAST,
		];

		$this->connection = new Connection( $connectionOptions );
		$this->collectionHandler = new CollectionHandler( $this->connection );
		$this->documentHandler = new DocumentHandler( $this->connection );
		\ArangoDBClient\Exception::enableLogging();
	}

	public function createCollection($name)
	{
		$collection = new Collection();
		$collection->setName( $name );

		return $this->collectionHandler->create( $collection );
	}

	public function saveDocument($collectionName, $document)
	{
		$this->documentHandler->save( $collectionName, $document );
	}
}
