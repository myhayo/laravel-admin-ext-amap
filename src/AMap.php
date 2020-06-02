<?php

namespace Encore\Admin\AMap;


use Encore\Admin\Form\Field;

class AMap extends Field
{
    /**
     * Column name.
     *
     * @var array
     */
    protected $column = [];

    protected $view = 'laravel-admin-amap::amap';

    /**
     * Map height.
     *
     * @var int
     */
    protected $height = 300;

    /**
     * 是否隐藏lng lat输入框
     *
     * @var bool
     */
    protected $hidden_lng_lat_input = false;

    const AMAP_JS_API = '//webapi.amap.com/maps?v=1.4.12&plugin=AMap.Geocoder&key=%s';
    const AMAP_UI_JS_API = '//webapi.amap.com/ui/1.1/main.js?v=1.1.1';

    public function __construct(string $column = '', array $arguments = [])
    {
        $this->column['lat'] = (string)$column;
        $this->column['lng'] = (string)$arguments[0];
        $this->column['address'] = (string)$arguments[1];

        array_shift($arguments);
        array_shift($arguments);

        $this->label = $this->formatLabel($arguments);
        $this->id = $this->formatId($this->column);
    }

    /**
     * Set map height.
     *
     * @param int $height
     *
     * @return $this
     */
    public function height(int $height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * 隐藏经纬度输入框.
     *
     * @param bool $is_hide
     *
     * @return $this
     */
    public function hideLngLatInput(bool $is_hide = true)
    {
        $this->hidden_lng_lat_input = $is_hide;

        return $this;
    }

    /**
     * @return array
     */
    public static function getAssets()
    {
        return ['js' => [sprintf(self::AMAP_JS_API, Extension::config('config.api_key')), self::AMAP_UI_JS_API]];
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function render()
    {
        $this->script = $this->applyScript();

        return parent::render()->with([
            'height'               => $this->height,
            'column'               => $this->column,
            'hidden_lng_lat_input' => $this->hidden_lng_lat_input,
        ]);
    }

    public function applyScript()
    {
        return <<<EOT
(function() {
    
    function init(name) {
        function resetAddressInput(inputName, lnglat) {
            geocoder.getAddress(lnglat, function(status, result) {
                if (status === 'complete' && result.regeocode) {
                    $('#' + inputName).val(result.regeocode.formattedAddress);
                }
            });
        }

        var lat = $('#{$this->column['lat']}');
        var lng = $('#{$this->column['lng']}');
        var selectInputName = '{$this->column['address']}';
    
        var map = new AMap.Map(name, {
            zoom:18,
            center: [lng.val() || 0, lat.val() || 0],//中心点坐标
            viewMode:'3D'//使用3D视图
        });
        
        var geocoder = new AMap.Geocoder();
        
        var marker = new AMap.Marker({
            map: map,
            draggable: true,
            position: [lng.val() || 0, lat.val() || 0],
        })

        map.on('click', function(e) {
            marker.setPosition(e.lnglat);
            
            lat.val(e.lnglat.getLat());
            lng.val(e.lnglat.getLng());
            
            resetAddressInput(selectInputName, e.lnglat);
        });
        
        marker.on('dragend', function (e) {
            lat.val(e.lnglat.getLat());
            lng.val(e.lnglat.getLng());
            
            resetAddressInput(selectInputName, e.lnglat);
        });
        
        if( ! lat.val() || ! lng.val()) {
            map.plugin('AMap.Geolocation', function () {
                geolocation = new AMap.Geolocation();
                map.addControl(geolocation);
                geolocation.getCurrentPosition();
                AMap.event.addListener(geolocation, 'complete', function (data) {
                    marker.setPosition(data.position);
                    
                    lat.val(data.position.getLat());
                    lng.val(data.position.getLng());
                    
                    resetAddressInput(selectInputName, e.lnglat);
                });
            });
        }

        AMapUI.loadUI(['misc/PoiPicker'], function(PoiPicker) {
            var poiPicker = new PoiPicker({
                input: "{$this->column['address']}"
            });
            
            poiPicker.on('poiPicked', function(poiResult) {
                map.setZoomAndCenter(18, poiResult.item.location);
                marker.setPosition(poiResult.item.location);
                lat.val(poiResult.item.location.lat);
                lng.val(poiResult.item.location.lng);
                
                resetAddressInput(selectInputName, [poiResult.item.location.lng, poiResult.item.location.lat]);
            });
        });
    }
    
    init('map_{$this->column['lat']}{$this->column['lng']}');
})();
EOT;
    }
}
