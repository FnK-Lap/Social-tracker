<?php

namespace SocialTracker\Bundle\ApplicationBundle\Facebook;

use Facebook\FacebookSDKException;
use Facebook\HttpClients\FacebookHttpable;
use Guzzle\Service\Client;
// use GuzzleHttp\Exception\AdapterException;
// use GuzzleHttp\Exception\RequestException;

class GuzzleClientHandler implements FacebookHttpable
{
    protected $requestHeaders = array();
    protected $responseHeaders = array();
    protected $responseHttpStatusCode = 0;

    protected static $guzzleClient;

    public function __construct(Client $guzzleClient = null)
    {
        self::$guzzleClient = $guzzleClient ?: new Client();
    }

    public function addRequestHeader($key, $value)
    {
        $this->requestHeaders[$key] = $value;
    }

    public function getResponseHeaders()
    {
        return $this->responseHeaders;
    }

    public function getResponseHttpStatusCode()
    {
        return $this->responseHttpStatusCode;
    }

    public function send($url, $method = 'GET', $parameters = array())
    {
        $options = array();
        if ($parameters) {
            $options = array('body' => $parameters);
        }

        $request = self::$guzzleClient->createRequest($method, $url, $options);

        foreach($this->requestHeaders as $k => $v) {
            $request->setHeader($k, $v);
        }

        // try {
            $rawResponse = self::$guzzleClient->send($request);
        // } catch (RequestException $e) {
        //     if ($e->getPrevious() instanceof AdapterException) {
        //         throw new FacebookSDKException($e->getMessage(), $e->getCode());
        //     }
        //     $rawResponse = $e->getResponse();
        // }

        $this->responseHttpStatusCode = $rawResponse->getStatusCode();
        $this->responseHeaders = $rawResponse->getHeaders();

        return $rawResponse->getBody();
    }
}
