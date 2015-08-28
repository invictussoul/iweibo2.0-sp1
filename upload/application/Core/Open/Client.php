<?php
/**
 * iweibo2.0
 *
 * 开放平台操作类
 *
 * @author gionouyang <gionouyang@tencent.com>
 * @link http://open.t.qq.com/iweibo
 * @copyright Copyright © 1998-2011. All Rights Reserved
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Core_Open_Client.php 2011-06-09 16:15:00Z gionouyang $
 * @package Controller
 * @since 2.0
 */
class Core_Open_Client
{
    //返回格式
    const RETURN_FORMAT = 'json';
    const OPEN_HOST = 'http://open.t.qq.com';

    /**
     * 构造函数
     *
     * @access public
     * @param mixed $wbakey 应用APP KEY
     * @param mixed $wbskey 应用APP SECRET
     * @param mixed $accecss_token OAuth认证返回的token
     * @param mixed $accecss_token_secret OAuth认证返回的token secret
     * @return void
     */
    function __construct($wbakey, $wbskey, $accecss_token = null, $accecss_token_secret = null)
    {
        $this->oauth = new Core_Open_Opent($wbakey, $wbskey, $accecss_token, $accecss_token_secret);
    }

    /*
     * 获取用户消息
     * @p 数组,包括以下:
     * @f 分页标识（0：第一页，1：向下翻页，2向上翻页）
     * @t 本页起始时间（第一页 0，继续：根据返回记录时间决定）
     * @n 每次请求记录的条数（1-20条）
     * @name: 用户名 空表示本人
     * @return array
     * *********************/
    public function getTimeline($p)
    {
        if(! isset($p['name']))
        {
            $url = self::OPEN_HOST . '/api/statuses/home_timeline?f=1&type=4';
            $params = array('format' => self::RETURN_FORMAT, 'pageflag' => $p['f'], 'reqnum' => $p['n'],
            'pagetime' => $p['t'], 'type' => $p['utype'], 'contenttype' => $p['ctype']);
        }
        else
        {
            $url = self::OPEN_HOST . '/api/statuses/user_timeline?f=1';
            $params = array('format' => self::RETURN_FORMAT, 'pageflag' => $p['f'], 'reqnum' => $p['n'],
            'pagetime' => $p['t'], 'name' => $p['name'], 'lastid' => $p['l'], 'type' => $p['utype'],
            'contenttype' => $p['ctype']);
        }
        return $this->oauth->get($url, $params);
    }

    /*
     * 获取多用户消息
     * @p 数组,包括以下:
     * @f 分页标识（0：第一页，1：向下翻页，2向上翻页）
     * @t 本页起始时间（第一页 0，继续：根据返回记录时间决定）
     * @n 每次请求记录的条数（1-100条）
     * @names: 你需要读取用户列表用“,”隔开，例如：abc,bcde,effg
     * @return array
     * *********************/
    public function getUsersTimeline($p)
    {
        $url = self::OPEN_HOST . '/api/statuses/users_timeline?f=1';
        $params = array('format' => self::RETURN_FORMAT, 'pageflag' => $p['f'], 'reqnum' => $p['n'],
        'pagetime' => $p['t'], 'names' => $p['names'], 'type' => $p['utype'], 'contenttype' => $p['ctype']);
        return $this->oauth->get($url, $params);
    }

    /*
     * 同城广播
     * @p 数组,包括以下:
     * @p 记录的起始位置（第一次请求是填0，继续请求进填上次返回的Pos）
     * @n 每次请求记录的条数（1-20条）
     * @City 城市码
     * @return array
     */
    public function getArea($p)
    {
        $url = self::OPEN_HOST . '/api/statuses/area_timeline?f=1';
        $params = array('format' => self::RETURN_FORMAT, 'pos' => $p['p'], 'reqnum' => $p['n'], 'city' => $p['city'],
        'province' => $p['province'], 'country' => $p['country']);
        return $this->oauth->get($url, $params);
    }

    /*
     * 广播大厅消息
     * @p 数组,包括以下:
     * @p 记录的起始位置（第一次请求是填0，继续请求进填上次返回的Pos）
     * @n 每次请求记录的条数（1-20条）
     * @return array
     */
    public function getPublic($p)
    {
        $url = self::OPEN_HOST . '/api/statuses/public_timeline?f=1';
        $params = array('format' => self::RETURN_FORMAT, 'pos' => $p['p'], 'reqnum' => $p['n']);
        return $this->oauth->get($url, $params);
    }

