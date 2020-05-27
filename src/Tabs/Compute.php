<?php
namespace SpotInst\Tabs;

use Illuminate\Validation\Validator;
use SpotInst\ElasticGroup\AWS\ElasticGroupApi;
use SpotInst\Exception\EmptyParamException;
use SpotInst\Exception\InvalidConfig;

class Compute extends TabBase implements \SpotTabsInterface
{

    public function validate()
    {
        return  \Validator::make($this->data, [
            'group.compute.launchSpecification.imageId' => 'required',
            'group.compute.launchSpecification.keyPair' => 'required',
            'group.compute.instanceTypes.spot' => 'required',
            'group.compute.launchSpecification.securityGroupIds' => 'required',
            'group.compute.availabilityZones' => 'required'
        ]);
    }

    public function build(array $config)
    {
        $thirdPartyType = $config['thirdPartyType'];
        $layerId = $config['layerId'];
        $stackType = $config['stackType'];
        $stackId = $config['stackId'];
        $userData = $config['userData'];
        $imageId = $config['imageId'];
        $keyPair = $config['keyPair'];
        $product = $config['product'];
        $tags = $config['tags'];
        $availabilityZone = $config['availabilityZone'];
        $securityGroup = $config['securityGroup'];

        if(!$imageId) {
            throw new InvalidConfig('Image Id is empty');
        }

        $defaultInstance = (!empty($config['defaultInstance']) ? $config['defaultInstance'] : $this->getData('group.compute.instanceTypes.ondemand'));
        $preferedInstance = (!empty($config['defaultInstance']) ? $config['defaultInstance'] : $this->getData('group.compute.instanceTypes.preferredSpot'));
        $ebsSize = (!empty($config['ebsSize']) ? $config['ebsSize'] : 8);
        $arr =  [
            'instanceTypes' => [
                'ondemand' => $defaultInstance,
                'spot' => $this->getData('group.compute.instanceTypes.spot'),
                'preferredSpot' => [
                    $preferedInstance
                ]
            ],
            'availabilityZones' =>  $availabilityZone,
            'privateIps' => $this->getData('group.compute.privateIps'),
            'elasticIps' => $this->getData('group.compute.elasticIps'),
            'product' => $product,
            'launchSpecification' => [
                'tags' => $tags,
                'securityGroupIds' => [$securityGroup],
                'monitoring' => false,
                'networkInterfaces' => [
                    [
                        'associateIpv6Address' => false,
                        'associatePublicIpAddress' => true,
                        'deleteOnTermination' => true,
                        'deviceIndex' => 0
                    ]
                ],
                'blockDeviceMappings' => [
                    [
                        'deviceName' => '/dev/xvda',
                        'ebs' => [
                            'deleteOnTermination' => true,
                            'volumeType' => 'GP2',
                            'volumeSize' => $ebsSize
                        ]
                    ]
                ],
                'userData' => $userData,
                'imageId' => $imageId,
                'keyPair' => $keyPair,
                'ebsOptimized' => false,
                "healthCheckType" => null,
                "tenancy"=> "default",
                'iamRole' => [
                    'name' => null,
                    'arn' => $this->getData('group.compute.launchSpecification.iamRole.arn')
                ]
            ],
            'elasticIps'=> null

        ];

        foreach ($thirdPartyType as $thirdParty) {
            switch ($thirdParty) {
                case ThirdPartiesIntergration::THIRDPARTY_OPSWORKS_TYPE:
                    $arr['launchSpecification']['userData']  = ThirdPartiesIntergration::getOpsWorksUserData($stackId, $stackType, $layerId);
                    $arr['launchSpecification']['healthCheckType']  = 'OPSWORKS';
                    $arr['launchSpecification']['healthCheckGracePeriod']  = isset( $config['gracePeriod'] ) ? $config['gracePeriod'] : 300 ;
                    $arr['launchSpecification']['healthCheckUnhealthyDurationBeforeReplacement']  = 60;
                    break;
            }
        }

        if(!isset($arr['tags'])) {
            unset($arr['tags']);
        }

        if(empty($arr['privateIps'])) {
            $arr['privateIps'] = null;
        }

        if(empty($arr['elasticIps'])) {
            $arr['elasticIps'] = null;
        }

        return $arr;
    }



}