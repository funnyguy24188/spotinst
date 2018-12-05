<?php
namespace SpotInst;

use GuzzleHttp\Client;

class SpotInstClient implements SpotInstClientInterface
{
    protected $endingPoint = 'https://api.spotinst.io';

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
    public function post(string $uri, array $jsonData) {

        $uri .= ('?accountId=' . $this->spotAccountId );

        $guzzleRequestOptions = [
            'json' => $jsonData,
            'auth' => $this->getAuth(),
            'headers' => [
                'Accept' => 'application/json'
            ]
        ];
        $response = $this->guzzleClient->request('POST', $this->endingPoint . $uri, $guzzleRequestOptions);
        return $this->handleResponse($response);
    }

    /**
     * Get action
     * @param string $uri
     * @param array $jsonData
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get(string $uri, array $jsonData) {

        $uri .= ('?accountId=' . $this->spotAccountId );

        $guzzleRequestOptions = [
            'json' => $jsonData,
            'auth' => $this->getAuth(),
            'headers' => [
                'Accept' => 'application/json'
            ]
        ];
        $response = $this->guzzleClient->request('GET', $this->endingPoint, $guzzleRequestOptions);
        return $this->handleResponse($response);
    }

    /**
     * Put action
     * @param string $uri
     * @param array $jsonData
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function put(string $uri, array $jsonData) {

        $uri .= ('?accountId=' . $this->spotAccountId );

        $guzzleRequestOptions = [
            'json' => $jsonData,
            'auth' => $this->getAuth(),
            'headers' => [
                'Accept' => 'application/json'
            ]
        ];
        $response = $this->guzzleClient->request('PUT', $this->endingPoint, $guzzleRequestOptions);
        return $this->handleResponse($response);
    }

    /**
     * Delete action
     * @param string $uri
     * @param array $jsonData
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete(string $uri, array $jsonData) {

        $uri .= ('?accountId=' . $this->spotAccountId );

        $guzzleRequestOptions = [
            'json' => $jsonData,
            'auth' => $this->getAuth(),
            'headers' => [
                'Accept' => 'application/json'
            ]
        ];
        $response = $this->guzzleClient->request('DELETE', $this->endingPoint, $guzzleRequestOptions);
        return $this->handleResponse($response);
    }

    /**
     * @param Response $response
     * @return mixed
     */
    private function handleResponse(Response $response)
    {
        $stream = \GuzzleHttp\Psr7\stream_for($response->getBody());
        $data = json_decode($stream);
        return $data;
    }

    private function getAuth() {
        return "Authorization: Bearer $this->accessToken";
    }



}