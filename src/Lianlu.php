<?php
namespace ozings;

use GuzzleHttp\Client;

class Lianlu
{
    public $signName = '早职到';
    public $template = '【signName】欢迎使用product，您的验证码为：code';
    private $client;
	
	public function __construct(Client $client)
    {
        $this->client = $client;
    }
    
    /**
     * 发送
     * @param $mobile
     * @param $templateParam
     * @param $template
     * @return array
     */
    public function send($mobile,$templateParam,$template = '', $signName = '')
    {
        if ($template) {
			$this->template = $template;
		}
		if ($signName) {
			$this->signName = $signName;
		}
		$this->template = str_replace('【signName】','【'.$this->signName.'】',$this->template);
		
		$keys = array_keys($templateParam);
        $content = str_replace($keys,$templateParam,$this->template);
        $url = 'http://lianluxinxi.com/sms.aspx?dataType=json&action=send&userid='.config('sms.lluserid').'&account='.config('sms.llaccount').'&password='.config('sms.llpwd').'&mobile='.$mobile.'&content='.$content.'&sendTime=&extno=';
        
		$result = $this->client->request('POST', $url);

        $result = json_decode($result,true);
        if ($result['returnstatus'] == 'Success') {
            $result['Message'] = 'OK';
        } else {
            $result['Message'] = 'Faild';
        }
        return $result;
    }
}
