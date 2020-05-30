<?php

namespace App\Controller;

use App\Database\ArangoHelper;
use ArangoDBClient\Document;

class ImportController
{
	private $vertices;

	public function handleUpload()
	{
		$fileContent = json_decode(file_get_contents($_FILES['jsonfile']['tmp_name']));

		$this->importVertices( json_encode($fileContent->vertices) );
		//$this->edges = json_encode($fileContent->edges);
		echo 'Import complete';
	}

	public function importVertices($verticesAsString)
	{
		$arango = new ArangoHelper();
		$vertices = $this->prepareVertices( $verticesAsString );
		$arango->createCollection( 'vertices' );

		foreach ( $vertices as $vertex )
		{
			$document = Document::createFromArray( $vertex );

			$arango->saveDocument( 'vertices', $document );
		}
	}

	private function prepareVertices($vertices)
	{
		return json_decode(str_replace( '"_id"', '"_key"', $vertices ));
	}

	public function getVertices()
	{
		return $this->vertices;
	}
}
