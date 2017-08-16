<?php

    namespace app\index\model;
    use think\Model;

//    模型需要继承Model
    class User extends Model
    {
//        自动完成,在controller插入数据不需要包含time字段
//        $auto  在新增和修改都会写入数据库
//        $insert   在新增时使用
//        $update     修改时使用
        protected $auto = [
            'time'
        ];
        protected $insert = [
          'time_insert'//对应数据库列
        ];
        protected $update = [
            'time_update'
        ];

        #命名 imooc_user(表名) -> User.php  User(类名)
        #   imooc_user_info(表名) ->UserInfo.php UserInfo(类名)
        public function getSexAttr($val){
            switch ($val) {
                case 2:
                    return "女";
                    break;
                case 1:
                    return "男";
                    break;
                default :
                    return '未知';
                    break;
            }
        }

        #写入md5,应用于密码的存储
        public function setNickNameAttr($val,$data){ //这里根据函数名去对应数据库字段,语句格式固定必须set对应字段Attr,否则失效
            return md5($val.$data['num']);  //这样加密增加一点点破解难度
//            return md5($val);
        }

        #修改器,配合protected数据或者controller传来数据
        public function setTimeAttr(){
            return time();
        }

        public function setTimeInsertAttr(){
            return time();
        }
        public function setTimeUpdateAttr(){
            return time();
        }
    }