<?php
return array(
    'database'=>array(
        'dbms'=>'mysql',
        'dbname'=>'db1',
        'user'=>'root',
        'pwd'=>'',
        'charset'=>'utf-8',
        'host'=>'127.0.0.1'
    ),
    
    'app'=>array(
        'default_platform'  =>'Admin',
        'default_controller'=>'Login',
        'default_action'    =>'Login',
        'key'               =>'asdf',
        
        'upload_path'       => 'Public/uploads/',
        'upload_type'       => array('image/jpeg','image/png','image/gif'),
        'upload_size'       => 2
    ),
    
    'admin'=>array(
        'goods_pagesize'=>2
    )
    
);
