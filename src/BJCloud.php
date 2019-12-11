<?php

namespace Jake\Baijiayun;

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class  BJCloud
{
    private $partnerId;

    private $partnerKey;

    private $domain = 'https://api.baijiacloud.com';

    private $timeout = 10;

    private $httpClient;

    /**
     * BJCloud constructor.
     * @param $config
     */
    public function __construct($config)
    {
        if ($config['partner_id'] ?? null) {
            $this->partnerId = $config['partner_id'];
        }

        if ($config['partner_key'] ?? null) {
            $this->partnerKey = $config['partner_key'];
        }

        if ($config['domain'] ?? null) {
            $this->domain = $config['domain'];
        }

        if ($config['timeout'] ?? null) {
            $this->timeout = $config['timeout'];
        }

        $this->httpClient = new Client([
            'base_uri' => $this->domain,
            'timeout' => $this->timeout,
        ]);
    }


    /**
     *
     * 创建房间
     * http://dev.baijiayun.com/wiki/detail/79#h6-8
     * @param string $title 房间标题
     * @param int $startTime 开课时间 10位时间戳
     * @param int $endTime 下课时间 10位时间戳
     * @param array $optionalParams 可选参数
     * @return array|mixed
     * @throws BJCloudException
     */
    public function roomCreate($title, $startTime, $endTime, $optionalParams = [])
    {
        $params = [
            'title' => $title,
            'start_time' => $startTime,
            'end_time' => $endTime,
        ];
        return $this->call('/openapi/room/create', array_merge($params, $optionalParams));
    }

    /**
     *
     * 更新房间
     * http://dev.baijiayun.com/wiki/detail/79#h6-9
     * @param string|int $roomId 房间id
     * @param string $title 标题
     * @param int $startTime
     * @param int $endTime
     * @param array $optionalParams
     * @return array|mixed
     * @throws BJCloudException
     */
    public function roomUpdate($roomId, $title = '', $startTime = 0, $endTime = 0, $optionalParams = [])
    {
        $params = [
            'room_id' => $roomId,
        ];

        if ($title) {
            $params['title'] = $title;
        }

        if ($startTime) {
            $params['start_time'] = $startTime;
        }

        if ($endTime) {
            $params['endTime'] = $endTime;
        }
        return $this->call('/openapi/room/update', array_merge($params, $optionalParams));
    }

    /**
     * 删除房间
     * http://dev.baijiayun.com/wiki/detail/79#h6-10
     * @param string|int $roomId 房间id
     * @return array|mixed
     * @throws BJCloudException
     */
    public function roomDelete($roomId)
    {
        return $this->call('/openapi/room/delete', [
            'room_id' => $roomId,
        ]);
    }

    /**
     * 获取房间信息
     * http://dev.baijiayun.com/wiki/detail/79#h6-11
     * @param string|int $roomId 房间id
     * @return array|mixed
     * @throws BJCloudException
     */
    public function roomInfo($roomId)
    {
        return $this->call('/openapi/room/info', [
            'room_id' => $roomId,
        ]);
    }

    /**
     * 生成参加码
     * http://dev.baijiayun.com/wiki/detail/79#h6-12
     * @param string|int $roomId 房间id
     * @param int $userNumber 合作方账号体系下的用户ID号，必须是数字
     * @param null $userAvatar 用户头像，需要完整的url地址
     * @return array|mixed
     * @throws BJCloudException
     */
    public function roomGetCode($roomId, $userNumber, $userAvatar = null)
    {
        $params = [
            'room_id' => $roomId,
            'user_number' => $userNumber
        ];

        if ($userAvatar) {
            $params['user_avatar'] = $userAvatar;
        }

        return $this->call('/openapi/room/getcode', $params);
    }

    /**
     * 获取用户参加码信息
     * http://dev.baijiayun.com/wiki/detail/79#h6-13
     * @param string $code 学生参加码
     * @return array|mixed
     * @throws BJCloudException
     */
    public function roomGetCodeInfo($code)
    {
        return $this->call('/openapi/room/getCodeInfo', [
            'code' => $code,
        ]);
    }


    /**
     * 获取已生成的参加码列表
     * http://dev.baijiayun.com/wiki/detail/79#h6-14
     * @param string|int $roomId 房间id
     * @param array $optionalParams type 0表示普通参加码 1表示试听参加码; page 页数，参加码数量过多时，可以分多页来获取，每页取limit条。默认值为1
     * @return array|mixed
     * @throws BJCloudException
     */
    public function roomListCode($roomId, $optionalParams = [])
    {
        $params = [
            'room_id' => $roomId,
        ];
        return $this->call('/openapi/room/listcode', array_merge($params, $optionalParams));
    }

    /**
     *  获取房间列表
     * http://dev.baijiayun.com/wiki/detail/79#h6-15
     * @param int $page 页码
     * @param array $optionalParams 可选参数 limit 每页获取的条数，默认值100，最大值不能超过1000 ; product_type 1:教育直播
     * @return array|mixed
     * @throws BJCloudException
     */
    public function roomList($page, $optionalParams = [])
    {
        $params = [
            'page' => $page,
        ];
        return $this->call('/openapi/room/list', array_merge($params, $optionalParams));
    }

    /**
     * 获取直播教室当前上课状态
     * http://dev.baijiayun.com/wiki/detail/79#h6-21
     * @param string|int $roomId 房间id
     * @return array|mixed
     * @throws BJCloudException
     */
    public function liveGetLiveStatus($roomId)
    {
        return $this->call('/openapi/live/getLiveStatus', [
            'room_id' => $roomId,
        ]);
    }

    /**
     * 获取老师是否在教室状态
     * http://dev.baijiayun.com/wiki/detail/79#h6-22
     * @param string|int $roomId 房间id
     * @return array|mixed
     * @throws BJCloudException
     */
    public function liveGetTeacherOnlineStatus($roomId)
    {
        return $this->call('/openapi/live/getTeacherOnlineStatus', [
            'room_id' => $roomId,
        ]);
    }

    /**
     * 获取当前时间教室人数
     * http://dev.baijiayun.com/wiki/detail/79#h6-23
     * @param string|int $roomId 房间id
     * @return array|mixed
     * @throws BJCloudException
     */
    public function liveGetUserCount($roomId)
    {
        return $this->call('/openapi/live/getUserCount', [
            'room_id' => $roomId,
        ]);
    }

    /**
     * 导出教室聊天记录
     * http://dev.baijiayun.com/wiki/detail/79#h6-24
     * @param string|int $roomId 房间id
     * @param string $date 没传递时，长期教室是最近上课的一天，短期教室默认是这次课的所有聊天信息
     * @return array|mixed
     * @throws BJCloudException
     */
    public function roomDataExportChatMsg($roomId, $date = null)
    {
        $params = [
            'room_id' => $roomId,
        ];

        if ($date) {
            $params['date'] = $date;
        }

        return $this->call('/openapi/room_data/exportChatMsg', $params);
    }

    /**
     * 设置教室公告
     * http://dev.baijiayun.com/wiki/detail/79#h6-25
     * @param string|int $roomId 房间id
     * @param string $content 公告信息
     * @return array|mixed
     * @throws BJCloudException
     */
    public function liveSetNotify($roomId, $content)
    {
        $params = [
            'room_id' => $roomId,
            'content' => $content,
        ];
        return $this->call('/openapi/live/setNotify', $params);
    }

    /**
     * 导出直播教室学员观看记录
     * http://dev.baijiayun.com/wiki/detail/79#h6-26
     * @param string|int $roomId 房间id
     * @param array $optionalParams type 可选值 all:所有用户 student:学员 teacher:老师 admin:助教，默认只导出学员观看记录;分页数据;date 查询日期，格式如：2018-03-02
     *
     * @return array|mixed
     * @throws BJCloudException
     */
    public function roomDataExportLiveReport($roomId, $optionalParams = [])
    {
        $params = [
            'room_id' => $roomId,
        ];

        return $this->call('/openapi/room_data/exportLiveReport', array_merge($params, $optionalParams));
    }

    /**
     * 停止直播教室的云端录制
     * http://dev.baijiayun.com/wiki/detail/79#h6-49
     * @param string|int $roomId 房间id
     * @return array|mixed
     * @throws BJCloudException
     */
    public function liveStopCloudRecord($roomId)
    {
        return $this->call('/openapi/live/stopCloudRecord', [
            'room_id' => $roomId,
        ]);
    }


    /**
     * 直播教室的云端录制生成回放
     * http://dev.baijiayun.com/wiki/detail/79#h6-50
     * @param string|int $roomId 房间id
     * @return array|mixed
     * @throws BJCloudException
     */
    public function liveTransCloudRecord($roomId)
    {
        return $this->call('/openapi/live/transCloudRecord', [
            'room_id' => $roomId,
        ]);
    }

    /**
     * 生成用户试听参加码
     * http://dev.baijiayun.com/wiki/detail/79#h6-54
     * @param $roomId
     * @param $userNumbers
     * @param string $userAvatar
     * @param int $auditionLength
     * @return array|mixed
     * @throws BJCloudException
     */
    public function roomGenAuditionCode($roomId, $userNumbers, $userAvatar = '', $auditionLength = null)
    {
        $params = [
            'room_id' => $roomId,
            'userNumbers' => $userNumbers,
        ];

        if ($userAvatar) {
            $params['user_avatar'] = $userAvatar;
        }

        if ($auditionLength) {
            $params['audition_length'] = $auditionLength;
        }

        return $this->call('/openapi/room/genAuditionCode', $params);
    }


    /**
     * 设置直播上下课事件回调地址
     * http://dev.baijiayun.com/wiki/detail/79#h59-60
     * @param string $url 回调地址
     * @return array|mixed
     * @throws BJCloudException
     */
    public function liveAccountSetClassCallbackUrl($url)
    {
        return $this->call('/openapi/live_account/setClassCallbackUrl', [
            'url' => $url,
        ]);
    }

    /**
     * 查询直播上下课回调地址
     * http://dev.baijiayun.com/wiki/detail/79#h59-61
     * @throws BJCloudException
     */
    public function liveAccountGetClassCallbackUrl()
    {
        return $this->call('/openapi/live_account/getClassCallbackUrl');
    }

    /**
     * 获取教室课后评价数据
     * http://dev.baijiayun.com/wiki/detail/79#h6-55
     * @param string|int $roomId 房间id
     * @param int $page
     * @param int $pageSize
     * @param string $date
     * @return array|mixed
     * @throws BJCloudException
     */
    public function roomDataGetEvaluationStat($roomId, $page, $pageSize = 20, $date = '')
    {
        $params = [
            'room_id' => $roomId,
            'page' => $page,
            'page_size' => $pageSize
        ];

        if ($date) {
            $params['date'] = $date;
        }

        return $this->call('/openapi/room_data/getEvaluationStat', $params);
    }

    /**
     * 校验签名
     * @param null $data
     * @return array|null
     * @throws BJCloudException
     */
    public function verify($data = null)
    {
        if (is_null($data)) {
            $request = Request::createFromGlobals();
            $data = $request->request->count() > 0 ? $request->request->all() : $request->query->all();
        }

        if (!isset($data['sign'])) {
            throw new BJCloudException('没有签名信息', 400001);
        }

        $sign = $data['sign'];

        $verifySign = $this->generateSign($data, $this->partnerKey);
        if (!hash_equals($verifySign, $sign)) {
            throw new BJCloudException('签名不准确', 400002);
        }

        return $data;
    }

    /**
     * 返回成功
     * @return Response
     */
    public function responseSuccess()
    {
        return Response::create(json_encode([
            'code' => 0,
        ]));
    }

    /**
     * 返回失败
     * @param string $msg
     * @return Response
     */
    public function responseErr($msg = '错误信息')
    {
        return Response::create(json_encode([
            "code" => 1,
            "msg" => $msg
        ]));
    }

//==================================================
//              以下为系统逻辑
//==================================================

    /**
     * @param array $params 业务参数
     * @param string $partner_key 加密salt
     *
     * @return string 生成的秘钥
     */
    private function generateSign($params, $partner_key)
    {
        unset($params['sign']);

        ksort($params);//将参数按key进行排序
        $str = '';
        foreach ($params as $k => $val) {
            $str .= "{$k}={$val}&"; //拼接成 key1=value1&key2=value2&...&keyN=valueN& 的形式
        }
        $str .= "partner_key=" . $partner_key; //结尾再拼上 partner_key=$partner_key
        return md5($str); //计算md5值
    }

    /**
     * 调用API
     *
     * @param string $path 调用的API路径
     * @param array $params API参数
     * @param string $httpMethod
     * @param array $files
     *
     * @return array|mixed
     * @throws BJCloudException
     */
    private function call($path, $params = [], $httpMethod = 'POST', $files = [])
    {
        $sysParams = [];
        $sysParams['partner_id'] = trim($this->partnerId);
        $sysParams['timestamp'] = time();
        $sysParams['sign'] = $this->generateSign(array_merge($sysParams, $params), $this->partnerKey);

        $request = null;

        if ('GET' == $httpMethod) {

        } elseif ('POST' == $httpMethod) {
            $requestParam = array_merge($sysParams, $params);

            $request = $this->httpClient->post($path, [
                'form_params' => $requestParam,
            ]);
        }
        $content = $request->getBody()->getContents();
        $response = json_decode($content, true);

        if ($response['code'] !== 0) {
            throw new BJCloudException($response['msg'], $response['code']);
        }
        return $response['data'];
    }


}