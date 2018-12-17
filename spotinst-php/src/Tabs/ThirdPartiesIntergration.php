<?php
namespace SpotInst\Tabs;

class ThirdPartiesIntergration extends \SpotInst\Tabs\TabBase implements \SpotTabsInterface
{

    public function validate()
    {
        return   \Validator::make([], $this->data);

    }

    public function build()
    {
        return new \stdClass();
    }
}