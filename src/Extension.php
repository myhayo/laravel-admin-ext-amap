<?php

namespace Encore\Admin\AMap;

use Encore\Admin\Extension as BaseExtension;

class Extension extends BaseExtension
{
    public $name = 'amap';

    public $views = __DIR__ . '/../resources/views';

    public $assets = __DIR__ . '/../resources/assets';
}