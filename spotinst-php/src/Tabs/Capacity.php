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

    public function build()
    {
        return [
            'target' =>  $this->getData('group.capacity.target'),
            'minimum' =>  $this->getData('group.capacity.minimum'),
            'maximum'=> $this->getData('group.capacity.maximum'),
            'unit' => 'instance',
        ];
    }
}