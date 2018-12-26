<?php

namespace SpotInst\Tabs;


class Capacity extends TabBase implements \SpotTabsInterface
{

    public function validate()
    {
        return \Validator::make($this->data, [
            'group.capacity.target' => 'required',
            'group.capacity.minimum' => 'required',
            'group.capacity.maximum' => 'required',
        ]);
    }

    public function build(array $config)
    {
        $target = (!empty($config['target']) ? $config['target'] : $this->getData('group.capacity.target')) ;
        $minimum = (!empty($config['minimum']) ? $config['minimum'] : $this->getData('group.capacity.minimum')) ;
        $maximum = (!empty($config['maximum']) ? $config['maximum'] : $this->getData('group.capacity.maximum')) ;

        return [
            'target' =>  $target,
            'minimum' =>  $minimum,
            'maximum'=> $maximum,
            'unit' => 'instance',
        ];
    }
}