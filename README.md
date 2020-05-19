laravel-admin extension amap
======

# 安装

    composer require myhayo/laravel-admin-ext-amap

# 使用
## 配置
打开admin.php, 找到extensions配置段, 加入以下配置:
    
    // 高德地图
    'amap'     => [
    
        // 是否启用插件
        'enable' => true,
    
        'config' => [
            // API KEY
            'api_key' => '3693fe745aea0df8852739dac08a22fb',
        ],
    ],

## 调用
    
    $form->amap('lat 字段名', 'lng 字段名', 'address 地址字段名', 'label');
    
    // 隐藏经纬度输入框
    $form->amap('lat 字段名', 'lng 字段名', 'address 地址字段名', 'label')->hideLngLatInput();
