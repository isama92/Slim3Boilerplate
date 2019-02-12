<?php
/*
|--------------------------------------------------------------------------
| Factory
|--------------------------------------------------------------------------
|
| Paginator page switcher factory
|
*/

namespace App\View;

use SlimFacades\App;


class Factory
{
    protected $view;

    public static function getEngine()
    {
        $settings = App::getContainer()['settings'];
        return new \Slim\Views\Twig($settings['template_path'], [
            'cache' => !$settings['test_mode']? $settings['tmp_path'] .DIRECTORY_SEPARATOR . 'twig' : false,
        ]);
    }

    public function make($view, $data = [])
    {
        $this->view = static::getEngine()->fetch($view, $data);
        return $this;
    }

    public function render()
    {
        return $this->view;
    }
}
