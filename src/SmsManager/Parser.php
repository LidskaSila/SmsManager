<?php

namespace LidskaSila\SmsManager;

use GuzzleHttp\Psr7\Response as GuzzleResponse;

class Parser
{

	public static function parseXmlResponseBody(GuzzleResponse $response, array $config = [])
	{
		$disableEntities = libxml_disable_entity_loader(true);
		$internalErrors  = libxml_use_internal_errors(true);
		try {
			// Allow XML to be retrieved even if there is no response body
			$xml = new \SimpleXMLElement(
				(string) $response->getBody() ?: '<root />',
				$config['libxml_options'] ?? LIBXML_NONET,
				false,
				$config['ns'] ?? '',
				$config['ns_is_prefix'] ?? false
			);
			libxml_disable_entity_loader($disableEntities);
			libxml_use_internal_errors($internalErrors);
		} catch (\Exception $e) {
			libxml_disable_entity_loader($disableEntities);
			libxml_use_internal_errors($internalErrors);
			throw new XmlParseException(
				'Unable to parse response body into XML: ' . $e->getMessage(),
				$response,
				$e,
				libxml_get_last_error() ?: null
			);
		}

		return $xml;
	}
}