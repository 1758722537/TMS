<?php
	return[
	// 视图输出字符串内容替换
    'view_replace_str'       => [
    	'__LAYUI__'		=>	'/static/index/lib/layui',
    	'__SRC__'		=> 	'/static/index',
    	'__UPLOAD__'    =>  '/uploads'
    ],
    // 是否开启路由
    'url_route_on'           => true,
     //验证码参数
    'captcha' => [
            // 验证码字符集合
            'codeSet' => '2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY',
            // 验证码字体大小(px)
            'fontSize' => 25,
            // 是否画混淆曲线
            'useCurve' => true,
            // 验证码图片高度
            'imageH' => 50,
            // 验证码图片宽度
            'imageW' => 200,
            // 验证码位数
            'length' => 3,
            // 验证成功后是否重置
            'reset' => true
            ],
];