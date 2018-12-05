<?php
/**
 * Created by PhpStorm.
 * User: trietl
 * Date: 12/4/18
 * Time: 11:27 AM
 */

namespace SpotInst\Tabs;

class Stragery extends \SpotInst\Tabs\TabBase implements TabsInterface
{

    public function validate()
    {
       $validator = Validator::make([],  $this->data);
       return $validator;
    }

    public function build()
    {
        return [
            "risk" => 100,
            "availabilityVsCost" => "costOriented",
            "fallbackToOd" => true,
            "persistence" => [
                "shouldPersistPrivateIp" => true,
                "shouldPersistBlockDevices" => true,
                "shouldPersistRootDevice" => true,
                "blockDevicesMode" => "onLaunch"
            ],
            "revertToSpot" => [
                "performAt" => 'always'
            ],
        ];
    }
}