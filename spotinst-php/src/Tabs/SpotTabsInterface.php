<?php
/**
 * Created by PhpStorm.
 * User: trietl
 * Date: 12/4/18
 * Time: 11:30 AM
 */

interface SpotTabsInterface
{
    /**
     * Validate the build tab data
     * @return mixed
     */
    public function validate();

    /**
     * Build a section tab
     * @return mixed
     */
    public function build();

}