<?php
namespace SpotInst\ElasticGroup;

use SpotInst\SpotInstClientInterface;

abstract class  BaseApi
{
    protected $data;

    public function __construct(SpotInstClientInterface $spotInstClient, array $data = [])
    {
        $this->data = $data;
        $this->client = $spotInstClient;
    }


    public function setData($data) {
        $this->data = $data;
    }
}