<?php

namespace Adshares\Ads;

use Adshares\Ads\Command\AbstractTransactionCommand;
use Adshares\Ads\Command\BroadcastCommand;
use Adshares\Ads\Command\GetAccountCommand;
use Adshares\Ads\Command\GetAccountsCommand;
use Adshares\Ads\Command\GetBlockCommand;
use Adshares\Ads\Command\GetBlocksCommand;
use Adshares\Ads\Command\GetBroadcastCommand;
use Adshares\Ads\Command\GetMeCommand;
use Adshares\Ads\Command\GetPackageCommand;
use Adshares\Ads\Command\GetPackageListCommand;
use Adshares\Ads\Command\SendManyCommand;
use Adshares\Ads\Command\SendOneCommand;
use Adshares\Ads\Driver\DriverInterface;
use Adshares\Ads\Entity\EntityFactory;
use Adshares\Ads\Exception\CommandException;
use Adshares\Ads\Response\BroadcastResponse;
use Adshares\Ads\Response\GetAccountResponse;
use Adshares\Ads\Response\GetAccountsResponse;
use Adshares\Ads\Response\GetBlockResponse;
use Adshares\Ads\Response\GetBlocksResponse;
use Adshares\Ads\Response\GetBroadcastResponse;
use Adshares\Ads\Response\GetMeResponse;
use Adshares\Ads\Response\GetPackageListResponse;
use Adshares\Ads\Response\GetPackageResponse;
use Adshares\Ads\Response\SendManyResponse;
use Adshares\Ads\Response\SendOneResponse;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Wrapper class used to interact with ADS wallet client.
 */
