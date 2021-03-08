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

	public function __toString()
	{
		return (string)$this->getImageUrl(400);
	}

	public static function make($version = null)
	{
		return new static($version);
	}

	public function setAccount($value)
	{
		$this->params['ACC'] = preg_replace('/\s/', null, $value);

		return $this;
	}

	public function setAmount($value)
	{
		$this->params['AM'] = $value;

		return $this;
	}

	public function setCurrency($value)
	{
		$this->params['CC'] = $value;

		return $this;
	}

	public function setInMessage($value)
	{
		$this->params['MSG'] = $value;

		return $this;
	}

	public function setVS($value)
	{
		$this->params['X-VS'] = $value;

		return $this;
	}

	public function setURL($value)
	{
		$this->params['X-URL'] = $value;

		return $this;
	}

	public function getString()
	{
		$string = 'SPD*' . $this->version . '*';

		foreach ($this->params as $key => $value) {
			$string .= $key . ':' . $value . '*';
		}

		return $string;
	}

	public function getImageUrl($size = 400, $provider = 'google')
	{
		switch ($provider) {
			case 'google':
				return \Katu\Types\TUrl::make('https://chart.googleapis.com/chart', [
					'cht' => 'qr',
					'chs' => $size,
					'chl' => $this->getString(),
				]);

				break;
		}

		throw new \Exception("Invalid provider.");
	}

	public function getEncoded()
	{
		try {
			$curl = new \Curl\Curl;
			$res = $curl->get($this->getImageUrl());
			$info = $curl->getInfo();

			return 'data:' . $info['content_type'] . ';base64,' . base64_encode($res);
		} catch (\Exception $e) {
			return '';
		}
	}
}
