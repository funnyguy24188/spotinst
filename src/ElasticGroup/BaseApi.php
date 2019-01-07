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
        if(!empty($config['config'])) {
            $this->config = $config['config'];
        }
        $this->client = $spotInstClient;
    }


    public function setData($data) {
        $this->data = $data;
    }
}