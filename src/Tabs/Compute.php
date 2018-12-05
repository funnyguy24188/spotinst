<?php
namespace SpotInst\Tabs;

use Illuminate\Validation\Validator;

class Compute extends TabBase implements \TabsInterface
{

    public function validate()
    {
        $validator = Validator::make([
            'imageId' => 'required',
            'keyPair' => 'required',
            'spot' => 'required',
            'securityGroupIds' => 'required',
            'availabilityZones' => 'required'
        ], $this->data);

        return $validator;
    }

    public function build()
    {
        return [
            'instanceTypes' => [
                'ondemand' => config('SPOTINST_ONDEMAND_INST_DEFAULT', 't2.micro'),
                'spot' => $this->getData('spot')
            ],
            'availabilityZones' =>  [
                'name' => $this->getData('availabilityZones.name'),
                'subnetId' => $this->getData('availabilityZones.subnetId'),
            ],
            'product' => 'Linux/UNIX',
            'tag' => $this->getData('tags', []),
            'privateIps' => $this->getData('privateIps'),
            'elasticIps' => $this->getData('elasticIps'),
            'launchSpecification' => [
                'securityGroupIds' => $this->getData('launchSpecification.securityGroupIds'),
                'monitor' => false,
                'imageId' => $this->getData('launchSpecification.imageId'),
                'keyPair' => $this->getData('launchSpecification.keyPair'),
                'ebsOptimized ' => false,
                'iamRole' => [
                    'name' => null,
                    'arn' => $this->getData('launchSpecification.iamRole.arn')
                ]
            ]

        ];
    }



}