    /*
     *获取关于我的消息
     * @p 数组,包括以下:
     * @f 分页标识（0：第一页，1：向下翻页，2向上翻页）
     * @t 本页起始时间（第一页 0，继续：根据返回记录时间决定）
     * @n 每次请求记录的条数（1-20条）
     * @l 当前页最后一条记录，用用精确翻页用
     * @type : 0 提及我的, other 我广播的
     * @return array
     */
    public function getMyTweet($p)
    {
        $p['type'] == 0 ? $url = self::OPEN_HOST . '/api/statuses/mentions_timeline?f=1' : $url = self::OPEN_HOST .
         '/api/statuses/broadcast_timeline?f=1';
        $params = array('format' => self::RETURN_FORMAT, 'pageflag' => $p['f'], 'reqnum' => $p['n'], 'contenttype' => 4,
        'pagetime' => $p['t'], 'lastid' => $p['l'], 'type' => $p['utype'], 'contenttype' => $p['ctype']);
        return $this->oauth->get($url, $params);
    }

    /*
     *获取话题下的消息
     * @p 数组,包括以下:
     * @t 话题名字
     * @f 分页标识（PageFlag = 1表示向后（下一页）查找；PageFlag = 2表示向前（上一页）查找；PageFlag = 3表示跳到最后一页  PageFlag = 4表示跳到最前一页）
     * @p 分页标识（第一页 填空，继续翻页：根据返回的 pageinfo决定）
     * @n 每次请求记录的条数（1-20条）
     * @return array
     */
    public function getTopic($p)
    {
        $url = self::OPEN_HOST . '/api/statuses/ht_timeline?f=1';
        $params = array('format' => self::RETURN_FORMAT, 'pageflag' => $p['f'], 'reqnum' => $p['n'],
        'httext' => $p['t'], 'pageinfo' => $p['p']);
        return $this->oauth->get($url, $params);
    }

    /*
     *获取一条消息
     * @p 数组,包括以下:
     * @id 微博ID
     * @return array
     */
    public function getOne($p)
    {
        $url = self::OPEN_HOST . '/api/t/show?f=1';
        $params = array('format' => self::RETURN_FORMAT, 'id' => $p['id']);
        return $this->oauth->get($url, $params);
    }

    /*
     *广播一条消息
     * @p 数组,包括以下:
     * @c 微博内容
     * @ip 用户IP(以分析用户所在地)
     * @j 经度（可以填空）
     * @w 纬度（可以填空）
     * @p 图片
     ******* type = 2,3,4
     * @r 父id
     * @type 1 广播 2 转播 3 对话 4 评论
     * @return array
     */
    public function postOne($p)
    {
        $params = array('format' => self::RETURN_FORMAT, 'content' => $p['c'], 'clientip' => $p['ip'],
        'jing' => $p['j'], 'wei' => $p['w']);
        switch($p['type'])
        {
            case 2:
                $url = self::OPEN_HOST . '/api/t/re_add?f=1';
                $params['reid'] = $p['r'];
                return $this->oauth->post($url, $params);
                break;
            case 3:
                $url = self::OPEN_HOST . '/api/t/reply?f=1';
                $params['reid'] = $p['r'];
                return $this->oauth->post($url, $params);
                break;
            case 4:
                $url = self::OPEN_HOST . '/api/t/comment?f=1';
                $params['reid'] = $p['r'];
                return $this->oauth->post($url, $params);
                break;
            default:
                if(! empty($p['p']))
                {
                    $url = self::OPEN_HOST . '/api/t/add_pic?f=1';
                    $params['pic'] = $p['p'];
                    return $this->oauth->post($url, $params, true);
                }
                else
                    if(! empty($p['audio']))
                    {
                        $url = self::OPEN_HOST . '/api/t/add_music?f=1';
                        $params['url'] = $p['audio']['url'];
                        $params['title'] = $p['audio']['title'];
                        $params['author'] = $p['audio']['author'];
                        return $this->oauth->post($url, $params, true);
                    }
                    else
                        if(! empty($p["video"]))
                        {
                            $url = self::OPEN_HOST . '/api/t/add_video?f=1';
                            $params['url'] = $p['video'];
                            return $this->oauth->post($url, $params, true);
                        }
                        else
                        {
                            $url = self::OPEN_HOST . '/api/t/add?f=1';
                            return $this->oauth->post($url, $params);
                        }
                break;
        }

    }

