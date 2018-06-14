<?php
namespace Dan;

class Response
{
    protected $res;
    public function __construct() {
    }
    public function setStatus()
    {
        $this->res['Status Code'] = '404';
    }
    public function setHeader()
    {
        
    }
}
