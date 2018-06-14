<?php
namespace App\Active;

class Book
{
	protected $ci;
    public function __construct($ci)
    {
        $this->ci = $ci;
    }
    public function __invoke($request, $response) {
        $method = $request->get('REQUEST_METHOD');
        switch ($method) {
            case 'GET':
                $this->GET($request, $response);
                break;
            case 'POST':
                $this->POST($request, $response);
                break;
            case "DELETE":
                $this->DELETE($request, $response);
                break;
            default:
                $this->jsr('FUCK! WHAT ARE YOU WANNA DOING!!!');
                break;
        }
    }
    public function GET($request, $response)
    {
        // webhook
        // header("Content-Type: text/html;charset=utf-8");
        // $value = 'fuck you man';
        // $this->ci->get('view')->assign('content', $value);
        // $this->ci->get('view')->display('book.html');
        // $path = $this->ci->get('setting')['store'];
        // debug($path);
        // $update = system('dir');
        // sleep(3);
        // $update = system('dir');
        // $update = passthru('git init store');
        // exec('powershell' , $update);
        // exec('cd store' , $update);
        $ss = $this->ci->get('git')->init();
    }
}