    /*
     *删除一条消息
     * @p 数组,包括以下:
     * @id 微博ID
     * @return array
     */
    public function delOne($p)
    {
        $url = self::OPEN_HOST . '/api/t/del?f=1';
        $params = array('format' => self::RETURN_FORMAT, 'id' => $p['id']);
        return $this->oauth->post($url, $params);
    }

    /*
     *获取转播和评论消息列表
     * @p 数组,包括以下:
     * @reid 转播或者对话根结点ID；
     * @f（根据dwTime），0：第一页，1：向下翻页，2向上翻页；
     * @n 要返回的记录的条数(1-20)；
     * @tid TwitterId：起始id，用于结果查询中的定位，上下翻页时才有用；
     * @t 起始时间戳，上下翻页时才有用，取第一页时忽略；
     * @flag 标识0 转播列表，1评论列表 2 评论与转播列表
     * @return array
     */
    public function getReplay($p)
    {
        $url = self::OPEN_HOST . '/api/t/re_list?f=1';
        $params = array('format' => self::RETURN_FORMAT, 'rootid' => $p['reid'], 'pageflag' => $p['f'],
        'reqnum' => $p['n'], 'flag' => $p['flag']);
        if(isset($p['t']))
        {
            $params['pagetime'] = $p['t'];
        }
        if(isset($p['tid']))
        {
            $params['twitterid'] = $p['tid'];
        }
        return $this->oauth->get($url, $params);
    }

    /*
     *获取当前用户的信息
     * @p 数组,包括以下:
     * @n:用户名 空表示本人
     * @return array
     */
    public function getUserInfo($p = false)
    {
        if(! $p || ! $p['n'])
        {
            $url = self::OPEN_HOST . '/api/user/info?f=1';
            $params = array('format' => self::RETURN_FORMAT);
        }
        else
        {
            $url = self::OPEN_HOST . '/api/user/other_info?f=1';
            $params = array('format' => self::RETURN_FORMAT, 'name' => $p['n']);
        }
        return $this->oauth->get($url, $params);
    }

    /*
     *获取一批用户的信息
	 *
	 * @params array $users 帐号数组
     * @return array
     */
    public function getUserInfos($users)
    {
        $url = self::OPEN_HOST . '/api/user/infos?f=1';
        is_array($users) && $users = implode(',', $users);
        $params = array('format' => self::RETURN_FORMAT, 'names' =>$users );
        return $this->oauth->get($url, $params);
    }

    /*
     *更新用户资料
     * @p 数组,包括以下:
     * @nick: 昵称
     * @sex: 性别 0 ，1：男2：女
     * @year:出生年 1900-2010
     * @month:出生月 1-12
     * @day:出生日 1-31
     * @countrycode:国家码
     * @provincecode:地区码
     * @citycode:城市 码
     * @introduction: 个人介绍
     * @return array
     */
    public function updateMyinfo($p)
    {
        $url = self::OPEN_HOST . '/api/user/update?f=1';
        $p['format'] = self::RETURN_FORMAT;
        return $this->oauth->post($url, $p);
    }

    /*
     *更新用户头像
     * @p 数组,包括以下:
     * @Pic:文件域表单名 本字段不能放入到签名串中
     * @return array
     ******************/
    public function updateUserHead($p)
    {
        $url = self::OPEN_HOST . '/api/user/update_head?f=1';
        $p['format'] = self::RETURN_FORMAT;
        return $this->oauth->post($url, $p, true);
    }

