<?php
/**
 * Created by PhpStorm.
 * User: trietl
 * Date: 12/4/18
 * Time: 11:38 AM
 */

namespace SpotInst\Tabs;


class TabBase
{

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function getData($key, $default = '') {
        $ret = null;
        $arrKey  = explode('.', $key);
        if(count($arrKey) > 1) {
            $data  = $this->data;
            foreach ($arrKey as $key) {
                if (isset($data[$key])) {
                    $data = $data[$key];
                    $ret = $data;
                }
            }
        } else {
            if (isset($this->data[$key])) {
                $ret = $this->data[$key];
            } else {
                $ret = $default;
            }
        }
        return $ret;
    }

}