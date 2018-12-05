<?php
namespace SpotInst\Tabs;

class ThirdPartiesIntergration extends \SpotInst\Tabs\TabBase implements \TabsInterface
{

    public function validate()
    {
        $validator = Validator::make([], $this->data);
    }

    public function build()
    {
        return [];
    }
}