<?php
namespace App\Active;

class MarkDown
{
	protected $ci;
    public function __construct($ci)
    {
        $this->ci = $ci;
    }
    public function __invoke() {
        return 'sdfsdf';
    }
    public function html($value='')
    {
        $html = file_get_contents( $this->ci->get('setting')['store'] . $_SERVER['PATH_INFO'] );
        $html = $this->ci->get('markdown')->makeHtml($html);

        $menu = file_get_contents( $this->ci->get('setting')['store'] . DIRECTORY_SEPARATOR . 'readme.md' );
        $menu = $this->ci->get('markdown')->makeHtml($menu);

        $this->ci->get('view')->assign('menu', $menu);
        $this->ci->get('view')->assign('content', $html);
        $this->ci->get('view')->display('index.html');
    }
}
