<?php
namespace SpotInst\Tabs;

class ThirdPartiesIntergration extends \SpotInst\Tabs\TabBase implements \SpotTabsInterface
{

    const THIRDPARTY_OPSWORKS_TYPE = 'opsworks';

    public function validate()
    {
        return \Validator::make([], $this->data);

    }

    public function build()
    {
        $thirdPartyType = config('devbox.resources.spotinst_thirdparty');
        $arr = new \stdClass();

        if($thirdPartyType) {
            switch ($thirdPartyType) {
                case self::THIRDPARTY_OPSWORKS_TYPE: {
                    $layerId = env('SPOTINST_THIRDPARTY_OPSWORKS_LAYER_ID');
                    $stackType = env('SPOTINST_THIRDPARTY_OPSWORKS_STACKTYPE');
                    $arr->opsWorks =  [
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
     * @return string
     */
    public static function getOpsWorksUserData() {
        $layerId = env('SPOTINST_THIRDPARTY_OPSWORKS_LAYER_ID');
        $stackId = env('SPOTINST_THIRDPARTY_OPSWORKS_STACK_ID');
        $stackType = env('SPOTINST_THIRDPARTY_OPSWORKS_STACKTYPE');

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