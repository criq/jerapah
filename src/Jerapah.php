<?php

namespace Jerapah;

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
		return $this->getImageUrl(400);
	}

	public static function make(string $version = null): Jerapah
	{
		return new static($version);
	}

	public function setAccount(?string $value): Jerapah
	{
		$this->params["ACC"] = preg_replace("/\s/", "", $value);

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

	public function getImageUrl(int $size): ?string
	{
		try {
			return "https://api.qrserver.com/v1/create-qr-code/?" . http_build_query([
				"size" => "{$size}x{$size}",
				"data" => $this->getString(),
			]);
		} catch (\Throwable $e) {
			return null;
		}
	}

	public static function generateIBAN(string $countryCode, string $bankCode, ?string $accountPrefix, string $accountNumber): string
	{
		$iban = new \PHP_IBAN\IBAN(implode([
			$countryCode ?: "CZ",
			"00",
			$bankCode,
			str_pad($accountPrefix, 6, 0, \STR_PAD_LEFT),
			str_pad($accountNumber, 10, 0, \STR_PAD_LEFT),
		]));
		$iban->setChecksum();

		return $iban->iban;
	}
}
