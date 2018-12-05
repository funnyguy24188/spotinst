<?php
namespace SpotInst\ElasticGroup\AWS;


use SpotInst\SpotInstClientInterface;
use SpotInst\Tabs\Capacity;
use SpotInst\Tabs\Compute;
use SpotInst\Tabs\Stragery;
use SpotInst\Tabs\ThirdPartiesIntergration;

class ElasticGroupApi
{

    /**
     * @var SpotInstClientInterface
     */
    protected $client;

    protected $tabs = [];

    public function __construct(array $data)
    {
        $this->client = app()->make(SpotInstClientInterface::class);
        $this->tabs = [
            'capacity' => new Capacity($data),
            'stragery' => new Stragery($data),
            'compute'  => new Compute($data),
            'thirdparty' => new ThirdPartiesIntergration($data)
        ];

    }

    public function createElasticgroupCluster(string $name = 'Default', string $description = 'Default', string $region = 'us-east-1') {

        $uri = 'aws/ec2/group';

        foreach ($this->tabs as $type => $tab) {
            $validator = $tab->validate();
            if($validator->fails()) {
                
            }
        }

        $jsonData = [
            "group" => [
                "name"=> $name,
                "description" => $description,
                "region" => $region
            ],
            'capacity' => $this->tabs['capacity']->build(),
            'strategy' => $this->tabs['stragery']->build(),
            'compute' =>  $this->tabs['compute']->build(),
            'thirdPartiesIntegration' => $this->tabs['thirdparty']->build()
        ];

        var_dump($jsonData);die;

        $respone = $this->client->post($uri, $jsonData);

    }


}