<?php
class Center_m extends CI_Model {

    public function __construct(){
        $this->rdb = $this->load->database('rdb',TRUE);
        $this->wdb = $this->load->database('wdb',TRUE);
    }
    //生成6位随机数
    public function randStr() { 
        $chars='0123456789';
        mt_srand((double)microtime()*1000000*getmypid()); 
        $password="";
        while(strlen($password)<6)
            $password.=substr($chars,(mt_rand()%strlen($chars)),1);
        return $password;
    }     
    //general
    public function getId($scaleout){//扩展code为3~9位数字表示唯一业务对象
        usleep(200);  
        $dt=new DateTime();
        list($usec, $sec) = explode(" ", microtime()); 
        $msec=round($usec*10000);  
        $this->load->helper('string');
        $tempid=$dt->format('YmdHis').$msec;//18位时间戳代表的字符串
        $result=$tempid.$scaleout;
        return $result;//唯一编码
    }
    //产生日志
    public function gen_userlog($id,$content){
        $dt=new DateTime();
        $data = array(
            'id' => $this->getId('ULOG'),
            'user_id' => $id,
            'content' => $content,
            'createdt' => $dt->format('Y-m-d H:i:s'),
        );
        $this->wdb->insert('cen_userlog', $data);
    }        
    public function gen_stafflog($id,$content){
        $dt=new DateTime();
        $data = array(
            'id' => $this->getId('ULOG'),
            'staff_id' => $id,
            'content' => $content,
            'createdt' => $dt->format('Y-m-d H:i:s'),
        );
        $this->wdb->insert('cen_stafflog', $data);
    }        
    public function add_blankrecord($tname){
        $resultno=1;
        
        $pdata=array(
            'name'=>'******',
        );
        $this->wdb->insert('cen_'.$tname,$pdata);

        return $resultno;
    }
    public function delete_record($id,$tname){
        $resultno=1;
        
        $sql='delete from cen_'.$tname.' where id = '.$id;
        $this->wdb->query($sql);

        return $resultno;
    }
    public function send_email($userid,$email,$titile,$content){//通用发邮件
        $resultno=1;
        $dt=new DateTime();        
        $this->load->library('email');
        $this->email->set_newline("\r\n");

        $this->email->from('output@belstar.com.cn','百星云输出');
        $this->email->to($email);
        $this->email->subject($titile);
        $this->email->message($content);
        $this->email->send();

        $this->gen_userlog($userid,'发送邮件：'.$email);

        return $resultno;
    }
    public function get_staff($accountno){
        $sql='SELECT * FROM cen_staff where isactive=1 ';
        $sql=$sql.' and (accountno = ? or email = ?) ';
        $query=$this->rdb->query($sql,array($accountno,$accountno));
        return $query->row_array();
    }
    public function get_user($accountno){
        $sql='SELECT * FROM cen_user where isactive=1 ';
        $sql=$sql.' and (accountno = ? or email = ?) ';
        $query=$this->rdb->query($sql,array($accountno,$accountno));
        return $query->row_array();
    }
    public function sendstaffemail($email){
        $resultno=1;
        $dt=new DateTime();
        
        $this->load->library('email');
        $this->email->set_newline("\r\n");

        $sql='SELECT * FROM cen_staff where email = ? ';
        $query=$this->rdb->query($sql,array($email));
        $staff=$query->row_array();
        if($staff != null){//老用户，重置密码的邮件
            $resetdt=new DateTime();
            $resetdt->modify("-60 minute");
            
            $sql='SELECT * FROM cen_staff where id='.$staff['id'];
            $sql=$sql.' and resetpswddt > ? ';
            $query=$this->rdb->query($sql,array($resetdt->format('Y-m-d H:i:s')));
            $staff0=$query->row_array();
            if($staff0 != null)$resultno=2;
            else{
                $rcode=$this->randStr();
                $this->wdb->set('resetpswdcode',$rcode);
                $this->wdb->set('resetpswddt',$dt->format('Y-m-d H:i:s'));
                $this->wdb->where('id',$staff['id']);
                $this->wdb->update('cen_staff');
                
                $this->email->from('output@belstar.com.cn','百星云输出');
                $this->email->to($email);
                $this->email->subject('百星云输出 重置密码验证邮件');
                $this->email->message('您的验证码为：'.$rcode.'，请尽快重置密码！');
                $this->email->send();
                $this->gen_stafflog($staff['id'],'发送重置密码邮件');
            }
        }
        return $resultno;
    }
    public function setstaffpassword($email,$yzm,$password){
        $resultno=1;
        $dt=new DateTime();
        
        $sql='SELECT * FROM cen_staff where email = ? and resetpswdcode = ?';
        $query=$this->rdb->query($sql,array($email,$yzm));
        $staff=$query->row_array();
        if($staff!=null){//找到用户
            $this->wdb->set('password',password_hash($password,PASSWORD_DEFAULT));
            $this->wdb->set('resetpswdcode',password_hash($yzm,PASSWORD_DEFAULT));
            $this->wdb->set('resetpswddt',$dt->format('Y-m-d H:i:s'));
            $this->wdb->where('id',$staff['id']);
            $this->wdb->update('cen_staff');

            $this->gen_stafflog($staff['id'],'重置密码');
        }else{
            $resultno=2;//验证码错误
        }
        return $resultno;
    }
    public function get_smenu($staffid){//员工访问菜单
        $sql = 'SELECT * FROM cen_sactree where id in (SELECT sactree_id FROM cen_staffsactree where staff_id="'.$staffid.'")';
        $query=$this->rdb->query($sql);
        $dmenu=$query->result_array();
        $ids='1=1';
        foreach($dmenu as $item){
            $ids=$ids.' or '.'(leftcode<='.$item['leftcode'].' and rightcode>='.$item['rightcode'].')';
        }
        $sql = 'SELECT * FROM cen_sactree where formenu=1 and ('.$ids.') order by leftcode asc';
        $query=$this->rdb->query($sql);

        return $query->result_array();
    }
    public function get_sactree(){//员工访问功能树
        $sql = 'SELECT * FROM cen_sactree order by leftcode asc';
        $query=$this->rdb->query($sql);

        return $query->result_array();
    }
    public function save_sactree($id,$name,$action,$formenu,$forentry){
        $resultno=1;
        $this->wdb->set('name',$name);
        $this->wdb->set('action',$action);
        $this->wdb->set('formenu',$formenu);
        $this->wdb->set('forentry',$forentry);
        $this->wdb->where('id',$id);
        $this->wdb->update('cen_sactree');

        return $resultno;
    }
    public function create_sactree($leftcode,$rightcode,$layer){
        $pdata=array(
            'name'=>'******',
            'leftcode'=>$leftcode,
            'rightcode'=>$rightcode,
            'layer'=>$layer,
        );
        $this->wdb->insert('cen_sactree',$pdata);
        return $this->wdb->insert_id();
    }
    public function addson_sactree($id){
        $resultno=1;
        
        $sql = 'SELECT * FROM cen_sactree where id="'.$id.'"';
        $query=$this->rdb->query($sql);
        $item=$query->row_array();

        $prc=$item['rightcode'];
        $newlc=$prc;
        $newrc=$prc+1;
        $newlayer=$item['layer']+1;
    
        $this->wdb->trans_start();

        $sql='update cen_sactree o set o.leftcode=o.leftcode+2 where o.leftcode > '.$prc;
        $this->wdb->query($sql);
        $sql='update cen_sactree o set o.rightcode=o.rightcode+2 where o.rightcode >= '.$prc;
        $this->wdb->query($sql);
        $newid=$this->create_sactree($newlc,$newrc,$newlayer);

        $this->wdb->trans_complete();
        if ($this->wdb->trans_status() === FALSE){
            log_message('error',':新建Sactree失败:');
        }

        return $resultno;
    }
    public function addbrother_sactree($id){
        $resultno=1;
        
        $sql = 'SELECT * FROM cen_sactree where id="'.$id.'"';
        $query=$this->rdb->query($sql);
        $item=$query->row_array();

        $prc=$item['rightcode'];
        $newlc=$item['rightcode']+1;
        $newrc=$newlc+1;
        $newlayer=$item['layer'];
        if($newlayer==0) return 0;
    
        $this->wdb->trans_start();

        $sql='update cen_sactree o set o.leftcode=o.leftcode+2 where o.leftcode >= '.$newlc;
        $this->wdb->query($sql);
        $sql='update cen_sactree o set o.rightcode=o.rightcode+2 where o.rightcode >= '.$newlc;
        $this->wdb->query($sql);
        $newid=$this->create_sactree($newlc,$newrc,$newlayer);

        $this->wdb->trans_complete();
        if ($this->wdb->trans_status() === FALSE){
            log_message('error',':新建Sactree失败:');
        }

        return $resultno;
    }
    public function move_sactree($id,$samelayer){
        $resultno=1;
        
        $sql = 'SELECT * FROM cen_sactree where id="'.$id.'"';
        $query=$this->rdb->query($sql);
        $item=$query->row_array();
    
        $lc=$item['leftcode'];
        $rc=$item['rightcode'];
        $num=$rc-$lc+1;//占用的值空间
        $layer=$item['layer'];
        if($layer==0)return 0;       
        $layerstr='';
        if($samelayer=='1'){
            $layerstr=' and layer='.$layer;
        }

        $sql = 'SELECT * FROM cen_sactree where layer>0 and leftcode < '.$lc.$layerstr.' order by leftcode desc ';
        $query=$this->rdb->query($sql);
        $items=$query->result_array();
        if(empty($items)) return 0;//空，返回
        $titem=current($items);
        $tlc=$titem['leftcode'];
        $trc=$titem['rightcode'];
        $tlayer=$titem['layer'];
        $difflayer=$layer-$tlayer;
        if($tlayer==0)return 0;    

        $this->wdb->trans_start();

        //插空
        $sql='update cen_sactree o set o.leftcode=o.leftcode+'.$num.' where o.leftcode >= '.$tlc;
        $this->wdb->query($sql);
        $sql='update cen_sactree o set o.rightcode=o.rightcode+'.$num.' where o.rightcode >= '.$tlc;
        $this->wdb->query($sql);
        //移动
        $sql='update cen_sactree o set o.leftcode=o.leftcode-'.($lc-$tlc+$num).', o.rightcode=o.rightcode-'.($lc-$tlc+$num).' , o.layer=layer-'.$difflayer.' where o.leftcode >= '.($lc+$num).' and o.rightcode <='.($rc+$num);
        $this->wdb->query($sql);
        //收缩
        $sql='update cen_sactree o set o.leftcode=o.leftcode-'.$num.' where o.leftcode >= '.($lc+$num);
        $this->wdb->query($sql);
        $sql='update cen_sactree o set o.rightcode=o.rightcode-'.$num.' where o.rightcode >= '.($rc+$num);
        $this->wdb->query($sql);

        $this->wdb->trans_complete();
        if ($this->wdb->trans_status() === FALSE){
            log_message('error',':移动Sactree失败:');
        }

        return $resultno;
    }
    public function delete_sactree($id){
        $resultno=1;
        
        $sql = 'SELECT * FROM cen_sactree where id="'.$id.'"';
        $query=$this->rdb->query($sql);
        $item=$query->row_array();
        $layer=$item['layer'];
        if($layer==0) return 0;

        $lc=$item['leftcode'];
        $rc=$item['rightcode'];
        $num=$rc-$lc+1;
        
        //检查删除节点是否已经被用到授权中
        $sql = 'SELECT * FROM cen_staffsactree s where s.sactree_id in (select p.id FROM cen_sactree p where p.leftcode >= '.$lc.' and p.rightcode<='.$rc.')';
        $query=$this->rdb->query($sql);
        $others=$query->result_array();
        if(!empty($others)) return 0;//非空，返回
        
        $this->wdb->trans_start();

        //删除节点
        $sql='delete from cen_sactree where leftcode >= '.$lc.' and rightcode<='.$rc;
        $this->wdb->query($sql);
        //收缩节点
        $sql='update cen_sactree o set o.leftcode=o.leftcode-'.$num.' where o.leftcode > '.$lc;
        $this->wdb->query($sql);
        $sql='update cen_sactree o set o.rightcode=o.rightcode-'.$num.' where o.rightcode > '.$rc;
        $this->wdb->query($sql);
   
        $this->wdb->trans_complete();
        if ($this->wdb->trans_status() === FALSE){
            log_message('error',':删除Sactree失败:');
        }

        return $resultno;
    }
    //基础配置微服务
    public function get_msbases(){
        $sql = 'SELECT * FROM cen_msbase';
        $query=$this->rdb->query($sql);

        return $query->result_array();
    }
    public function save_msbase($id,$name,$url,$port,$user,$pass){
        $resultno=1;
        $this->wdb->set('name',$name);
        $this->wdb->set('url',$url);
        $this->wdb->set('port',$port);
        $this->wdb->set('user',$user);
        $this->wdb->set('pass',$pass);
        $this->wdb->where('id',$id);
        $this->wdb->update('cen_msbase');

        return $resultno;
    }
    //委外客户
    public function get_customers(){
        $sql = 'SELECT * FROM cen_customer';
        $query=$this->rdb->query($sql);

        return $query->result_array();
    }
    public function save_customer($id,$name,$code,$memo,$scaleout,$isactive,$msbase_id){
        $resultno=1;
        $this->wdb->set('name',$name);
        $this->wdb->set('code',$code);
        $this->wdb->set('memo',$memo);
        $this->wdb->set('scaleout',$scaleout);
        $this->wdb->set('isactive',$isactive);
        $this->wdb->set('msbase_id',$msbase_id);
        $this->wdb->where('id',$id);
        $this->wdb->update('cen_customer');

        return $resultno;
    }
    //制作中心
    public function get_opcenters(){
        $sql = 'SELECT * FROM cen_opcenter';
        $query=$this->rdb->query($sql);

        return $query->result_array();
    }
    public function save_opcenter($id,$name,$code,$memo,$isvirtual,$isactive,$postcodeprefix,$addressee,$postcode,$address,$email,$tel,$mobile){
        $resultno=1;
        $this->wdb->set('name',$name);
        $this->wdb->set('code',$code);
        $this->wdb->set('memo',$memo);
        $this->wdb->set('isvirtual',$isvirtual);
        $this->wdb->set('isactive',$isactive);
        $this->wdb->set('postcodeprefix',$postcodeprefix);
        $this->wdb->set('addressee',$addressee);
        $this->wdb->set('postcode',$postcode);
        $this->wdb->set('address',$address);
        $this->wdb->set('email',$email);
        $this->wdb->set('tel',$tel);
        $this->wdb->set('mobile',$mobile);
        $this->wdb->where('id',$id);
        $this->wdb->update('cen_opcenter');

        return $resultno;
    }
    public function get_staffs(){
        $sql = 'SELECT * FROM cen_staff';
        $query=$this->rdb->query($sql);

        return $query->result_array();
    }
    public function save_staff($id,$name,$accountno,$email,$mobile,$isactive,$opcenter_id){
        $resultno=1;
        $this->wdb->set('name',$name);
        $this->wdb->set('accountno',$accountno);
        $this->wdb->set('email',$email);
        $this->wdb->set('mobile',$mobile);
        $this->wdb->set('isactive',$isactive);
        $this->wdb->set('opcenter_id',$opcenter_id);
        $this->wdb->where('id',$id);
        $this->wdb->update('cen_staff');

        return $resultno;
    }




    public function backup_db(){
        $resultno=1;
        $dt=new DateTime();
        $filename=$dt->format('YmdHis');
        if(strlen($filename)>2){
            exec('mysqldump -ucenteruser -pcenterpswd --default-character-set=utf8 op_center > '.$filename.'.sql');
        }

        return $resultno;
    }
    public function restore_db($filename){
        $resultno=1;
        if(strlen($filename)>2){
            exec('mysql -ucenteruser -pcenterpswd op_center < '.$filename.'.sql');
        }

        return $resultno;
    }
  
}