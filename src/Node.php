<?php

namespace App;

class Node
{
    public $data;

    public ?Node $left = null;

    public ?Node $right = null;

    public function __construct($data)
    {
        $this->data = $data;
    }
}