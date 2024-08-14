<?php

namespace Jerapah;

use Pankki\Account;

class Jerapah
{
	public $version = "1.0";
	public $params = [];

	public function __construct($version = null)
	{
		if (!is_null($version)) {
			$this->version = $version;
		}
	}

	public function __toString(): string
	{
		return (string)$this->getQRCode();
	}

	public static function make(string $version = null): Jerapah
	{
		return new static($version);
	}

	public function setAccount(Account $account): Jerapah
	{
		$this->params["ACC"] = $account->getIBAN();

		return $this;
	}

	public function setAmount(?float $value): Jerapah
	{
		$this->params["AM"] = $value;

		return $this;
	}

	public function setCurrency(?string $value): Jerapah
	{
		$this->params["CC"] = $value;

		return $this;
	}

	public function setInMessage(?string $value): Jerapah
	{
		$this->params["MSG"] = $value;

		return $this;
	}

	public function setVS(?string $value): Jerapah
	{
		$this->params["X-VS"] = $value;

		return $this;
	}

	public function setURL(?string $value): Jerapah
	{
		$this->params["X-URL"] = $value;

		return $this;
	}

	public function getString(): string
	{
		$string = "SPD*{$this->version}*";
		foreach ($this->params as $key => $value) {
			$string .= "{$key}:{$value}*";
		}

		return $string;
	}

	public function getQRCode(?int $size = null, ?int $margin = null): \Katu\Tools\Images\QRCode
	{
		return new \Katu\Tools\Images\QRCode($this->getString(), $size, $margin);
	}
}
