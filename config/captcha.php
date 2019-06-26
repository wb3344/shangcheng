<?php
//验证码配置
return [

	// 验证码字体大小
    'fontSize'    =>    22,    
    // 验证码位数
    'length'      =>    4,   
    // 关闭验证码杂点
    'useNoise'    =>    false, 

    'codeSet'  =>'1234567890',

    'useCurve'  => false,

    'imageH'	=>	54,

    'imageW'    =>  128,

    'bg'	=> [rand(0,255),rand(0,255),rand(0,255)],
];