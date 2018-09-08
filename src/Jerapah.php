<?php

namespace Jerapah;

class Jerapah {

	public $version = '1.0';
	public $params = [];

	public function __construct($version = null) {
		if (!is_null($version)) {
			$this->version = $version;
		}
	}

	public function __toString() {
		return $this->getImageUrl(400);
	}

	static function make($version = null) {
		return new static($version);
	}

	public function setAccount($value) {
		$this->params['ACC'] = preg_replace('#\s#', null, $value);

		return $this;
	}

	public function setAmount($value) {
		$this->params['AM'] = $value;

		return $this;
	}

	public function setCurrency($value) {
		$this->params['CC'] = $value;

		return $this;
	}

	public function setInMessage($value) {
		$this->params['MSG'] = $value;

		return $this;
	}

	public function setVS($value) {
		$this->params['X-VS'] = $value;

		return $this;
	}

	public function setURL($value) {
		$this->params['X-URL'] = $value;

		return $this;
	}

	public function getString() {
		$string = 'SPD*' . $this->version . '*';

		foreach ($this->params as $key => $value) {
			$string .= $key . ':' . $value . '*';
		}

		return $string;
	}

	public function getImageUrl($size, $provider = 'google') {
		switch ($provider) {
			case 'google' :

				$image = new \gchart\gQRCode($size, $size);
				$image->setQRCode($this->getString());

				$url = $image->getUrl();
				$url = preg_replace('#&amp;#', '&', $url);
				$url = preg_replace('/^http:/', 'https:', $url);

				return $url;

			break;
		}

		throw new Exception("Invalid provider.");
	}

}
