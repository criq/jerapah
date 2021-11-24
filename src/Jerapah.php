<?php

namespace Jerapah;

class Jerapah
{
	public $version = '1.0';
	public $params = [];

	public function __construct($version = null)
	{
		if (!is_null($version)) {
			$this->version = $version;
		}
	}

	public function __toString(): string
	{
		return $this->getImageUrl(400);
	}

	public static function make(string $version = null): Jerapah
	{
		return new static($version);
	}

	public function setAccount(string $value): Jerapah
	{
		$this->params['ACC'] = preg_replace('#\s#', '', $value);

		return $this;
	}

	public function setAmount(string $value): Jerapah
	{
		$this->params['AM'] = $value;

		return $this;
	}

	public function setCurrency(string $value): Jerapah
	{
		$this->params['CC'] = $value;

		return $this;
	}

	public function setInMessage(string $value): Jerapah
	{
		$this->params['MSG'] = $value;

		return $this;
	}

	public function setVS(string $value): Jerapah
	{
		$this->params['X-VS'] = $value;

		return $this;
	}

	public function setURL(string $value): Jerapah
	{
		$this->params['X-URL'] = $value;

		return $this;
	}

	public function getString(): string
	{
		$string = 'SPD*' . $this->version . '*';

		foreach ($this->params as $key => $value) {
			$string .= $key . ':' . $value . '*';
		}

		return $string;
	}

	public function getImageUrl(int $size, string $provider = 'google'): string
	{
		switch ($provider) {
			case 'google':
				$image = new \gchart\gQRCode($size, $size);
				$image->setQRCode($this->getString());

				$url = $image->getUrl();
				$url = preg_replace('#&amp;#', '&', $url);
				$url = preg_replace('/^http:/', 'https:', $url);

				return $url;

				break;
		}

		throw new \Exception("Invalid provider.");
	}
}
