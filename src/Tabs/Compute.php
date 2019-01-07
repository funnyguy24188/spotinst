<?php
namespace SpotInst\Tabs;

use Illuminate\Validation\Validator;
use SpotInst\ElasticGroup\AWS\ElasticGroupApi;

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

        $defaultInstance = (!empty($config['defaultInstance']) ? $config['defaultInstance'] : $this->getData('group.compute.instanceTypes.ondemand'));
        $arr =  [
            'instanceTypes' => [
                'ondemand' => $defaultInstance,
                'spot' => $this->getData('group.compute.instanceTypes.spot')
            ],
            'availabilityZones' =>  $this->getData('group.compute.availabilityZones'),
            'product' => 'Linux/UNIX',
            'tags' => $this->getData('tags', []),
            'privateIps' => $this->getData('group.compute.privateIps'),
            'elasticIps' => $this->getData('group.compute.elasticIps'),
            'launchSpecification' => [
                'securityGroupIds' => $this->getData('group.compute.launchSpecification.securityGroupIds'),
                'monitoring' => false,
                'networkInterfaces' => [
                    [
                        'associateIpv6Address' => false,
                        'associatePublicIpAddress' => true,
                        'deleteOnTermination' => true,
                        'deviceIndex' => 0
                    ]
                ],
                //'imageId' => $this->getData('group.compute.launchSpecification.imageId', 'ami-c63d6aa5'),
                'imageId' => 'ami-c63d6aa5',
                'keyPair' => $this->getData('group.compute.launchSpecification.keyPair'),
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
                    break;
            }
        }

        if(empty($arr['tags'])) {
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