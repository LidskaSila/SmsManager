<?php declare(strict_types = 1);

namespace LidskaSila\SmsManager;

use Psr\Http\Message\ResponseInterface;

class Parser
{

	public static function parseXmlResponseBody(ResponseInterface $response, array $config = []): \SimpleXMLElement
	{
		$disableEntities = libxml_disable_entity_loader();
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
				$e
			);
		}

		return $xml;
	}
}
