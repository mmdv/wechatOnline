<?php

namespace app\index\model;
use think\Model;
use traits\model\SoftDelete;//使用软删除
//    模型需要继承Model
class User extends Model
{
    use SoftDelete;
    protected $autoWriteTimestamp = true;  //开启时间戳自动添加更新数据库时间记录
    protected $createTime = false;//后参数为数据库字段
    protected $updateTime = 'update_at';
    protected $deleteTime = 'delete_at';
}