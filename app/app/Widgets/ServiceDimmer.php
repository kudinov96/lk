<?php

namespace App\Widgets;

use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Widgets\BaseDimmer;

class ServiceDimmer extends BaseDimmer
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
        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-credit-cards',
            'title'  => "Все услуги",
            'text'   => "Редактирование подписок, курсов, услуг и графиков",
            'buttons' => [
                [
                    'text' => "Подписки",
                    'link' => route("voyager.subscription.index"),
                ],
                [
                    'text' => "Курсы",
                    'link' => route("voyager.course.index"),
                ],
                [
                    'text' => "Услуги",
                    'link' => route("voyager.service.index"),
                ],
                [
                    'text' => "Графики",
                    'link' => route("voyager.graph.index"),
                ],
            ],
            'image' => voyager_asset('images/widget-backgrounds/02.jpg'),
        ]));
    }

    /**
     * Determine if the widget should be displayed.
     *
     * @return bool
     */
    public function shouldBeDisplayed()
    {
        return Auth::user()->can('browse', Voyager::model('User'));
    }
}
