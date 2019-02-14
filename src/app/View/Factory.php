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

use \Slim\Views\Twig;

class Factory
{
    protected $view;
    protected $settings;

    public function __construct($settings)
    {
        $this->settings = $settings;
    }

    public static function getEngine($settings)
    {
        return new Twig($settings->get('view.template_path'), [
            'cache' => $settings->get('view.cache'),
        ]);
    }

    public function make($view, $data = [])
    {
        $this->view = static::getEngine($this->settings)->fetch($view, $data);
        return $this;
    }

    public function render()
    {
        return $this->view;
    }
}
