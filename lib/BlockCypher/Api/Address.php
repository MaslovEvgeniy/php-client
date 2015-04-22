<?php

namespace BlockCypher\Api;

use BlockCypher\Common\BlockCypherResourceModel;
use BlockCypher\Rest\ApiContext;
use BlockCypher\Transport\BlockCypherRestCall;
use BlockCypher\Validation\ArgumentValidator;
use BlockCypher\Validation\ArrayValidator;

/**
 * Class Address
 *
 * A resource representing a block.
 *
 * @package BlockCypher\Api
 *
 * @property string address
 * @property int total_received
 * @property int total_sent
 * @property int balance
 * @property int unconfirmed_balance
 * @property int final_balance
 * @property int n_tx
 * @property int unconfirmed_n_tx
 * @property int final_n_tx
 * @property \BlockCypher\Api\Txref[] txrefs
 * @property \BlockCypher\Api\Links tx_url
 */
class Address extends BlockCypherResourceModel
{
    // TODO: Code Review. Replace these fields address, total_received ... final_n_tx by AddressBalance object

    /**
     * Create a new address.
     *
     * @param ApiContext $apiContext is the APIContext for this call. It can be used to pass dynamic configuration and credentials.
     * @param BlockCypherRestCall $restCall is the Rest Call Service that is used to make rest calls
     * @return AddressCreateResponse
     */
    public static function create($apiContext = null, $restCall = null)
    {
        $payLoad = "";

        //Initialize the context if not provided explicitly
        $apiContext = $apiContext ? $apiContext : new ApiContext(self::$credential);
        $chainUrlPrefix = $apiContext->getBaseChainUrl();

        $json = self::executeCall(
            "$chainUrlPrefix/addrs",
            "POST",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        $ret = new AddressCreateResponse();
        $ret->fromJson($json);
        return $ret;
    }

    /**
     * Obtain the Address resource for the given identifier.
     *
     * @param string $address
     * @param array $params Parameters. Options: unspentOnly, and before
     * @param ApiContext $apiContext is the APIContext for this call. It can be used to pass dynamic configuration and credentials.
     * @param BlockCypherRestCall $restCall is the Rest Call Service that is used to make rest calls
     * @return Address
     */
    public static function get($address, $params = array(), $apiContext = null, $restCall = null)
    {
        ArgumentValidator::validate($address, 'address');
        ArgumentValidator::validate($params, 'params');

        $allowedParams = array(
            'unspentOnly' => 1,
            'before' => 1,
        );
        $payLoad = "";

        //Initialize the context if not provided explicitly
        $apiContext = $apiContext ? $apiContext : new ApiContext(self::$credential);
        $chainUrlPrefix = $apiContext->getBaseChainUrl();

        $json = self::executeCall(
            "$chainUrlPrefix/addrs/$address?" . http_build_query(array_intersect_key($params, $allowedParams)),
            "GET",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        $ret = new Address();
        $ret->fromJson($json);
        return $ret;
    }

    /**
     * Obtain the FullAddress resource for the given address.
     *
     * @param string $address
     * @param array $params Parameters. Options: unspentOnly, and before
     * @param ApiContext $apiContext is the APIContext for this call. It can be used to pass dynamic configuration and credentials.
     * @param BlockCypherRestCall $restCall is the Rest Call Service that is used to make rest calls
     * @return FullAddress
     */
    public static function getFullAddress($address, $params = array(), $apiContext = null, $restCall = null)
    {
        return FullAddress::get($address, $params, $apiContext, $restCall);
    }

    /**
     * Obtain the AddressBalance resource for the given address.
     *
     * @param string $address
     * @param ApiContext $apiContext is the APIContext for this call. It can be used to pass dynamic configuration and credentials.
     * @param BlockCypherRestCall $restCall is the Rest Call Service that is used to make rest calls
     * @return AddressBalance
     */
    public static function getOnlyBalance($address, $apiContext = null, $restCall = null)
    {
        return AddressBalance::get($address, $apiContext, $restCall);
    }

    /**
     * Obtain multiple Addresses resources for the given identifiers.
     *
     * @param string[] $array
     * @param array $params Parameters. Options: unspentOnly, and before
     * @param ApiContext $apiContext is the APIContext for this call. It can be used to pass dynamic configuration and credentials.
     * @param BlockCypherRestCall $restCall is the Rest Call Service that is used to make rest calls
     * @return Address[]
     */
    public static function getMultiple($array, $params = array(), $apiContext = null, $restCall = null)
    {
        ArrayValidator::validate($array, 'array');
        foreach ($array as $address) {
            ArgumentValidator::validate($address, 'address');
        }
        ArgumentValidator::validate($params, 'params');

        $addressList = implode(";", $array);
        $allowedParams = array(
            'unspentOnly' => 1,
            'before' => 1,
        );
        $payLoad = "";

        //Initialize the context if not provided explicitly
        $apiContext = $apiContext ? $apiContext : new ApiContext(self::$credential);
        $chainUrlPrefix = $apiContext->getBaseChainUrl();

        $json = self::executeCall(
            "$chainUrlPrefix/addrs/$addressList?" . http_build_query(array_intersect_key($params, $allowedParams)),
            "GET",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        return Address::getList($json);
    }

    /**
     * The requested address.
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * The requested address.
     *
     * @param string $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * Total amount, in satoshis, received by this address.
     *
     * @return int
     */
    public function getTotalReceived()
    {
        return $this->total_received;
    }

    /**
     * Total amount, in satoshis, received by this address.
     *
     * @param int $total_received
     * @return $this
     */
    public function setTotalReceived($total_received)
    {
        $this->total_received = $total_received;
        return $this;
    }

    /**
     * Total amount, in satoshis, sent by this address.
     *
     * @return int
     */
    public function getTotalSent()
    {
        return $this->total_sent;
    }

    /**
     * Total amount, in satoshis, sent by this address.
     *
     * @param int $total_sent
     * @return $this
     */
    public function setTotalSent($total_sent)
    {
        $this->total_sent = $total_sent;
        return $this;
    }

    /**
     * Balance on the specified address, in satoshi. This is the difference between outputs and inputs on this address,
     * for transactions that have been included into a block (confirmations > 0)
     *
     * @return int
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * Balance on the specified address, in satoshi. This is the difference between outputs and inputs on this address,
     * for transactions that have been included into a block (confirmations > 0)
     *
     * @param int $balance
     * @return $this
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
        return $this;
    }

    /**
     * Balance of unconfirmed transactions for this address, in satoshi. Can be negative
     * (if unconfirmed transactions are just spending.). Only unconfirmed transactions (haven't made it into a block)
     * are included.
     *
     * @return int
     */
    public function getUnconfirmedBalance()
    {
        return $this->unconfirmed_balance;
    }

    /**
     * Balance of unconfirmed transactions for this address, in satoshi. Can be negative
     * (if unconfirmed transactions are just spending.). Only unconfirmed transactions (haven't made it into a block)
     * are included.
     *
     * @param int $unconfirmed_balance
     * @return $this
     */
    public function setUnconfirmedBalance($unconfirmed_balance)
    {
        $this->unconfirmed_balance = $unconfirmed_balance;
        return $this;
    }

    /**
     * Balance including confirmed and unconfirmed transactions for this address, in satoshi.
     *
     * @return int
     */
    public function getFinalBalance()
    {
        return $this->final_balance;
    }

    /**
     * Balance including confirmed and unconfirmed transactions for this address, in satoshi.
     *
     * @param int $final_balance
     * @return $this
     */
    public function setFinalBalance($final_balance)
    {
        $this->final_balance = $final_balance;
        return $this;
    }

    /**
     * Number of confirmed transactions on the specified address. Only transactions that have made it into a block
     * (confirmations > 0) are counted.
     *
     * @return int
     */
    public function getNTx()
    {
        return $this->n_tx;
    }

    /**
     * Number of confirmed transactions on the specified address. Only transactions that have made it into a block
     * (confirmations > 0) are counted.
     *
     * @param int $n_tx
     * @return $this
     */
    public function setNTx($n_tx)
    {
        $this->n_tx = $n_tx;
        return $this;
    }

    /**
     * All unconfirmed transaction inputs and outputs for the specified address.
     *
     * @return int
     */
    public function getUnconfirmedNTx()
    {
        return $this->unconfirmed_n_tx;
    }

    /**
     * All unconfirmed transaction inputs and outputs for the specified address.
     *
     * @param int $unconfirmed_n_tx
     * @return $this
     */
    public function setUnconfirmedNTx($unconfirmed_n_tx)
    {
        $this->unconfirmed_n_tx = $unconfirmed_n_tx;
        return $this;
    }

    /**
     * Append Txref to the list.
     *
     * @param \BlockCypher\Api\Txref $txref
     * @return $this
     */
    public function addTxref($txref)
    {
        if (!$this->getTxrefs()) {
            return $this->setTxrefs(array($txref));
        } else {
            return $this->setTxrefs(
                array_merge($this->getTxrefs(), array($txref))
            );
        }
    }

    /**
     * All transaction inputs and outputs for the specified address.
     *
     * @return \BlockCypher\Api\Txref[]
     */
    public function getTxrefs()
    {
        return $this->txrefs;
    }

    /**
     * All transaction inputs and outputs for the specified address.
     *
     * @param \BlockCypher\Api\Txref[] $txrefs
     *
     * @return $this
     */
    public function setTxrefs($txrefs)
    {
        $this->txrefs = $txrefs;
        return $this;
    }

    /**
     * Remove Txref from the list.
     *
     * @param \BlockCypher\Api\Txref $txref
     * @return $this
     */
    public function removeTxref($txref)
    {
        return $this->setTxrefs(
            array_diff($this->getTxrefs(), array($txref))
        );
    }

    /**
     * To retrieve base URL transactions. To get the full URL, concatenate this URL with the transaction's hash.
     *
     * @return Links
     */
    public function getTxUrl()
    {
        return $this->tx_url;
    }

    /**
     * To retrieve base URL transactions. To get the full URL, concatenate this URL with the transaction's hash.
     *
     * @param Links $tx_url
     * @return $this
     */
    public function setTxUrl($tx_url)
    {
        $this->tx_url = $tx_url;
        return $this;
    }

    /**
     * Final number of transactions, including unconfirmed transactions, for this address.
     *
     * @return int
     */
    public function getFinalNTx()
    {
        return $this->final_n_tx;
    }

    /**
     * Final number of transactions, including unconfirmed transactions, for this address.
     *
     * @param int $final_n_tx
     * @return $this
     */
    public function setFinalNTx($final_n_tx)
    {
        $this->final_n_tx = $final_n_tx;
        return $this;
    }
}