    /*
     *获取听众列表/偶像列表
     * @p 数组,包括以下:
     * @num: 请求个数(1-30)
     * @start: 起始位置
     * @n:用户名 空表示本人
     * @type: 0 听众 1 偶像
     * @return array
     */
    public function getMyfans($p)
    {
        try
        {
            if($p['n'] == '')
            {
                $p['type'] ? $url = self::OPEN_HOST . '/api/friends/idollist' : $url = self::OPEN_HOST .
                 '/api/friends/fanslist';
            }
            else
            {
                $p['type'] ? $url = self::OPEN_HOST . '/api/friends/user_idollist' : $url = self::OPEN_HOST .
                 '/api/friends/user_fanslist';
            }
            $params = array('format' => self::RETURN_FORMAT, 'name' => $p['n'], 'reqnum' => $p['num'],
            'startindex' => $p['start']);
            return $this->oauth->get($url, $params);
        }
        catch(Core_Exception $e)
        {
            $ret = array("ret" => 0, "msg" => "ok",
            "data" => array("timestamp" => 0, "hasnext" => 1, "info" => array()));
            return $ret;
        }
    }

    /*
     *收听/取消收听某人
     * @p 数组,包括以下:
     * @n: 用户名
     * @type: 0 取消收听,1 收听 ,2 特别收听
     * @return array
     */
    public function setMyidol($p)
    {
        switch($p['type'])
        {
            case 0:
                $url = self::OPEN_HOST . '/api/friends/del?f=1';
                break;
            case 1:
                $url = self::OPEN_HOST . '/api/friends/add?f=1';
                break;
            case 2:
                $url = self::OPEN_HOST . '/api/friends/addspecail?f=1';
                break;
        }
        $params = array('format' => self::RETURN_FORMAT, 'name' => $p['n']);
        return $this->oauth->post($url, $params);
    }

    /*
     *检测是否我粉丝或偶像
     * @p 数组,包括以下:
     * @n: 其他人的帐户名列表（最多30个,逗号分隔）
     * @type:   0 检测听众，1检测收听的人 2 两种关系都检测
     * @return array
     */
    public function checkFriend($p)
    {
        $url = self::OPEN_HOST . '/api/friends/check';
        $params = array('format' => self::RETURN_FORMAT, 'names' => $p['n'], 'flag' => $p['type']);
        return $this->oauth->get($url, $params);
    }

    /*
     *发私信
     * @p 数组,包括以下:
     * @c: 微博内容
     * @ip: 用户IP(以分析用户所在地)
     * @j: 经度（可以填空）
     * @w: 纬度（可以填空）
     * @n: 接收方微博帐号
     * @return array
     */
    public function postOneMail($p)
    {
        $url = self::OPEN_HOST . '/api/private/add?f=1';
        $params = array('format' => self::RETURN_FORMAT, 'content' => $p['c'], 'clientip' => $p['ip'],
        'jing' => $p['j'], 'wei' => $p['w'], 'name' => $p['n']);
        return $this->oauth->post($url, $params);
    }

    /*
     *删除一封私信
     * @p 数组,包括以下:
     * @id: 微博ID
     * @return array
     */
    public function delOneMail($p)
    {
        $url = self::OPEN_HOST . '/api/private/del?f=1';
        $params = array('format' => self::RETURN_FORMAT, 'id' => $p['id']);
        return $this->oauth->post($url, $params);
    }

    /*
     *私信收件箱和发件箱
     * @p 数组,包括以下:
     * @f 分页标识（0：第一页，1：向下翻页，2向上翻页）
     * @t: 本页起始时间（第一页 0，继续：根据返回记录时间决定）
     * @n: 每次请求记录的条数（1-20条）
     * @type : 0 发件箱 1 收件箱
     * @return array
     */
    public function getMailBox($p)
    {
        if($p['type'])
        {
            $url = self::OPEN_HOST . '/api/private/recv?f=1';
        }
        else
        {
            $url = self::OPEN_HOST . '/api/private/send?f=1';
        }
        $params = array('format' => self::RETURN_FORMAT, 'pageflag' => $p['f'], 'pagetime' => $p['t'],
        'reqnum' => $p['n'], 'lastid' => $p['l']);
        return $this->oauth->get($url, $params);
    }

    /*
     *搜索
     * @p 数组,包括以下:
     * @k:搜索关键字
     * @n: 每页大小
     * @p: 页码
     * @type : 0 用户 1 消息 2 话题 3tag
     * @return array
     */
    public function getSearch($p)
    {
        switch($p['type'])
        {
            case 0:
                $url = self::OPEN_HOST . '/api/search/user?f=1';
                break;
            case 1:
                $url = self::OPEN_HOST . '/api/search/t?f=1';
                break;
            case 2:
                $url = self::OPEN_HOST . '/api/search/ht?f=1';
                break;
            case 3:
                $url = self::OPEN_HOST . '/api/search/userbytag?f=1';
                break;
            default:
                $url = self::OPEN_HOST . '/api/search/t?f=1';
                break;
        }

        $params = array('format' => self::RETURN_FORMAT, 'keyword' => $p['k'], 'pagesize' => $p['n'], 'page' => $p['p']);
        return $this->oauth->get($url, $params);
    }

