<?php
namespace SpotInst\Tabs;

use Illuminate\Validation\Validator;

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

    public function build()
    {

        $arr =  [
            'instanceTypes' => [
                'ondemand' => config('SPOTINST_ONDEMAND_INST_DEFAULT', 't2.micro'),
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
                'imageId' => $this->getData('group.compute.launchSpecification.imageId', 'ami-c63d6aa5'),
                'keyPair' => $this->getData('group.compute.launchSpecification.keyPair'),
                'ebsOptimized' => false,
                'iamRole' => [
                    'name' => null,
                    'arn' => $this->getData('group.compute.launchSpecification.iamRole.arn')
                ]
            ]

        ];

        if(empty($arr['tags'])) {
            unset($arr['tags']);
        }

        if(empty($arr['privateIps'])) {
            unset($arr['privateIps']);
        }

        if(empty($arr['elasticIps'])) {
            unset($arr['elasticIps']);
        }

        return $arr;
    }



}