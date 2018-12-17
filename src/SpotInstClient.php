<?php
namespace SpotInst;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;

class SpotInstClient implements SpotInstClientInterface
{
    protected $endingPoint = 'https://api.spotinst.io/';

    protected $guzzleClient;

    protected $spotAccountId;

    protected $accessToken;


    /**
     * SpotInstClient constructor.
     * @param string $spotAccountId
     */
    public function __construct(string $spotAccountId, string $accessToken)
    {
        if(empty(trim($spotAccountId)) || empty(trim($accessToken))) {
            throw new \EmptyParamException('SpotInst Account Params is not valid');
        }

        $this->accessToken = $accessToken;
        $this->spotAccountId = $spotAccountId;
        $this->guzzleClient = new Client();
    }

    /**
     * Post action
     * @param string $uri
     * @param array $jsonData
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function post(string $uri, array $jsonData = []) {
        try {
            $uri = str_replace('{ACCOUNT_ID}', $this->spotAccountId, $uri);

            $guzzleRequestOptions = [
                'json' => $jsonData,
                'headers' => [
                    "Content-Type" => "application/json",
                    "Authorization" => "Bearer {$this->getAuth()}"
                ]
            ];

            $response = $this->guzzleClient->request('POST', ($this->endingPoint . $uri), $guzzleRequestOptions);
        }  catch (ClientException $e) {
            $jsonBody = json_decode($e->getResponse()->getBody());
            $ret = json_decode(json_encode($jsonBody->response->errors),true);
            $response = [ 'response' => array_shift($ret) ];
            return $response;
        }

        $stream = \GuzzleHttp\Psr7\stream_for($response->getBody());
        $data = json_decode($stream, true);
        $response = [ 'response' => $data['response'] ];
        return $response;
    }

    /**
     * Get action
     * @param string $uri
     * @param array $jsonData
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get(string $uri, array $jsonData = []) {

        try {
            $uri = str_replace('{ACCOUNT_ID}', $this->spotAccountId, $uri);

            $guzzleRequestOptions = [
                'json' => $jsonData,
                'headers' => [
                    "Content-Type" => "application/json",
                    "Authorization" => "Bearer {$this->getAuth()}"
                ]
            ];
            $response = $this->guzzleClient->request('GET', ($this->endingPoint . $uri), $guzzleRequestOptions);
        } catch (ClientException $e) {
            $jsonBody = json_decode($e->getResponse()->getBody());
            $ret = json_decode(json_encode($jsonBody->response->errors),true);
            $response = [ 'response' => array_shift($ret) ];
            return $response;
        }
        $stream = \GuzzleHttp\Psr7\stream_for($response->getBody());
        $data = json_decode($stream, true);
        $response = [ 'response' => $data['response'] ];
        return $response;
    }

    /**
     * Put action
     * @param string $uri
     * @param array $jsonData
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function put(string $uri, array $jsonData = []) {
        try {
            $uri = str_replace('{ACCOUNT_ID}', $this->spotAccountId, $uri);

            $guzzleRequestOptions = [
                'json' => $jsonData,
                'headers' => [
                    "Content-Type" => "application/json",
                    "Authorization" => "Bearer {$this->getAuth()}"
                ]
            ];
            $response = $this->guzzleClient->request('PUT', ($this->endingPoint . $uri), $guzzleRequestOptions);
        } catch (ClientException $e) {
            $jsonBody = json_decode($e->getResponse()->getBody());
            $ret = json_decode(json_encode($jsonBody->response->errors),true);
            $response = [ 'response' => array_shift($ret) ];
            return $response;
        }
        $stream = \GuzzleHttp\Psr7\stream_for($response->getBody());
        $data = json_decode($stream, true);
        $response = [ 'response' => $data['response'] ];
        return $response;
    }

    /**
     * Delete action
     * @param string $uri
     * @param array $jsonData
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete(string $uri, array $jsonData = []) {
        try {
            $uri = str_replace('{ACCOUNT_ID}', $this->spotAccountId, $uri);

            $guzzleRequestOptions = [
                'json' => $jsonData,
                'headers' => [
                    "Content-Type" => "application/json",
                    "Authorization" => "Bearer {$this->getAuth()}"
                ]
            ];
            $response = $this->guzzleClient->request('DELETE', ($this->endingPoint . $uri), $guzzleRequestOptions);
        } catch (ClientException $e) {
            $jsonBody = json_decode($e->getResponse()->getBody());
            $ret = json_decode(json_encode($jsonBody->response->errors),true);
            $response = [ 'response' => array_shift($ret) ];
            return $response;
        }
        $stream = \GuzzleHttp\Psr7\stream_for($response->getBody());
        $data = json_decode($stream, true);
        $response = [ 'response' => $data['response'] ];
        return $response;
    }

    private function getAuth() {
        return $this->accessToken;
    }



}