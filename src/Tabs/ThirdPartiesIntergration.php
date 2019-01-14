<?php
namespace SpotInst\Tabs;

class ThirdPartiesIntergration extends \SpotInst\Tabs\TabBase implements \SpotTabsInterface
{

    const THIRDPARTY_OPSWORKS_TYPE = 'opsworks';

    public function validate()
    {
        return \Validator::make([], $this->data);

    }

    /**
     * @param string $thirdPartyType
     * @param string $layerId
     * @param string $stackType
     * @return mixed|\stdClass
     */
    public function build(array $config)
    {

        $thirdPartyType = $config['thirdPartyType'];
        $layerId = $config['layerId'];
        $stackType = $config['stackType'];
        $stackId = $config['stackId'];

        $arr = new \stdClass();
        foreach ($thirdPartyType as $thirdParty) {
                switch ($thirdParty) {
                    case self::THIRDPARTY_OPSWORKS_TYPE:
                        {
                            $arr->opsWorks = [
                                'layerId' => $layerId,
                                'stackType' => $stackType
                            ];

                            break;
                        }
                }

        }

        
        return $arr;
    }

    /**
     * Generate user data for OpsWorks config
     * @param string $stackId
     * @param string $layerId
     * @param string $stackType
     * @return string
     */
    public static function getOpsWorksUserData(string $stackId, string $stackType, string $layerId ) {

        $opsWorksPatten = <<<RAW
                            #!/bin/bash

curl -fsSL https://s3.amazonaws.com/spotinst-public/integrations/opsworks/spotinst_aws_opsworks_v5.sh | \
OPSWORKS_STACK_TYPE="$stackType" \
OPSWORKS_STACK_ID="$stackId" \
OPSWORKS_LAYER_ID="$layerId" \
bash
RAW;
      return base64_encode($opsWorksPatten);
    }
    
    

}