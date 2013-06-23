<?php

require_once SYSLIB . 'libraries/curl.lib.php';

define('PAY_STATUS_FAIL', 0);
define('PAY_STATUS_WAIT', 1);
define('PAY_STATUS_SUCCESS', 2);

/**
 * 
 * pay for 51
 * @author zhangwy
 *
 */
class payment {
    
    private $pay_conf, $order_info;

    public function __construct() {
        header('Content-type:text/html; charset=utf-8');

        $cfg = include SYSLIB . 'config/pay.php';
        
        $this->pay_conf['mem_id']      = $cfg['mem_id'];
        $this->pay_conf['code']        = $cfg['key'];
        $this->pay_conf['gateway_url'] = $cfg['gateway_url'];
        $this->pay_conf['notify_url']  = $cfg['notify_url'];
        $this->pay_conf['return_url']  = $cfg['return_url'];
        
        unset($cfg);
    }
    
    /**
     * 生成签名数据
     * 
     * @param array $data
     * @return string 
     */
    public function createSign($data){   
        ksort($data);
        reset($data);
        
        $args = '';
        foreach ($data as $key => $value) {
            if ('sign' == $key || '' === $value){
                continue;
            }
            $args .= $key . '=' . $value . '&';
        }
        $args = rtrim($args, '&');

        return md5($args . $this->pay_conf['code']);
    }

    /**
     * 设置订单信息
     * @param unknown_type $order_info
     */
    public function setOrderInfo($order_info) {
        $this->order_info = $order_info;
        return $this;
    }

    /**
     * 获取订单发送准备数据
     *
     * @return 数组
     * */
    public function getPrepareData() {
        
        if (empty($this->order_info)){
            throw new Exception('The order info can not be empty!');
        }
        
        $prepare_data = array();
        
        $prepare_data['pm_id']        = $this->pay_conf['mem_id'];         //合作伙伴ID：
        $prepare_data['notify_url']   = $this->pay_conf['notify_url'];     //通知页面地址：
        $prepare_data['return_url']   = $this->pay_conf['return_url'];     //返回页面地址

        $prepare_data['order_amount'] = $this->order_info['amount'];       //总金额
        $prepare_data['order_id']     = $this->order_info['order_id'];     //订单id
        $prepare_data['channel_name'] = $this->order_info['channel_name']; //充值渠道
        
        $prepare_data['user_ip']      = isset($this->order_info['user_ip'])  ? $this->order_info['user_ip']  : ''; //用户ip
        $prepare_data['payer']        = isset($this->order_info['payer'])    ? $this->order_info['payer']    : ''; //充值人账号
        $prepare_data['receiver']     = isset($this->order_info['receiver']) ? $this->order_info['receiver'] : ''; //受益人账号

        switch ($prepare_data['channel_name']) {
            case 'szx': //神州行
                $prepare_data['cardno']  = $this->order_info['cardno'];
                $prepare_data['cardkey'] = $this->order_info['cardkey'];
                break;
            case 'netbank': //网银
                //银行名字,可选值：1,2,3,4,5,6,7,8,9,11,12,13,14,15,19
                $prepare_data['bank_name'] = $this->order_info['bank_name'];
                break;
            case 'alipay': //支付宝
            case 'cft': //财付通
                break;
            default:
                log_message('error', '充值渠道错误: ' . $prepare_data['order_id']);
                break;
        }

        //签名
        $prepare_data['sign'] = $this->createSign($prepare_data);
        
        return $prepare_data;
    }

    /**
     * 过滤参数
     *
     * @param 数组 $parameter
     * @return 数组
     * */
    private function filterParameter($parameter) {
        $para = array();
        foreach ($parameter as $key => $value) {
            if ('' == $value){
                continue;
            } else {
                $para[$key] = $value;
            } 
        }
        return $para;
    }

    /**
     * 获取发送订单信息的HTML代码
     *
     * @return 字符串
     * */
    public function getHtml($button_attr = '', $formID = '') {
        $prepare_data = $this->getPrepareData();
        
        $str = '<form action="' . $this->pay_conf['gateway_url'] . '" method="POST" id="' . $formID . '" target="_blank">';

        foreach ($prepare_data as $key => $value) {
            $str .= '<input type="hidden" name="' . $key . '" value="' . $value . '" />';
        }

        $str .= '<input type="submit" ' . $button_attr . ' />';
        $str .= '</form>';
        
        return $str;
    }

    /**
     * 发送订单信息获取返回的json数据
     * 为卡类，神州行。
     * @return array 
     */
    public function getJson() {
        
        $prepare_data = $this->getPrepareData();
        
        return json_decode(curl::post($this->pay_conf['gateway_url'], $prepare_data), TRUE);
    }

    /**
     * 接收通知信息
     *
     * @return 数组 返回一个包括 order_id、order_total、pay_status 的数组
     * */
    public function receive($receive_data) {
        
        //过滤数据
        $receive_data = $this->filterParameter($receive_data);
        
        $return_data = array( 'pay_status' => PAY_STATUS_FAIL );
        
        if (empty($receive_data)) {

            $return_data['error_code'] = '51回调充值接口时，返回的参数为空！';
            
        }else{
            
            $sign = $this->createSign($receive_data); //签名
            
            if ($sign != $receive_data['sign']) {

                $return_data['error_code'] = "订单[{$receive_data['order_id']}]签名错误: $sign(本地生成) - (51返回){$receive_data['sign']}";
                
            } elseif (!isset($receive_data['inpour_no'])) {
                
                $return_data['error_code'] = "订单[{$receive_data['order_id']}]所关联的51订单号不能为空！";
                
            } elseif (!isset($receive_data['receiver'])) {
                
                $return_data['error_code'] = "订单[{$receive_data['order_id']}]的用户账号不能为空！";
                
            }else{
                
                $return_data['order_id']    = $receive_data['order_id'];
                $return_data['inpour_no']   = $receive_data['inpour_no'];
                $return_data['receiver']    = $receive_data['receiver'];
                $return_data['payer']       = isset($receive_data['payer'])    ? $receive_data['payer']    : '';
                $return_data['order_total'] = isset($receive_data['pay_cash']) ? $receive_data['pay_cash'] : '';
                $return_data['pay_time']    = isset($receive_data['pay_time']) ? $receive_data['pay_time'] : time();

                switch($receive_data['result']){
                    case 'Y' :
                        $return_data['pay_status'] = PAY_STATUS_SUCCESS; //成功
                        break;
                    case 'N' :
                        $return_data['error_code'] = '支持失败：'.$receive_data['error_code'];
                        break;
                    default :
                        $return_data['error_code'] = '未知结果！';
                        break;
                }
            }
        }
        
        return $return_data;
    }
}

//end payment.lib.php 