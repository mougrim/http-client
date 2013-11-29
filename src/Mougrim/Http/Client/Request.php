<?php
namespace Mougrim\Http\Client;

/**
 * @author Mougrim <rinat@mougrim.ru>
 */
class Request {
	private $url;
	private $options = array(
		CURLOPT_USERAGENT      => 'Mougrim http client',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_HEADER         => true,
		CURLOPT_CONNECTTIMEOUT => 1,
		CURLOPT_TIMEOUT        => 2,
	);
	private $time;

	public function __construct($url) {
		$this->url = $url;
		$this->options[CURLOPT_USERAGENT] .= ' ' . Factory::CURRENT_VERSION;
	}

	public function getOptions() {
		return $this->options;
	}

	/**
	 * @return float|null
	 */
	public function getTime() {
		return $this->time;
	}

	public function setOption($option, $value) {
		if($value !== null) {
			$this->options[$option] = $value;
		} else {
			unset($this->options[$option]);
		}
	}

	public function doRequest() {
		$begin              = microtime(true);
		$curlResource = curl_init($this->url);
		curl_setopt_array($curlResource, $this->options);
		$response   = new Response($this, $curlResource, curl_exec($curlResource));
		$this->time = microtime(true) - $begin;

		return $response;
	}
}
