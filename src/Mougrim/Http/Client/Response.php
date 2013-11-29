<?php
namespace Mougrim\Http\Client;

/**
 * @package Mougrim\Http\Client
 * @author  Mougrim <rinat@mougrim.ru>
 */
class Response {
	private $code;
	private $header;
	private $contentLength;
	private $body;
	private $errorNumber;
	private $errorMessage;

	public function __construct(Request $request, $curlResource, $response) {
		$this->code = (integer) curl_getinfo($curlResource, CURLINFO_HTTP_CODE);
		$options    = $request->getOptions();
		if($response) {
			if(array_key_exists(CURLOPT_HEADER, $options) && $options[CURLOPT_HEADER]) {
				$headerSize   = curl_getinfo($curlResource, CURLINFO_HEADER_SIZE);
				$this->header = substr($response, 0, $headerSize);
				if(preg_match('#\bcontent\-length:\s+(\d+)#i', $this->header, $matches)) {
					$this->contentLength = (integer) $matches[1];
				}
				$this->body = substr($response, $headerSize);
			} else {
				$this->body = $response;
			}
		}

		$this->errorNumber = curl_errno($curlResource);
		if($this->errorNumber) {
			$this->errorMessage = curl_error($curlResource);
		}
		curl_close($curlResource);
	}

	/**
	 * @return integer
	 */
	public function getCode() {
		return $this->code;
	}

	/**
	 * @return string
	 */
	public function getHeader() {
		return $this->header;
	}

	/**
	 * @return integer|null
	 */
	public function getContentLength() {
		return $this->contentLength;
	}

	/**
	 * @return string
	 */
	public function getBody() {
		return $this->body;
	}

	/**
	 * @return string
	 */
	public function getErrorMessage() {
		return $this->errorMessage;
	}

	/**
	 * @return integer
	 */
	public function getErrorNumber() {
		return $this->errorNumber;
	}
}
