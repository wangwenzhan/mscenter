<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Center extends CI_Migration {
    public function up()
    {
        //userlog
        $this->dbforge->add_field(array(
            'id'=>array('type' => 'varchar','constraint' => 32),
            'user_id'=>array('type' => 'bigint','constraint' => 20),
            'content'=>array('type' => 'varchar','constraint' => 128,'default'=>''),//操作的描述
            'status'=>array('type' => 'int','constraint' => 11,'default'=>1),//1成功；2失败
            'createdt'=>array('type' => 'datetime','null' => true),
        ));
        $this->dbforge->add_key('id',true);
        $this->dbforge->create_table('cen_userlog');
        //stafflog
        $this->dbforge->add_field(array(
            'id'=>array('type' => 'varchar','constraint' => 32),
            'staff_id'=>array('type' => 'bigint','constraint' => 20),
            'content'=>array('type' => 'varchar','constraint' => 128,'default'=>''),//操作的描述
            'status'=>array('type' => 'int','constraint' => 11,'default'=>1),//1成功；2失败
            'createdt'=>array('type' => 'datetime','null' => true),
        ));
        $this->dbforge->add_key('id',true);
        $this->dbforge->create_table('cen_stafflog');
        //Base微服务
        $this->dbforge->add_field(array(
            'id'=>array('type' => 'int','constraint' => 11,'auto_increment' => true),
            'name'=>array('type' => 'varchar','constraint' => 32,'default'=>''),
            'url'=>array('type' => 'varchar','constraint' => 128,'default'=>'192.168.0.1'),
            'port'=>array('type' => 'int','constraint' => 11,'default'=>'80'),
            'user'=>array('type' => 'varchar','constraint' => 32,'default'=>''),
            'pass'=>array('type' => 'varchar','constraint' => 32,'default'=>''),
        ));
        $this->dbforge->add_key('id',true);
        $this->dbforge->create_table('cen_msbase');

        //Customer
        $this->dbforge->add_field(array(
            'id'=>array('type' => 'bigint','constraint' => 20,'auto_increment' => true),
            'code'=>array('type' => 'varchar','constraint' => 32,'default'=>''),
            'name'=>array('type' => 'varchar','constraint' => 64,'default'=>''),
            'memo'=>array('type' => 'varchar','constraint' => 256,'default'=>''),
            'isactive'=>array('type' => 'int','constraint' => 11,'default'=>1),

            'msbase_id'=>array('type' => 'bigint','constraint' => 20,'default'=>0),
            'scaleout'=>array('type' => 'varchar','constraint' => 22,'default'=>''),
        ));
        $this->dbforge->add_key('id',true);
        $this->dbforge->create_table('cen_customer');
        //Opcenter
        $this->dbforge->add_field(array(
            'id'=>array('type' => 'bigint','constraint' => 20,'auto_increment' => true),
            'code'=>array('type' => 'varchar','constraint' => 32,'default'=>''),
            'name'=>array('type' => 'varchar','constraint' => 64,'default'=>''),
            'isvirtual'=>array('type' => 'int','constraint' => 11,'default'=>0),//0实际的;1虚拟的
            'isactive'=>array('type' => 'int','constraint' => 11,'default'=>1),
            'memo'=>array('type' => 'varchar','constraint' => 256,'default'=>''),

            'postcodeprefix'=>array('type' => 'varchar','constraint' => 255,'default'=>''),//制作中心所在地本地邮编前缀
            'addressee'=>array('type' => 'varchar','constraint' => 32,'default'=>''),
            'postcode'=>array('type' => 'varchar','constraint' => 16,'default'=>''),
            'address'=>array('type' => 'varchar','constraint' => 128,'default'=>''),
            'email'=>array('type' => 'varchar','constraint' => 64,'default'=>''),
            'tel'=>array('type' => 'varchar','constraint' => 22,'default'=>''),
            'mobile'=>array('type' => 'varchar','constraint' => 22,'default'=>''),
        ));
        $this->dbforge->add_key('id',true);
        $this->dbforge->add_key('code');
        $this->dbforge->create_table('cen_opcenter');
        //Site
        $this->dbforge->add_field(array(
            'id'=>array('type' => 'bigint','constraint' => 20,'auto_increment' => true),
            'code'=>array('type' => 'varchar','constraint' => 32,'default'=>''),
            'name'=>array('type' => 'varchar','constraint' => 64,'default'=>''),
            'memo'=>array('type' => 'varchar','constraint' => 256,'default'=>''),
            'ipaddress'=>array('type' => 'varchar','constraint' => 32,'default'=>'192.168.1.10'),
            'aliasname'=>array('type' => 'varchar','constraint' => 32,'default'=>'PrintBJ01'),
            'accountno'=>array('type' => 'varchar','constraint' => 64,'default'=>''),
            'password'=>array('type' => 'varchar','constraint' => 255,'default'=>''),
            'isactive'=>array('type' => 'int','constraint' => 11,'default'=>1),
        ));
        $this->dbforge->add_key('id',true);
        $this->dbforge->create_table('cen_site');

        //User AccessTree访问功能树
        $this->dbforge->add_field(array(
            'id'=>array('type' => 'bigint','constraint' => 20,'auto_increment' => true),
            'name'=>array('type' => 'varchar','constraint' => 32,'default'=>''),
            'leftcode'=>array('type' => 'int','constraint' => 11,'default'=>0),//左编码
            'rightcode'=>array('type' => 'int','constraint' => 11,'default'=>0),//右编码
            'layer'=>array('type' => 'int','constraint' => 11,'default'=>0),//层级：0...n;0为根目录，每个product建立时自动建立根节点
            'formenu'=>array('type' => 'int','constraint' => 11,'default'=>1),//菜单显示项: 0不是，1是
            'forentry'=>array('type' => 'int','constraint' => 11,'default'=>1),//入口项：0不是，1是；可以通过菜单访问
            'action'=>array('type' => 'varchar','constraint' => 128,'default'=>''),//入口动作如：center/index
        ));
        $this->dbforge->add_key('id',true);
        $this->dbforge->create_table('cen_uactree');
        //User
        $this->dbforge->add_field(array(
            'id'=>array('type' => 'bigint','constraint' => 20,'auto_increment' => true),
            'accountno'=>array('type' => 'varchar','constraint' => 22,'default'=>''),
            'name'=>array('type' => 'varchar','constraint' => 64,'default'=>''),
            'email'=>array('type' => 'varchar','constraint' => 64,'default'=>''),
            'mobile'=>array('type' => 'varchar','constraint' => 22,'default'=>''),
            'isactive'=>array('type' => 'int','constraint' => 11,'default'=>1),
            'customer_id'=>array('type' => 'bigint','constraint' => 20,'default'=>0),//所属客户
            'password'=>array('type' => 'varchar','constraint' => 255,'default'=>''),
            'resetpswdcode'=>array('type' => 'varchar','constraint' => 255,'default'=>''),
            'resetpswddt'=>array('type' => 'datetime','null' => true),
        ));
        $this->dbforge->add_key('id',true);
        $this->dbforge->add_key('accountno');
        $this->dbforge->add_key('email');
        $this->dbforge->create_table('cen_user');
        //User关联功能
        $this->dbforge->add_field(array(
            'id'=>array('type' => 'bigint','constraint' => 20,'auto_increment' => true),
            'user_id'=>array('type' => 'bigint','constraint' => 20,'default'=>0),//职员
            'uactree_id'=>array('type' => 'bigint','constraint' => 20,'default'=>0),//具体功能
        ));
        $this->dbforge->add_key('id',true);
        $this->dbforge->create_table('cen_useruactree');
        
        //Staff AccessTree访问功能树
        $this->dbforge->add_field(array(
            'id'=>array('type' => 'bigint','constraint' => 20,'auto_increment' => true),
            'name'=>array('type' => 'varchar','constraint' => 32,'default'=>''),
            'leftcode'=>array('type' => 'int','constraint' => 11,'default'=>0),//左编码
            'rightcode'=>array('type' => 'int','constraint' => 11,'default'=>0),//右编码
            'layer'=>array('type' => 'int','constraint' => 11,'default'=>0),//层级：0...n;0为根目录，每个product建立时自动建立根节点
            'formenu'=>array('type' => 'int','constraint' => 11,'default'=>1),//菜单显示项: 0不是，1是
            'forentry'=>array('type' => 'int','constraint' => 11,'default'=>0),//入口项：0不是，1是；可以通过菜单访问
            'action'=>array('type' => 'varchar','constraint' => 128,'default'=>''),//入口动作如：center/index
        ));
        $this->dbforge->add_key('id',true);
        $this->dbforge->create_table('cen_sactree');
        //Staff
        $this->dbforge->add_field(array(
            'id'=>array('type' => 'bigint','constraint' => 20,'auto_increment' => true),
            'accountno'=>array('type' => 'varchar','constraint' => 22,'default'=>''),
            'name'=>array('type' => 'varchar','constraint' => 64,'default'=>''),
            'email'=>array('type' => 'varchar','constraint' => 64,'default'=>''),
            'mobile'=>array('type' => 'varchar','constraint' => 22,'default'=>''),
            'isactive'=>array('type' => 'int','constraint' => 11,'default'=>1),
            'opcenter_id'=>array('type' => 'bigint','constraint' => 20,'default'=>0),//所属制作中心；可为NULL，0为未分配
            'password'=>array('type' => 'varchar','constraint' => 255,'default'=>''),
            'resetpswdcode'=>array('type' => 'varchar','constraint' => 255,'default'=>''),
            'resetpswddt'=>array('type' => 'datetime','null' => true),
        ));
        $this->dbforge->add_key('id',true);
        $this->dbforge->add_key('accountno');
        $this->dbforge->add_key('email');
        $this->dbforge->create_table('cen_staff');
        //Staff关联功能
        $this->dbforge->add_field(array(
            'id'=>array('type' => 'bigint','constraint' => 20,'auto_increment' => true),
            'staff_id'=>array('type' => 'bigint','constraint' => 20,'default'=>0),//职员
            'sactree_id'=>array('type' => 'bigint','constraint' => 20,'default'=>0),//具体功能
        ));
        $this->dbforge->add_key('id',true);
        $this->dbforge->create_table('cen_staffsactree');

        
        //Init Center data
        $this->db->insert('cen_msbase',array('id'=>'1','name'=>'微服务一号','url'=>'localhost/output/msbase/base','port'=>'88','user'=>'belstar','pass'=>'20161122'));
        $this->db->insert('cen_customer',array('id'=>'1','code'=>'NCI','name'=>'新华保险','msbase_id'=>'1','scaleout'=>'NCI'));
        $this->db->insert('cen_opcenter',array('id'=>'1','code'=>'BEIJING01','name'=>'北京制作中心','memo'=>''));
        $this->db->insert('cen_site',array('id'=>'1','code'=>'TestSite','name'=>'TestSiteName','accountno'=>'1111','password'=>'$2y$10$si0ccKja/adSD2.W8MtCNeedyS3wYdoSxXr9ZVt1/fv6KQO/jWUwy',));
		//staff
		$this->db->insert('cen_staff',array('id'=>'1','accountno'=>'1111','name'=>'admin','opcenter_id'=>'1','password'=>'$2y$10$si0ccKja/adSD2.W8MtCNeedyS3wYdoSxXr9ZVt1/fv6KQO/jWUwy','email'=>'outputadmin@belstar.com.cn',));
		$this->db->insert('cen_staff',array('id'=>'2','accountno'=>'2222','name'=>'demo','opcenter_id'=>'1','password'=>'$2y$10$si0ccKja/adSD2.W8MtCNeedyS3wYdoSxXr9ZVt1/fv6KQO/jWUwy','email'=>'outputdemo@belstar.com.cn',));
		//sactree
		$this->db->insert('cen_sactree',array('id'=>'1','name'=>'全部功能','leftcode'=>'1','rightcode'=>'16','layer'=>'0',));
		$this->db->insert('cen_sactree',array('id'=>'2','name'=>'基础配置微服务','action'=>'center/msbase','leftcode'=>'2','rightcode'=>'3','layer'=>'0',));
		$this->db->insert('cen_sactree',array('id'=>'3','name'=>'生产处理微服务','action'=>'belstar/msproduce','leftcode'=>'4','rightcode'=>'5','layer'=>'0',));
		$this->db->insert('cen_sactree',array('id'=>'4','name'=>'职员访问功能','action'=>'center/sactree','leftcode'=>'6','rightcode'=>'7','layer'=>'0',));
		$this->db->insert('cen_sactree',array('id'=>'5','name'=>'制作中心','action'=>'center/opcenter','leftcode'=>'8','rightcode'=>'9','layer'=>'0',));
		$this->db->insert('cen_sactree',array('id'=>'6','name'=>'百星职员','action'=>'center/staff','leftcode'=>'10','rightcode'=>'11','layer'=>'0',));
		$this->db->insert('cen_sactree',array('id'=>'7','name'=>'客户访问功能','action'=>'center/uactree','leftcode'=>'12','rightcode'=>'13','layer'=>'0',));
		$this->db->insert('cen_sactree',array('id'=>'8','name'=>'委外客户','action'=>'center/customer','leftcode'=>'14','rightcode'=>'15','layer'=>'0',));
		//staff sactree
		$this->db->insert('cen_staffsactree',array('id'=>'1','staff_id'=>'1','sactree_id'=>'1',));
		$this->db->insert('cen_staffsactree',array('id'=>'2','staff_id'=>'2','sactree_id'=>'1',));


    }
    
    public function down()
    {
        $this->dbforge->drop_table('cen_staffsactree');
        $this->dbforge->drop_table('cen_staff');
        $this->dbforge->drop_table('cen_sactree');

        $this->dbforge->drop_table('cen_useruactree');
        $this->dbforge->drop_table('cen_user');
        $this->dbforge->drop_table('cen_uactree');

        $this->dbforge->drop_table('cen_site');
        $this->dbforge->drop_table('cen_opcenter');
        $this->dbforge->drop_table('cen_customer');

        $this->dbforge->drop_table('cen_msbase');
        $this->dbforge->drop_table('cen_stafflog');
        $this->dbforge->drop_table('cen_userlog');
    }
    
}