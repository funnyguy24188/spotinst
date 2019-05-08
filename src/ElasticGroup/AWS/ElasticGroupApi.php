<?php
namespace SpotInst\ElasticGroup\AWS;


use Illuminate\Validation\Validator;
use SpotInst\ElasticGroup\BaseApi;
use SpotInst\Exception\InvalidConfig;
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

    protected $tabs = [];

    public function __construct(SpotInstClientInterface $spotInstClient, array $globalConfig = [])
    {
        parent::__construct($spotInstClient,$globalConfig);
        
        if(empty($globalConfig['pattern']) && empty($globalConfig['config'])) {
            throw new InvalidConfig('Invalidate config');
        }

        $this->tabs = [
            'capacity' => new Capacity($globalConfig['pattern']),
            'stragery' => new Stragery($globalConfig['pattern']),
            'compute'  => new Compute($globalConfig['pattern']),
            'thirdparty' => new ThirdPartiesIntergration($globalConfig['pattern'])
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
                'capacity' => $this->tabs['capacity']->build($this->config['config']),
                'strategy' => $this->tabs['stragery']->build($this->config['config']),
                'compute' =>  $this->tabs['compute']->build($this->config['config']),
                'thirdPartiesIntegration' => $this->tabs['thirdparty']->build($this->config['config']),
                'multai' => null,
                'scheduling' => new \stdClass(),
                'scaling' => new \stdClass(),

            ],

        ];
        return  $this->client->post($uri, $jsonData);
    }

    /**
     * Update capacity of group
     * @param string $groupId
     * @param $data
     * @return array|mixed
     */
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

    /**
     * Get heath of a group
     * @param string $groupId
     * @return mixed
     */
    public function getHealthiness(string $groupId) {
        $uri = 'aws/ec2/group/{GROUP_ID}/instanceHealthiness?accountId={ACCOUNT_ID}';
        $uri = str_replace('{GROUP_ID}', $groupId, $uri);
        return $this->client->get($uri, []);
    }

    /**
     * Delete a group
     * @param string $groupId
     */
    public function deleteGroup(string $groupId) {
        $uri = 'aws/ec2/group/{GROUP_ID}?accountId={ACCOUNT_ID}';
        $uri = str_replace('{GROUP_ID}', $groupId, $uri);
        $jsonData = [
            'statefulDeallocation' => [
                'shouldDeleteImages'=> true,
                'shouldDeleteNetworkInterfaces'=> true,
                'shouldDeleteVolumes'=> true,
                'shouldDeleteSnapshots'=> true
            ]
        ];
        return $this->client->delete($uri, $jsonData);
    }


    /**
     * Generate default Elastic Group config JSON for override on other service
     * @return string
     */
    public static function generateDefaultPatternJson() {

        $pattern = <<<RAW
{
  "group": {
    "name": "",
    "description": "",
    "strategy": {
      "risk": 100,
      "onDemandCount": null,
      "availabilityVsCost": "availabilityOriented",
      "drainingTimeout": 120,
      "fallbackToOd": true,
      "lifetimePeriod": "days",
      "persistence": {
        "shouldPersistBlockDevices": true,
        "shouldPersistRootDevice": true,
        "shouldPersistPrivateIp": true,
        "blockDevicesMode": "onLaunch"
      },
      "revertToSpot": {
        "performAt": "never"
      }
    },
    "capacity": {
      "target": 1,
      "minimum": 0,
      "maximum": 1,
      "unit": "instance"
    },
    "scaling": {},
    "compute": {
      "instanceTypes": {
        "ondemand": "t3.small",
        "spot": [
            "t2.small",
          "t3.small" 
        ],
        "preferredSpot": [
          "t3.small"
        ]
      },
      "availabilityZones": [
        {
          "name": "ap-southeast-1a",
          "subnetIds": [
            "subnet-43e36627"
          ]
        }
      ],
      "product": "Linux/UNIX",
      "launchSpecification": {
        "loadBalancerNames": null,
        "loadBalancersConfig": {
          "loadBalancers": null
        },
        "securityGroupIds": [
         "{SPOT_SECURITY_GROUP_ID}"
        ],
        "monitoring": false,
        "ebsOptimized": false,
        "imageId": "{SPOT_IMAGE_ID}",
        "keyPair": "propextra-dev-ap-southeast-1",
        "networkInterfaces": [
          {
            "deviceIndex": 0,
            "associatePublicIpAddress": true,
            "deleteOnTermination": true,
            "associateIpv6Address": false
          }
        ],
        "userData": null,
        "shutdownScript": null,
        "iamRole": {
          "name": null,
          "arn": "{SPOT_ARN}"
        },
        "tenancy": "default"
      },
      "elasticIps": null
    }

  }
}

RAW;

        return $pattern;

    }

}