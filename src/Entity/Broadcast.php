<?php

namespace Adshares\Ads\Entity;

use Adshares\Ads\Util\AdsConverter;

/**
 * Class Broadcast
 *
 * @package Adshares\Ads\Entity
 */
class Broadcast extends AbstractEntity
{
    /**
     * Sender last message number
     *
     * @var int
     */
    protected $accountMsid;

    /**
     * Sender address
     *
     * @var string
     */
    protected $address;

    /**
     * Block time of broadcast transaction
     *
     * @var \DateTime
     */
    protected $blockTime;

    /**
     * Transaction data as hexadecimal string
     *
     * @var string
     */
    protected $data;

    /**
     * Fee
     *
     * @var int
     */
    protected $fee;

    /**
     * Id
     *
     * @var string
     */
    protected $id;

    /**
     * Sender input hash
     *
     * @var string
     */
    protected $inputHash;

    /**
     * Message
     *
     * @var string
     */
    protected $message;

    /**
     * Sender node ordinal number
     *
     * @var int
     */
    protected $node;

    /**
     * Position of the broadcast transaction in node message
     *
     * @var int
     */
    protected $nodeMpos;

    /**
     * Number of last node message
     *
     * @var int
     */
    protected $nodeMsid;

    /**
     * Sender public key
     *
     * @var string
     */
    protected $publicKey;

    /**
     * Signature
     *
     * @var string
     */
    protected $signature;

    /**
     *
     * @var \DateTime
     */
    protected $time;

    /**
     * true if verification passed, false if verification failed
     *
     * @var boolean
     */
    protected $verify;

    /**
     * @return int Sender last message number
     */
    public function getAccountMsid(): int
    {
        return $this->accountMsid;
    }

    /**
     * @return string Sender address
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return \DateTime Block time of broadcast transaction
     */
    public function getBlockTime(): \DateTime
    {
        return $this->blockTime;
    }

    /**
     * Transaction data as hexadecimal string
     *
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * @return int Fee
     */
    public function getFee(): int
    {
        return $this->fee;
    }

    /**
     * @return string Id
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string Sender input hash
     */
    public function getInputHash(): string
    {
        return $this->inputHash;
    }

    /**
     * @return string Message
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return int Sender node ordinal number
     */
    public function getNode(): int
    {
        return $this->node;
    }

    /**
     * @return string Sender node id
     */
    public function getNodeId(): string
    {
        return sprintf('%04X', $this->node);
    }

    /**
     * @return int Position of the broadcast transaction in node message
     */
    public function getNodeMpos(): int
    {
        return $this->nodeMpos;
    }

    /**
     * @return int Number of last node message
     */
    public function getNodeMsid(): int
    {
        return $this->nodeMsid;
    }

    /**
     * @return string Sender public key
     */
    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    /**
     * @return string Signature
     */
    public function getSignature(): string
    {
        return $this->signature;
    }

    /**
     * @return \DateTime
     */
    public function getTime(): \DateTime
    {
        return $this->time;
    }

    /**
     * @return bool true if verification passed, false if verification failed
     */
    public function isVerificationPassed(): bool
    {
        return $this->verify;
    }

    /**
     * @param string $name
     * @param array|mixed $value
     * @param \ReflectionClass|null $refClass
     * @return int|mixed
     */
    protected static function castProperty(string $name, $value, \ReflectionClass $refClass = null)
    {
        if ('fee' === $name) {
            return AdsConverter::adsToClicks($value);
        }

        if ('verify' === $name) {
            return 'passed' === $value;
        }

        return parent::castProperty($name, $value, $refClass);
    }
}