class AdsClient implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var DriverInterface
     */
    protected $driver;

    /**
     * AdsClient constructor.
     *
     * @param DriverInterface $driver
     * @param LoggerInterface|null $logger
     */
    public function __construct(DriverInterface $driver, LoggerInterface $logger = null)
    {
        $this->driver = $driver;
        if (null === $logger) {
            $logger = new NullLogger();
        }
        $this->logger = $logger;
    }

    /**
     * @param array $map
     */
    public static function setEntityMap(array $map): void
    {
        EntityFactory::setEntityMap($map);
    }

    /**
     * @param AbstractTransactionCommand $transaction
     *
     * @throws CommandException
     */
    private function prepareTransaction(AbstractTransactionCommand $transaction)
    {
        $getMeResponse = $this->getMe();
        $transaction->setLastHash($getMeResponse->getAccount()->getHash());
        $transaction->setLastMessageId($getMeResponse->getAccount()->getMsid());
    }

    /**
     * @param string $message hexadecimal string with even number of characters
     *
     * @return BroadcastResponse
     *
     * @throws CommandException
     */
    public function broadcast($message): BroadcastResponse
    {
        $command = new BroadcastCommand($message);
        $this->prepareTransaction($command);
        $response = $this->driver->executeCommand($command);

        return new BroadcastResponse($response->getRawData());
    }

    /**
     * @param string $address
     *
     * @return GetAccountResponse
     *
     * @throws CommandException
     */
    public function getAccount(string $address): GetAccountResponse
    {
        $command = new GetAccountCommand($address);
        $response = $this->driver->executeCommand($command);

        return new GetAccountResponse($response->getRawData());
    }

    /**
     * @param int         $node
     * @param null|string $block
     *
     * @return GetAccountsResponse
     *
     * @throws CommandException
     */
    public function getAccounts(int $node, string $block = null): GetAccountsResponse
    {
        $command = new GetAccountsCommand($node, $block);
        $response = $this->driver->executeCommand($command);

        return new GetAccountsResponse($response->getRawData());
    }

    /**
     * @param null|string $block [optional] block time in Unix Epoch seconds as hexadecimal String
     *
     * @return GetBlockResponse
     *
     * @throws CommandException
     */
    public function getBlock(string $block = null): GetBlockResponse
    {
        $command = new GetBlockCommand($block);
        $response = $this->driver->executeCommand($command);

        return new GetBlockResponse($response->getRawData());
    }

    /**
     * @param null|string $from [optional] block time in Unix Epoch seconds as hexadecimal String
     * @param null|string $to   [optional] block time in Unix Epoch seconds as hexadecimal String
     *
     * @return GetBlocksResponse
     *
     * @throws CommandException
     */
    public function getBlocks(string $from = null, string $to = null): GetBlocksResponse
    {
        $command = new GetBlocksCommand($from, $to);
        $response = $this->driver->executeCommand($command);

        return new GetBlocksResponse($response->getRawData());
    }

    /**
     * @param null|string $from block time in Unix Epoch seconds as hexadecimal String, 0 for last block
     *
     * @return GetBroadcastResponse
     *
     * @throws CommandException
     */
    public function getBroadcast(string $from = null): GetBroadcastResponse
    {
        $command = new GetBroadcastCommand($from);
        $response = $this->driver->executeCommand($command);

        return new GetBroadcastResponse($response->getRawData());
    }

    /**
     * @return GetMeResponse
     *
     * @throws CommandException
     */
    public function getMe(): GetMeResponse
    {
        $command = new GetMeCommand();
        $response = $this->driver->executeCommand($command);

        return new GetMeResponse($response->getRawData());
    }

    /**
     * @param int $node
     * @param int $nodeMsid
     * @param null|string $block
     *
     * @return GetPackageResponse
     *
     * @throws CommandException
     */
    public function getPackage(int $node, int $nodeMsid, string $block = null): GetPackageResponse
    {
        $command = new GetPackageCommand($node, $nodeMsid, $block);
        $response = $this->driver->executeCommand($command);

        return new GetPackageResponse($response->getRawData());
    }

    /**
     * @param null|string $block
     *
     * @return GetPackageListResponse
     *
     * @throws CommandException
     */
    public function getPackageList(string $block = null): GetPackageListResponse
    {
        $command = new GetPackageListCommand($block);
        $response = $this->driver->executeCommand($command);

        return new GetPackageListResponse($response->getRawData());
    }

    /**
     * @param array $wires array of wires. Each entry is pair: account address => amount in clicks.
     *                     Example: ['0001-00000000-XXXX'=>200,'0001-00000001-XXXX'=>10]
     *
     * @return SendManyResponse
     *
     * @throws CommandException
     */
    public function sendMany(array $wires): SendManyResponse
    {
        $command = new SendManyCommand($wires);
        $this->prepareTransaction($command);
        $response = $this->driver->executeCommand($command);

        return new SendManyResponse($response->getRawData());
    }

    /**
     * @param string      $address address to which funds will be transferred
     * @param int         $amount  transfer amount in clicks
     * @param null|string $message optional message, 32 bytes hexadecimal string without leading 0x
     *
     * @return SendOneResponse
     *
     * @throws CommandException
     */
    public function sendOne(string $address, int $amount, $message = null): SendOneResponse
    {
        $command = new SendOneCommand($address, $amount, $message);
        $this->prepareTransaction($command);
        $response = $this->driver->executeCommand($command);

        return new SendOneResponse($response->getRawData());
    }

    //    TODO: (Yodahack) : disscuss placement of this methods (currently copied to Adshares\Adserver\Http\Utils)
    //    public static function normalizeAddress($address)
    //    {
    //        $x = preg_replace('/[^0-9A-FX]+/', '', strtoupper($address));
    //        if (strlen($x) != 16) {
    //            throw new \RuntimeException("Invalid adshares address");
    //        }
    //        return sprintf("%s-%s-%s", substr($x, 0, 4), substr($x, 4, 8), substr($x, 12, 4));
    //    }
    //
    //    public static function normalizeTxid($txid)
    //    {
    //        $x = preg_replace('/[^0-9A-F]+/', '', strtoupper($txid));
    //        if (strlen($x) != 16) {
    //            throw new \RuntimeException("Invalid adshares address");
    //        }
    //        return sprintf("%s:%s:%s", substr($x, 0, 4), substr($x, 4, 8), substr($x, 12, 4));
    //    }
}
