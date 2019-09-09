<?php
namespace ozings;

use app\common\exception\ApiException;
use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

class Alidayu
{

    /**
     * 发送短信
     * @param $mobile string 手机号
     * @param $TemplateParam  json 模板参数
     */
    public function send($mobile, $TemplateParam, $templateCode = '', $signName = '')
    {
        // Download：https://github.com/aliyun/openapi-sdk-php-client
        // Usage：https://github.com/aliyun/openapi-sdk-php-client/blob/master/README-CN.md
        
        AlibabaCloud::accessKeyClient(config('sms.accessKeyId'), config('sms.accessSecret'))
                                ->regionId('cn-hangzhou') // replace regionId as you need
                                ->asGlobalClient();
        try {
            $result = AlibabaCloud::rpcRequest()
                                ->product('Dysmsapi')
                                // ->scheme('https') // https | http
                                ->version('2017-05-25')
                                ->action('SendSms')
                                ->method('POST')
                                ->options([
                                            'query' => [
                                                'PhoneNumbers' => $mobile,
                                                'SignName' => $signName,
                                                'TemplateCode' => $templateCode,
                                                'TemplateParam' => json_encode($TemplateParam),
                                            ],
                                        ])
                                ->request();
            return $result->toArray();
        } catch (ClientException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
        } catch (ServerException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
        }
    }
}
