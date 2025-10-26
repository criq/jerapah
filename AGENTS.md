# Jerapah Library - AI Agent Documentation

> **Agent Protocol: Keep This Document Updated**
> All agents are required to update this document with any new, relevant information discovered during their work on the Jerapah library. This includes changes in QR code payment functionality, new payment parameters, updated QR code generation, or newly established payment patterns.

This document provides comprehensive technical documentation for the Jerapah library, a PHP QR code payment utility used in the Hnutí DUHA IS application. The library handles QR code generation for payment processing, supporting various payment parameters and integration with banking systems.

---

## Table of Contents

1. [Library Overview](#1-library-overview)
2. [Core Architecture](#2-core-architecture)
3. [QR Code Payment System](#3-qr-code-payment-system)
4. [Payment Parameters](#4-payment-parameters)
5. [Account Integration](#5-account-integration)
6. [QR Code Generation](#6-qr-code-generation)
7. [Payment Processing](#7-payment-processing)
8. [Common Patterns](#8-common-patterns)
9. [Troubleshooting](#9-troubleshooting)
10. [Development Guidelines](#10-development-guidelines)
11. [API Reference](#11-api-reference)

---

## 1. Library Overview

### 1.1. Purpose

The Jerapah library is responsible for:

- **QR Code Payment Generation**: Creating QR codes for payment processing
- **Payment Parameter Management**: Handling various payment parameters
- **Bank Account Integration**: Integration with Pankki library for account management
- **Payment Processing**: Support for Czech payment standards
- **QR Code Rendering**: Generation of QR codes with customizable parameters

### 1.2. Key Features

- **QR Code Generation**: Create QR codes for payment processing
- **Payment Parameters**: Support for amount, currency, variable symbol, and messages
- **Account Integration**: Integration with Pankki library for IBAN handling
- **URL Support**: Optional URL parameter for payment processing
- **Customizable QR Codes**: Size and margin customization
- **String Representation**: Direct string output for QR code data

### 1.3. Core Components

- **Jerapah**: Main class for QR code payment generation
- **Payment Parameters**: Amount, currency, variable symbol, message, URL
- **Account Integration**: IBAN account number handling
- **QR Code Generation**: QR code creation with customizable parameters
- **String Formatting**: SPD format string generation

### 1.4. Dependencies

- **Katu Framework**: Base framework for utilities and QR code generation
- **Pankki Library**: Banking integration for account management
- **Curl**: HTTP requests for external services
- **PHP-IBAN**: IBAN validation and formatting

---

## 2. Core Architecture

### 2.1. Jerapah Class

**Location**: `src/Jerapah.php`

Main class for QR code payment generation:

```php
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
}
```

**Key Features**:

- **Version Management**: Support for different QR code payment versions
- **Parameter Storage**: Flexible parameter storage system
- **Fluent Interface**: Method chaining for easy configuration
- **QR Code Generation**: Direct QR code generation from parameters

### 2.2. Payment Parameter System

#### Parameter Structure

```php
// Supported payment parameters
$params = [
    "ACC" => "IBAN",           // Account number (IBAN)
    "AM" => 100.50,            // Amount
    "CC" => "CZK",             // Currency code
    "MSG" => "Payment message", // Payment message
    "X-VS" => "1234567890",    // Variable symbol
    "X-URL" => "https://...",  // Payment URL
];
```

#### Parameter Management

```php
// Set payment parameters
$jerapah = Jerapah::make()
    ->setAccount($account)
    ->setAmount(100.50)
    ->setCurrency("CZK")
    ->setInMessage("Payment for services")
    ->setVS("1234567890")
    ->setURL("https://example.com/payment");
```

---

## 3. QR Code Payment System

### 3.1. SPD Format

#### SPD String Format

The library generates QR codes in SPD (Short Payment Descriptor) format:

```
SPD*{version}*{param1}:{value1}*{param2}:{value2}*...
```

#### Example SPD String

```
SPD*1.0*ACC:CZ6508000000192000145399*AM:100.50*CC:CZK*MSG:Payment for services*X-VS:1234567890*
```

### 3.2. Version Management

#### Supported Versions

```php
// Default version
$jerapah = new Jerapah();

// Custom version
$jerapah = new Jerapah("1.0");

// Factory method
$jerapah = Jerapah::make("1.0");
```

#### Version Usage

```php
// Create with specific version
$jerapah = Jerapah::make("1.0")
    ->setAccount($account)
    ->setAmount(100.50);
```

---

## 4. Payment Parameters

### 4.1. Account Parameter (ACC)

#### Account Integration

```php
public function setAccount(Account $account): Jerapah
{
    $this->params["ACC"] = $account->getIBAN();
    return $this;
}
```

#### Usage

```php
// Set account using Pankki Account
$account = new \Pankki\Account("CZ6508000000192000145399");
$jerapah = Jerapah::make()->setAccount($account);
```

### 4.2. Amount Parameter (AM)

#### Amount Setting

```php
public function setAmount(?float $value): Jerapah
{
    $this->params["AM"] = $value;
    return $this;
}
```

#### Usage

```php
// Set payment amount
$jerapah = Jerapah::make()->setAmount(100.50);
```

### 4.3. Currency Parameter (CC)

#### Currency Setting

```php
public function setCurrency(?string $value): Jerapah
{
    $this->params["CC"] = $value;
    return $this;
}
```

#### Usage

```php
// Set currency
$jerapah = Jerapah::make()->setCurrency("CZK");
```

### 4.4. Message Parameter (MSG)

#### Message Setting

```php
public function setInMessage(?string $value): Jerapah
{
    $this->params["MSG"] = $value;
    return $this;
}
```

#### Usage

```php
// Set payment message
$jerapah = Jerapah::make()->setInMessage("Payment for services");
```

### 4.5. Variable Symbol (X-VS)

#### Variable Symbol Setting

```php
public function setVS(?string $value): Jerapah
{
    $this->params["X-VS"] = $value;
    return $this;
}
```

#### Usage

```php
// Set variable symbol
$jerapah = Jerapah::make()->setVS("1234567890");
```

### 4.6. URL Parameter (X-URL)

#### URL Setting

```php
public function setURL(?string $value): Jerapah
{
    $this->params["X-URL"] = $value;
    return $this;
}
```

#### Usage

```php
// Set payment URL
$jerapah = Jerapah::make()->setURL("https://example.com/payment");
```

---

## 5. Account Integration

### 5.1. Pankki Integration

#### Account Usage

```php
// Create account using Pankki library
$account = new \Pankki\Account("CZ6508000000192000145399");

// Use account in Jerapah
$jerapah = Jerapah::make()->setAccount($account);
```

#### IBAN Handling

```php
// Get IBAN from account
$iban = $account->getIBAN();

// Set IBAN in Jerapah
$jerapah->setAccount($account);
```

### 5.2. Account Validation

#### IBAN Validation

```php
// Validate IBAN using Pankki
$account = new \Pankki\Account("CZ6508000000192000145399");
if ($account->isValid()) {
    $jerapah = Jerapah::make()->setAccount($account);
}
```

---

## 6. QR Code Generation

### 6.1. QR Code Creation

#### Basic QR Code Generation

```php
public function getQRCode(?int $size = null, ?int $margin = null): \Katu\Tools\Images\QRCode
{
    return new \Katu\Tools\Images\QRCode($this->getString(), $size, $margin);
}
```

#### Usage

```php
// Generate QR code
$jerapah = Jerapah::make()
    ->setAccount($account)
    ->setAmount(100.50)
    ->setCurrency("CZK");

$qrCode = $jerapah->getQRCode();
```

### 6.2. QR Code Customization

#### Size and Margin

```php
// Custom size and margin
$qrCode = $jerapah->getQRCode(300, 10);

// Default parameters
$qrCode = $jerapah->getQRCode();
```

#### QR Code Properties

```php
// Get QR code properties
$size = $qrCode->getSize();
$margin = $qrCode->getMargin();
$data = $qrCode->getData();
```

### 6.3. String Representation

#### String Output

```php
public function getString(): string
{
    $string = "SPD*{$this->version}*";
    foreach ($this->params as $key => $value) {
        $string .= "{$key}:{$value}*";
    }
    return $string;
}
```

#### Usage

```php
// Get SPD string
$spdString = $jerapah->getString();

// Direct string conversion
$spdString = (string)$jerapah;
```

---

## 7. Payment Processing

### 7.1. Complete Payment Setup

#### Full Payment Configuration

```php
// Complete payment setup
$account = new \Pankki\Account("CZ6508000000192000145399");
$jerapah = Jerapah::make("1.0")
    ->setAccount($account)
    ->setAmount(100.50)
    ->setCurrency("CZK")
    ->setInMessage("Payment for services")
    ->setVS("1234567890")
    ->setURL("https://example.com/payment");

// Generate QR code
$qrCode = $jerapah->getQRCode(300, 10);
```

### 7.2. Payment Validation

#### Parameter Validation

```php
// Validate payment parameters
$jerapah = Jerapah::make()
    ->setAccount($account)
    ->setAmount(100.50)
    ->setCurrency("CZK");

// Check if all required parameters are set
if ($jerapah->params["ACC"] && $jerapah->params["AM"]) {
    $qrCode = $jerapah->getQRCode();
}
```

### 7.3. Payment Processing Workflow

#### Step-by-Step Process

```php
// 1. Create account
$account = new \Pankki\Account("CZ6508000000192000145399");

// 2. Create Jerapah instance
$jerapah = Jerapah::make("1.0");

// 3. Set payment parameters
$jerapah->setAccount($account)
        ->setAmount(100.50)
        ->setCurrency("CZK")
        ->setInMessage("Payment for services")
        ->setVS("1234567890");

// 4. Generate QR code
$qrCode = $jerapah->getQRCode(300, 10);

// 5. Get SPD string
$spdString = $jerapah->getString();
```

---

## 8. Common Patterns

### 8.1. Basic Payment QR Code

```php
// Basic payment QR code
$account = new \Pankki\Account("CZ6508000000192000145399");
$jerapah = Jerapah::make()
    ->setAccount($account)
    ->setAmount(100.50)
    ->setCurrency("CZK");

$qrCode = $jerapah->getQRCode();
```

### 8.2. Payment with Variable Symbol

```php
// Payment with variable symbol
$jerapah = Jerapah::make()
    ->setAccount($account)
    ->setAmount(100.50)
    ->setCurrency("CZK")
    ->setVS("1234567890");

$qrCode = $jerapah->getQRCode();
```

### 8.3. Payment with Message

```php
// Payment with message
$jerapah = Jerapah::make()
    ->setAccount($account)
    ->setAmount(100.50)
    ->setCurrency("CZK")
    ->setInMessage("Payment for services");

$qrCode = $jerapah->getQRCode();
```

### 8.4. Payment with URL

```php
// Payment with URL
$jerapah = Jerapah::make()
    ->setAccount($account)
    ->setAmount(100.50)
    ->setCurrency("CZK")
    ->setURL("https://example.com/payment");

$qrCode = $jerapah->getQRCode();
```

### 8.5. Complete Payment Configuration

```php
// Complete payment configuration
$jerapah = Jerapah::make("1.0")
    ->setAccount($account)
    ->setAmount(100.50)
    ->setCurrency("CZK")
    ->setInMessage("Payment for services")
    ->setVS("1234567890")
    ->setURL("https://example.com/payment");

$qrCode = $jerapah->getQRCode(300, 10);
$spdString = $jerapah->getString();
```

---

## 9. Troubleshooting

### 9.1. Common Issues

#### QR Code Generation Failures

- Check account IBAN validity
- Verify payment parameters
- Ensure proper parameter formatting
- Check QR code size and margin values

#### Parameter Issues

- Validate account using Pankki library
- Check amount format (float)
- Verify currency code format
- Ensure variable symbol format

#### Integration Issues

- Check Pankki library integration
- Verify account creation
- Ensure proper IBAN formatting
- Check dependency installation

### 9.2. Debugging

#### Parameter Debugging

```php
// Check payment parameters
$jerapah = Jerapah::make()
    ->setAccount($account)
    ->setAmount(100.50);

echo "Parameters: " . json_encode($jerapah->params);
echo "SPD String: " . $jerapah->getString();
```

#### QR Code Debugging

```php
// Check QR code generation
$qrCode = $jerapah->getQRCode();
echo "QR Code Size: " . $qrCode->getSize();
echo "QR Code Margin: " . $qrCode->getMargin();
echo "QR Code Data: " . $qrCode->getData();
```

#### Account Debugging

```php
// Check account information
$account = new \Pankki\Account("CZ6508000000192000145399");
echo "IBAN: " . $account->getIBAN();
echo "Valid: " . ($account->isValid() ? "Yes" : "No");
```

---

## 10. Development Guidelines

### 10.1. Library Development

**Requirements**:

- Implement fluent interface for method chaining
- Support multiple payment parameters
- Integrate with Pankki library for account management
- Provide QR code generation capabilities

### 10.2. Payment Parameter Handling

**Best Practices**:

- Validate all payment parameters
- Use proper data types for parameters
- Handle null values appropriately
- Implement proper parameter formatting

### 10.3. QR Code Generation

**Guidelines**:

- Use appropriate QR code sizes
- Implement proper margin settings
- Handle QR code generation errors
- Provide fallback options

### 10.4. Integration Guidelines

**Integration Best Practices**:

- Use Pankki library for account management
- Implement proper error handling
- Provide clear parameter validation
- Support multiple payment scenarios

---

## 11. API Reference

### 11.1. Jerapah Class

```php
class Jerapah
{
    // Properties
    public $version = "1.0";
    public $params = [];

    // Constructor
    public function __construct($version = null);

    // Factory method
    public static function make(string $version = null): Jerapah;

    // Parameter setters
    public function setAccount(Account $account): Jerapah;
    public function setAmount(?float $value): Jerapah;
    public function setCurrency(?string $value): Jerapah;
    public function setInMessage(?string $value): Jerapah;
    public function setVS(?string $value): Jerapah;
    public function setURL(?string $value): Jerapah;

    // Output methods
    public function getString(): string;
    public function getQRCode(?int $size = null, ?int $margin = null): \Katu\Tools\Images\QRCode;

    // String conversion
    public function __toString(): string;
}
```

### 11.2. Usage Examples

#### Basic Usage

```php
// Create Jerapah instance
$jerapah = Jerapah::make();

// Set payment parameters
$jerapah->setAccount($account)
        ->setAmount(100.50)
        ->setCurrency("CZK");

// Generate QR code
$qrCode = $jerapah->getQRCode();
```

#### Advanced Usage

```php
// Complete payment setup
$jerapah = Jerapah::make("1.0")
    ->setAccount($account)
    ->setAmount(100.50)
    ->setCurrency("CZK")
    ->setInMessage("Payment for services")
    ->setVS("1234567890")
    ->setURL("https://example.com/payment");

// Generate custom QR code
$qrCode = $jerapah->getQRCode(300, 10);

// Get SPD string
$spdString = $jerapah->getString();
```

#### String Conversion

```php
// Direct string conversion
$jerapah = Jerapah::make()
    ->setAccount($account)
    ->setAmount(100.50);

$spdString = (string)$jerapah;
```

### 11.3. Parameter Reference

#### Supported Parameters

| Parameter | Type   | Description     | Example                       |
| --------- | ------ | --------------- | ----------------------------- |
| `ACC`     | string | Account IBAN    | `CZ6508000000192000145399`    |
| `AM`      | float  | Payment amount  | `100.50`                      |
| `CC`      | string | Currency code   | `CZK`                         |
| `MSG`     | string | Payment message | `Payment for services`        |
| `X-VS`    | string | Variable symbol | `1234567890`                  |
| `X-URL`   | string | Payment URL     | `https://example.com/payment` |

#### SPD String Format

```
SPD*{version}*{param1}:{value1}*{param2}:{value2}*...
```

#### Example SPD String

```
SPD*1.0*ACC:CZ6508000000192000145399*AM:100.50*CC:CZK*MSG:Payment for services*X-VS:1234567890*
```

This comprehensive documentation covers the Jerapah library, providing AI agents with detailed information about QR code payment generation, parameter management, account integration, and payment processing workflows.
