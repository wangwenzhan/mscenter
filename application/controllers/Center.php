<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Center extends CI_Controller {

    public function index()
    {
        if (($_SERVER['PHP_AUTH_USER'] == 'belstar') && ($_SERVER['PHP_AUTH_PW'] == '20161122')) {
            $this->load->library('xmlrpc');
            $this->load->library('xmlrpcs');

            $config['functions']['get_staff'] = array('function' => 'Center.get_staff');
            $config['functions']['sendstaffemail'] = array('function' => 'Center.sendstaffemail');
            $config['functions']['setstaffpassword'] = array('function' => 'Center.setstaffpassword');
            $config['functions']['get_smenu'] = array('function' => 'Center.get_smenu');
            $config['functions']['get_sactree'] = array('function' => 'Center.get_sactree');
            $config['functions']['save_sactree'] = array('function' => 'Center.save_sactree');
            $config['functions']['addson_sactree'] = array('function' => 'Center.addson_sactree');
            $config['functions']['addbrother_sactree'] = array('function' => 'Center.addbrother_sactree');
            $config['functions']['move_sactree'] = array('function' => 'Center.move_sactree');
            $config['functions']['delete_sactree'] = array('function' => 'Center.delete_sactree');

            $config['functions']['add_blankrecord'] = array('function' => 'Center.add_blankrecord');
            $config['functions']['delete_record'] = array('function' => 'Center.delete_record');

            $config['functions']['get_msbases'] = array('function' => 'Center.get_msbases');
            $config['functions']['save_msbase'] = array('function' => 'Center.save_msbase');
            $config['functions']['get_customers'] = array('function' => 'Center.get_customers');
            $config['functions']['save_customer'] = array('function' => 'Center.save_customer');
            $config['functions']['get_opcenters'] = array('function' => 'Center.get_opcenters');
            $config['functions']['save_opcenter'] = array('function' => 'Center.save_opcenter');
            $config['functions']['get_staffs'] = array('function' => 'Center.get_staffs');
            $config['functions']['save_staff'] = array('function' => 'Center.save_staff');

            $this->xmlrpcs->initialize($config);
            $this->xmlrpcs->serve();
        } 
/*
        else {
            header("WWW-Authenticate: Basic realm=\"My Private Area\"");
            header("HTTP/1.0 401 Unauthorized");
            print "You need valid credentials to get access!\n";
            exit;
        }
*/
    }
    public function get_staff($request){
        $this->load->model('center_m');
        $paras = $request->output_parameters();
        $result=$this->center_m->get_staff($paras[0]);
        $response = array(htmlspecialchars(json_encode($result)));
        return $this->xmlrpc->send_response($response);
    }
    public function sendstaffemail($request){
        $this->load->model('center_m');
        $paras = $request->output_parameters();
        $result=$this->center_m->sendstaffemail($paras[0]);
        $response = array(htmlspecialchars(json_encode($result)));
        return $this->xmlrpc->send_response($response);
    }
    public function setstaffpassword($request){
        $this->load->model('center_m');
        $paras = $request->output_parameters();
        $result=$this->center_m->setstaffpassword($paras[0],$paras[1],$paras[2]);
        $response = array(htmlspecialchars(json_encode($result)));
        return $this->xmlrpc->send_response($response);
    }
    public function get_smenu($request){
        $this->load->model('center_m');
        $paras = $request->output_parameters();
        $result=$this->center_m->get_smenu($paras[0]);
        $response = array(htmlspecialchars(json_encode($result)));
        return $this->xmlrpc->send_response($response);
    }
    public function get_sactree($request){
        $this->load->model('center_m');
        $paras = $request->output_parameters();
        $result=$this->center_m->get_sactree();
        $response = array(htmlspecialchars(json_encode($result)));
        return $this->xmlrpc->send_response($response);
    }
    public function save_sactree($request){
        $this->load->model('center_m');
        $paras = $request->output_parameters();
        $result=$this->center_m->save_sactree($paras[0],$paras[1],$paras[2],$paras[3],$paras[4]);
        $response = array(htmlspecialchars(json_encode($result)));
        return $this->xmlrpc->send_response($response);
    }
    public function addson_sactree($request){
        $this->load->model('center_m');
        $paras = $request->output_parameters();
        $result=$this->center_m->addson_sactree($paras[0]);
        $response = array(htmlspecialchars(json_encode($result)));
        return $this->xmlrpc->send_response($response);
    }
    public function addbrother_sactree($request){
        $this->load->model('center_m');
        $paras = $request->output_parameters();
        $result=$this->center_m->addbrother_sactree($paras[0]);
        $response = array(htmlspecialchars(json_encode($result)));
        return $this->xmlrpc->send_response($response);
    }
    public function move_sactree($request){
        $this->load->model('center_m');
        $paras = $request->output_parameters();
        $result=$this->center_m->move_sactree($paras[0],$paras[1]);
        $response = array(htmlspecialchars(json_encode($result)));
        return $this->xmlrpc->send_response($response);
    }
    public function delete_sactree($request){
        $this->load->model('center_m');
        $paras = $request->output_parameters();
        $result=$this->center_m->delete_sactree($paras[0]);
        $response = array(htmlspecialchars(json_encode($result)));
        return $this->xmlrpc->send_response($response);
    }

    public function add_blankrecord($request){
        $this->load->model('center_m');
        $paras = $request->output_parameters();
        $result=$this->center_m->add_blankrecord($paras[0]);
        $response = array(htmlspecialchars(json_encode($result)));
        return $this->xmlrpc->send_response($response);
    }
    public function delete_record($request){
        $this->load->model('center_m');
        $paras = $request->output_parameters();
        $result=$this->center_m->delete_record($paras[0],$paras[1]);
        $response = array(htmlspecialchars(json_encode($result)));
        return $this->xmlrpc->send_response($response);
    }

    public function get_msbases($request){
        $this->load->model('center_m');
        $paras = $request->output_parameters();
        $result=$this->center_m->get_msbases();
        $response = array(htmlspecialchars(json_encode($result)));
        return $this->xmlrpc->send_response($response);
    }
    public function save_msbase($request){
        $this->load->model('center_m');
        $paras = $request->output_parameters();
        $result=$this->center_m->save_msbase($paras[0],$paras[1],$paras[2],$paras[3],$paras[4],$paras[5]);
        $response = array(htmlspecialchars(json_encode($result)));
        return $this->xmlrpc->send_response($response);
    }
    public function get_customers($request){
        $this->load->model('center_m');
        $paras = $request->output_parameters();
        $result=$this->center_m->get_customers();
        $response = array(htmlspecialchars(json_encode($result)));
        return $this->xmlrpc->send_response($response);
    }
    public function save_customer($request){
        $this->load->model('center_m');
        $paras = $request->output_parameters();
        $result=$this->center_m->save_customer($paras[0],$paras[1],$paras[2],$paras[3],$paras[4],$paras[5],$paras[6]);
        $response = array(htmlspecialchars(json_encode($result)));
        return $this->xmlrpc->send_response($response);
    }
    public function get_opcenters($request){
        $this->load->model('center_m');
        $paras = $request->output_parameters();
        $result=$this->center_m->get_opcenters();
        $response = array(htmlspecialchars(json_encode($result)));
        return $this->xmlrpc->send_response($response);
    }
    public function save_opcenter($request){
        $this->load->model('center_m');
        $paras = $request->output_parameters();
        $result=$this->center_m->save_opcenter($paras[0],$paras[1],$paras[2],$paras[3],$paras[4],$paras[5],$paras[6],$paras[7],$paras[8],$paras[9],$paras[10],$paras[11],$paras[12]);
        $response = array(htmlspecialchars(json_encode($result)));
        return $this->xmlrpc->send_response($response);
    }
    public function get_staffs($request){
        $this->load->model('center_m');
        $paras = $request->output_parameters();
        $result=$this->center_m->get_staffs();
        $response = array(htmlspecialchars(json_encode($result)));
        return $this->xmlrpc->send_response($response);
    }
    public function save_staff($request){
        $this->load->model('center_m');
        $paras = $request->output_parameters();
        $result=$this->center_m->save_staff($paras[0],$paras[1],$paras[2],$paras[3],$paras[4],$paras[5],$paras[6]);
        $response = array(htmlspecialchars(json_encode($result)));
        return $this->xmlrpc->send_response($response);
    }


    public function migrate()
    {
        $this->load->library('migration');
        if ($this->migration->current() === false){
            show_error($this->migration->error_string());
        }else{
            echo '数据库迁移成功';
        }
    }
    
}
