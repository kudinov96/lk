<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;

class MainMenu extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        if(!empty($this->config['menu'])) {
			$items = menu($this->config['menu'], '_json');
			
			return view('widgets.main_menu', [
				'items' => $items,
				'config' => $this->config,
			]);
		} else {
			return '';
		}
    }
}
