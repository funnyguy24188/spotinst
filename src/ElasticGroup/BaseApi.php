<?php
namespace SpotInst\ElasticGroup;

use SpotInst\SpotInstClientInterface;

abstract class  BaseApi
{
    protected $data;

    protected $config;

    protected $client;

    public function __construct(SpotInstClientInterface $spotInstClient, array $config = [])
    {
        if(!empty($config['pattern'])) {
            $this->data = $config['pattern'];
        }
        $this->config = $this->fallBackConfig($config);
        $this->client = $spotInstClient;


    }


    public function setData($data) {
        $this->data = $data;
    }

    public function getConfig() {
        return $this->config;
    }

    private function fallBackConfig($config) {

        if(!empty($config['config'])) {

            $config['config']['thirdPartyType']  = isset($config['config']['thirdPartyType'] ) ? $config['config']['thirdPartyType'] : [];
            $config['config']['layerId'] = isset($config['config']['layerId']) ? $config['config']['layerId']: '';
            $config['config']['stackType'] = isset($config['config']['stackType']) ? $config['config']['stackType'] : '';
            $config['config']['stackId'] = isset($config['config']['stackId']) ? $config['config']['stackId'] : '';
            $config['config']['userData'] = isset($config['config']['userData']) ? $config['config']['userData'] : '';
            $config['config']['imageId'] = isset($config['config']['imageId']) ? $config['config']['imageId'] : '';
            $config['config']['keyPair'] = isset($config['config']['keyPair']) ? $config['config']['keyPair'] : null;
            $config['config']['tags'] = isset($config['config']['tags']) ? $config['config']['tags'] : [];

        }
        return $config;
    }

    public function setConfig($config) {
        $this->config = $this->fallBackConfig($config);
    }
}