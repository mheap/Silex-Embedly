<?php

namespace SilexEmbedly\Tests\Extension;

use Silex\Application;

use Symfony\Component\HttpFoundation\Request;

use SilexEmbedly\EmbedlyExtension;

use Embedly\Embedly;

class EmbedlyExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (!class_exists('Embedly\\Embedly')) {
            $this->markTestSkipped('Embedly was not installed.');
        }
    }
    
    public function testRegister()
    {
        $app = new Application();
        $app->register(new EmbedlyExtension(), array(
            'embedly.class_path' => __DIR__ . '/../../../vendor/embedly-php/src'
        ));
            
        $app->get('/', function() use($app) {
            $app['embedly'];    
        });
        $request = Request::create('/');
        $app->handle($request);
        
        $this->assertInstanceOf('Embedly\Embedly', $app['embedly']);
    }
    
    public function testApi()
    {
        $app = new Application();
        $app->register(new EmbedlyExtension(), array(
            'embedly.class_path' => __DIR__ . '/../../../vendor/embedly-php/src'
        ));
            
        $app->get('/', function() use($app) {
            $app['embedly'];    
        });
        $request = Request::create('/');
        $app->handle($request);
        
        $data = $app['embedly']->oembed('http://www.youtube.com/watch?v=c9BA5e2Of_U');
        $this->assertNotEmpty($data);
        $this->assertEquals($data->title, "Rick Astley - Never Gonna Give You Up (Live 2005)");
    }
}