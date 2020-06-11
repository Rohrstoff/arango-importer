<?php

namespace App\Controller;

use App\Database\ArangoHelper;
use ArangoDBClient\EdgeDefinition;
use ArangoDBClient\Graph;

class ImportController
{
	public function handleUpload()
	{
		$fileContent = json_decode(file_get_contents($_FILES['jsonfile']['tmp_name']));
		$arango = new ArangoHelper();

		$this->importVertices( json_encode($fileContent->vertices), $arango );
		$this->importEdges( json_encode( $fileContent->edges ), $arango );

		$this->createGraph( $arango );
	}

	public function importVertices($verticesAsString, $arango)
	{
		$vertices = $this->prepareVertices( $verticesAsString );
		$id = $arango->createCollection( 'devices' );

		foreach ( $vertices as $vertex )
		{
			$vertex['_key'] = strval($vertex['_key']);

			$arango->saveDocument( $id, $vertex );
		}
	}

	public function importEdges($edgesAsString, $arango)
	{
		$edges = $this->prepareEdges($edgesAsString);
		$id = $arango->createEdgeCollection( 'events' );

		foreach ( $edges as $edge )
		{
			$edge['_from'] = 'devices/' . $edge['_from'];
			$edge['_to'] = 'devices/' . $edge['_to'];

			$arango->saveDocument( $id, $edge );
		}
	}

	private function prepareVertices($vertices)
	{
		return json_decode(str_replace( '"_id"', '"_key"', $vertices ), true);
	}

	private function prepareEdges($edges)
	{
		return json_decode(str_replace(['"_id"', '"_outV"', '"_inV"'], ['"_key"', '"_from"', '"_to"'], $edges), true);
	}

	private function createGraph($arango)
	{
		$graph = new Graph();
		$graph->set('_key', 'iot_landscape');
		$graph->addEdgeDefinition( new EdgeDefinition( 'events', 'devices', 'devices' ) );
		$arango->saveGraph( $graph );
	}
}
