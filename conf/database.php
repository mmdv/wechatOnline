<?php
	// 这个位置的database.php是所有模块都可以使用的 特殊
	use think\Env;
	return [
        // 数据库类型
        'type'            => 'mysql',
        'hostname'        => 'localhost',
        'database'        => Env::get('database.dbname','wechat'),
//        'database'        => 'wechat',
        'username'        => Env::get('database.username','root'),
//        'username'        => 'root',
        'password'        => Env::get('database.password',''),
        'password'        => '',
//        'hostport'        => '3306',
        'hostport'        => Env::get('database.hostport','3306'),
        'charset'         => 'utf8',
        'auto_timestamp'  => false,
	];