    /*
     *热门话题
     * @p 数组,包括以下:
     * @type: 请求类型 1 话题名，2 搜索关键字 3 两种类型都有
     * @n: 请求个数（最多20）
     * @pos :请求位置，第一次请求时填0，继续填上次返回的POS
     * @return array
     */
    public function getHotTopic($p)
    {
        $url = self::OPEN_HOST . '/api/trends/ht?f=1';
        if($p['type'] < 1 || $p['type'] > 3)
        {
            $p['type'] = 1;
        }
        $params = array('format' => self::RETURN_FORMAT, 'type' => $p['type'], 'reqnum' => $p['n'], 'pos' => $p['pos']);
        return $this->oauth->get($url, $params);
    }

    /*
     *查看数据更新条数
     * @p 数组,包括以下:
     * @op :请求类型 0：只请求更新数，不清除更新数，1：请求更新数，并对更新数清零
     * @type：5 首页未读消息记数，6 @页消息记数 7 私信页消息计数 8 新增粉丝数 9 首页广播数（原创的）
     * @return array
     */
    public function getUpdate($p)
    {
        $url = self::OPEN_HOST . '/api/info/update?f=1';
        if(isset($p['type']))
        {
            if($p['op'])
            {
                $params = array('format' => self::RETURN_FORMAT, 'op' => $p['op'], 'type' => $p['type']);
            }
            else
            {
                $params = array('format' => self::RETURN_FORMAT, 'op' => $p['op']);
            }
        }
        else
        {
            $params = array('format' => self::RETURN_FORMAT, 'op' => $p['op']);
        }
        return $this->oauth->get($url, $params);
    }

    /*
     *添加/删除 收藏的微博
     * @p 数组,包括以下:
     * @id : 微博id
     * @type：1 添加 0 删除
     * @return array
     */
    public function postFavMsg($p)
    {
        if($p['type'])
        {
            $url = self::OPEN_HOST . '/api/fav/addt?f=1';
        }
        else
        {
            $url = self::OPEN_HOST . '/api/fav/delt?f=1';
        }
        $params = array('format' => self::RETURN_FORMAT, 'id' => $p['id']);
        return $this->oauth->post($url, $params);
    }

    /*
     *添加/删除 收藏的话题
     * @p 数组,包括以下:
     * @id : 微博id
     * @type：1 添加 0 删除
     * @return array
     */
    public function postFavTopic($p)
    {
        if($p['type'])
        {
            $url = self::OPEN_HOST . '/api/fav/addht?f=1';
        }
        else
        {
            $url = self::OPEN_HOST . '/api/fav/delht?f=1';
        }
        $params = array('format' => self::RETURN_FORMAT, 'id' => $p['id']);
        return $this->oauth->post($url, $params);
    }

    /*
     *获取收藏的内容
     * @p 数组,包括以下:
     * @Format: 返回数据的格式 是（json或xml）
     *******话题
	n:请求数，最多15
	f:翻页标识  0：首页   1：向下翻页 2：向上翻页
	t:翻页时间戳0
	l:翻页话题ID，第次请求时为0
     *******消息
	f 分页标识（0：第一页，1：向下翻页，2向上翻页）
	t: 本页起始时间（第一页 0，继续：根据返回记录时间决定）
	n: 每次请求记录的条数（1-20条）
     * @type 0 收藏的消息  1 收藏的话题
     * @return array
     */
    public function getFav($p)
    {
        if($p['type'])
        {
            $url = self::OPEN_HOST . '/api/fav/list_ht?f=1';
            $params = array('format' => self::RETURN_FORMAT, 'reqnum' => $p['n'], 'pageflag' => $p['f'],
            'pagetime' => $p['t'], 'lastid' => $p['l']);
        }
        else
        {
            $url = self::OPEN_HOST . '/api/fav/list_t?f=1';
            $params = array('format' => self::RETURN_FORMAT, 'reqnum' => $p['n'], 'pageflag' => $p['f'],
            'pagetime' => $p['t'], 'lastid' => $p['l']);
        }
        return $this->oauth->get($url, $params);
    }

