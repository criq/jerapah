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

	// public function getImage()
	// {
	// 	$writer = new \PngWrite

	// 	// Create QR code
	// 	$qrCode = QrCode::create('Data')
	// 			->setEncoding(new Encoding('UTF-8'))
	// 			->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
	// 			->setSize(300)
	// 			->setMargin(10)
	// 			->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
	// 			->setForegroundColor(new Color(0, 0, 0))
	// 			->setBackgroundColor(new Color(255, 255, 255));
	// }
}
