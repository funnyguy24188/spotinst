<?php
/**
 * Created by PhpStorm.
 * User: trietl
 * Date: 12/4/18
 * Time: 2:36 PM
 */

namespace SpotInst\Tabs;


class Capacity extends TabBase implements \TabsInterface
{

    public function validate()
    {
        $validator = Validator::make([
            'target' => 'required',
            'minimum' => 'required',
            'maximum' => 'required',
        ], $this->data);

        return $validator;
    }

    public function build()
    {
        return [
            'target' =>  $this->getData('target'),
            'minimum' =>  $this->getData('minimum'),
            'maximum'=> $this->getData('maximum'),
            'unit' => 'instance',
        ];
    }
}