    /*
     *获取话题id
     * @p 数组,包括以下:
     * @list: 话题名字列表（abc,efg,）
     * @return array
     */
    public function getTopicId($p)
    {
        $url = self::OPEN_HOST . '/api/ht/ids?f=1';
        $params = array('format' => self::RETURN_FORMAT, 'httexts' => $p['list']);
        return $this->oauth->get($url, $params);
    }

    /*
     *获取话题内容
     * @p 数组,包括以下:
     * @list: 话题id列表（abc,efg,）
     * @return array
     */
    public function getTopicList($p)
    {
        $url = self::OPEN_HOST . '/api/ht/info?f=1';
        $params = array('format' => self::RETURN_FORMAT, 'ids' => $p['list']);
        return $this->oauth->get($url, $params);
    }

    /*
     *增加tag
     * @p 数组,包括以下:
     * @tag: tag 名称n
     * @return array
     */
    public function addTag($p)
    {
        $url = self::OPEN_HOST . '/api/tag/add';
        $params = array('format' => self::RETURN_FORMAT, 'tag' => $p['n']);
        return $this->oauth->post($url, $params);
    }

    /*
     *删除tag
     * @p 数组,包括以下:
     * @tag: tag的id
     * @return array
     */
    public function delTag($p)
    {
        $url = self::OPEN_HOST . '/api/tag/del';
        $params = array('format' => self::RETURN_FORMAT, 'tagid' => $p['id']);
        return $this->oauth->post($url, $params);
    }

    /*
     *获取视频信息
     * @p 数组,包括以下:
     * @format: 返回数据的格式
     * @url: 视频地址
     * @return  json或xml
     */
    public function getVideoInfo($p)
    {
        $url = self::OPEN_HOST . '/api/t/getvideoinfo';
        $params = array('format' => 'string', 'url' => $p['url']);
        return $this->oauth->post($url, $params);
    }

    /*
     *获取视频信息
     * @p 数组,包括以下:
     * @format: 返回数据的格式
     * @url: 视频地址
     * @return  json或xml
     */
    public function getUrlFull($p)
    {
        $url = self::OPEN_HOST . '/api/t/getvideoinfo';
        $params = array('format' => 'string', 'url' => $p['url']);
        return $this->oauth->post($url, $params);
    }

    /*
     *根据微博id批量获取微博内容（与索引结合起来用）
     * @p 数组,包括以下:
     * @format: 返回数据的格式
     * @ids:array || string
     * @return  json或xml
     */
    public function getTlineFromIds($p)
    {
        $url = self::OPEN_HOST . '/api/t/list';
        if(is_array($p['ids']) && $p['ids'])
        {
            $p['ids'] = implode(',', $p['ids']);
        }
        $params = array('format' => self::RETURN_FORMAT, 'ids' => $p['ids']);
        return $this->oauth->get($url, $params);
    }

    /*
     *获取我的粉丝列表，简单信息
     * @p 数组,包括以下:
     * @format: 返回数据的格式
     * @reqnum: 请求个数(1-200)
     * @Startindex: 起始位置（第一页填0，继续向下翻页：reqnum*（page-1））
     * @return  json或xml
     */
    public function getFansShortList($p)
    {
        $url = self::OPEN_HOST . '/api/friends/fanslist_s';
        $params = array('format' => self::RETURN_FORMAT, 'reqnum' => $p['reqnum'], 'startindex' => $p['startindex']);
        return $this->oauth->get($url, $params);
    }

        /*
     *获取我的粉丝列表，简单信息
     * @p 数组,包括以下:
     * @format: 返回数据的格式
     * @reqnum: 请求个数(1-200)
     * @Startindex: 起始位置（第一页填0，继续向下翻页：reqnum*（page-1））
     * @return  json或xml
     */
    public function getIdolShortList($p)
    {
        $url = self::OPEN_HOST . '/api/friends/idollist_s';
        $params = array('format' => self::RETURN_FORMAT, 'reqnum' => $p['reqnum'], 'startindex' => $p['startindex']);
        return $this->oauth->get($url, $params);
    }


}
?>
