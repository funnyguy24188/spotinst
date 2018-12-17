<?php
namespace SpotInst\ElasticGroup\AWS;


use Illuminate\Validation\Validator;
use SpotInst\ElasticGroup\BaseApi;
use SpotInst\Exception\NotValidParam;
use SpotInst\SpotInstClientInterface;
use SpotInst\Tabs\Capacity;
use SpotInst\Tabs\Compute;
use SpotInst\Tabs\Stragery;
use SpotInst\Tabs\ThirdPartiesIntergration;

class ElasticGroupApi extends BaseApi
{

    /**
     * @var SpotInstClientInterface
     */
    protected $client;

    protected $tabs = [];

    public function __construct(SpotInstClientInterface $spotInstClient, array $globalConfig = [])
    {
        parent::__construct($spotInstClient,$globalConfig);

        $this->tabs = [
            'capacity' => new Capacity($globalConfig),
            'stragery' => new Stragery($globalConfig),
            'compute'  => new Compute($globalConfig),
            'thirdparty' => new ThirdPartiesIntergration($globalConfig)
        ];

    }

    /**
     * @return mixed
     */

    public function listElasticGroup() {
        $uri = 'aws/ec2/group?accountId={ACCOUNT_ID}';
        return $this->client->get($uri);
    }

    /**
     * @param string $groupId
     * @return mixed
     */

    public function showElasticGroup(string $groupId) {
        $uri = 'aws/ec2/group/{GROUP_ID}?accountId={ACCOUNT_ID}';
        $uri = str_replace('{GROUP_ID}', $groupId, $uri);
        return $this->client->get($uri);
    }

    /**
     * @param string $name
     * @param string $description
     * @param string $region
     * @param array $patternConfig
     * @return mixed
     */
    public function createElasticgroupCluster(string $name = 'Default', string $description = 'Default', string $region = 'ap-southeast-1') {

        $uri = 'aws/ec2/group?accountId={ACCOUNT_ID}';

        // validate from lib
        foreach ($this->tabs as $type => $tab) {
            $validator = $tab->validate();
            /**
             * @var $validator Illuminate\Validation\Validator
             */
            if($validator->fails()) {
                return [ 'response' => [ 'code' => NotValidParam::NOT_VALID_PARAMS, 'message' => $validator->errors() ]] ;
            }
        }


        $jsonData = [
            "group" => [
                "name"=> $name,
                "description" => $description,
                "region" => $region,
                'capacity' => $this->tabs['capacity']->build(),
                'strategy' => $this->tabs['stragery']->build(),
                'compute' =>  $this->tabs['compute']->build(),
                'thirdPartiesIntegration' => $this->tabs['thirdparty']->build(),
                'scheduling' => new \stdClass(),
                'scaling' => new \stdClass(),

            ],

        ];
        return  $this->client->post($uri, $jsonData);
    }

    public function updateCapacity(string $groupId, $data) {

        $validator = \Validator::make($data,[
            'minimum' => 'required|integer',
            'maximum' => 'required|integer',
            'target' => 'required|integer'
        ]);

        if($validator->fails()) {
            return [ 'response' => [ 'code' => NotValidParam::NOT_VALID_PARAMS, 'message' => $validator->errors() ]] ;
        }

        $minimum = $data['minimum'];
        $maximum = $data['maximum'];
        $target = $data['target'];

        $uri = 'aws/ec2/group/{GROUP_ID}/capacity?accountId={ACCOUNT_ID}';
        $uri = str_replace('{GROUP_ID}', $groupId, $uri);
        $jsonData = [
            "capacity" => [
                "minimum" => $minimum,
                "maximum" => $maximum,
                "target" => $target
            ]
        ];
        return $this->client->put($uri, $jsonData);
    }


}