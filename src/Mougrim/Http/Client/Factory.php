<?php
namespace Mougrim\Http\Client;

/**
 * @author Mougrim <rinat@mougrim.ru>
 */
class Factory {
	const CURRENT_VERSION = 'dev';

	public function createRequest($url) {
		return new Request($url);
	}
}
