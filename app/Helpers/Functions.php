<?php
/**
 * Created by @gai871013.
 * User: @gai871013wanga
 * FileName: Functions.php
 * Date: 2016-05-29
 * Time: 02:46
 *
 * ━━━━━━神兽出没━━━━━━
 *       ┏┓    ┏┓
 *      ┏┛┻━━━━┛┻┓
 *      ┃        ┃
 *      ┃    ━   ┃
 *      ┃  ┳┛ ┗┳ ┃
 *      ┃        ┃
 *      ┃    ┻   ┃
 *      ┃        ┃
 *      ┗━┓    ┏━┛  Code is far away from bug with the animal protecting
 *        ┃    ┃    神兽保佑,代码无bug
 *        ┃    ┃
 *        ┃    ┗━━━┓
 *        ┃        ┣┓
 *        ┃       ┏┛
 *        ┗┓┓┏━┳┓┏┛
 *         ┃┫┫ ┃┫┫
 *         ┗┻┛ ┗┻┛
 *
 * ━━━━━━感觉萌萌哒━━━━━━
 *
 */


/**
 * 样式别名加载（支持批量加载）
 * @param  string|array $aliases 配置文件中的别名
 * @param  array $attributes 标签中需要加入的其它参数的数组
 * @return string
 */
function style($aliases, $attributes = array(), $interim = '')
{
    if (is_array($aliases)) {
        foreach ($aliases as $key => $value) {
            $interim .= (is_int($key)) ? style($value, $attributes, $interim) : style($key, $value, $interim);
        }
        return $interim;
    }
    $cssAliases = Config::get('extend.webAssets.cssAliases');
    $url = isset($cssAliases[$aliases]) ? $cssAliases[$aliases] : $aliases;
    return HTML::style($url, $attributes);
}

/**
 * 脚本别名加载（支持批量加载）
 * @param  string|array $aliases 配置文件中的别名
 * @param  array $attributes 标签中需要加入的其它参数的数组
 * @return string
 */
function script($aliases, $attributes = array(), $interim = '')
{
    if (is_array($aliases)) {
        foreach ($aliases as $key => $value) {
            $interim .= (is_int($key)) ? script($value, $attributes, $interim) : script($key, $value, $interim);
        }
        return $interim;
    }
    $jsAliases = Config::get('extend.webAssets.jsAliases');
    $url = isset($jsAliases[$aliases]) ? $jsAliases[$aliases] : $aliases;
    return HTML::script($url, $attributes);
}

/**
 * 脚本别名加载（补充）用于 js 的 document.write(）中
 * @param  string $aliases 配置文件中的别名
 * @param  array $attributes 标签中需要加入的其它参数的数组
 * @return string
 */
function or_script($aliases, $attributes = array())
{
    $jsAliases = Config::get('extend.webAssets.jsAliases');
    $url = isset($jsAliases[$aliases]) ? $jsAliases[$aliases] : $aliases;
    $attributes['src'] = URL::asset($url);
    return "'<script" . HTML::attributes($attributes) . ">'+'<'+'/script>'";
}


/**
 * 获取登录用户信息，用于登录之后页面显示或验证
 *
 * @param string $ret 限定返回的字段
 * @return string|object 返回登录用户相关字段信息或其ORM对象
 */
function user($ret = 'nickname')
{
    if (Auth::check()) {
        switch ($ret) {
            case 'nickname':
                return Auth::user()->nickname;  //返回昵称
                break;
            case 'username':
                return Auth::user()->username;  //返回登录名
                break;
            case 'realname':
                return Auth::user()->realname;  //返回真实姓名
                break;
            case 'id':
                return Auth::user()->id;  //返回用户id
                break;
            case 'user_type':
                return Auth::user()->user_type;  //返回用户类型
                break;
            case 'object':
                return Auth::user();  //返回User对象
                break;
            default:
                return Auth::user()->nickname;  //默认返回昵称
                break;
        }
    } else {
        if ($ret === 'object') {
            $user = app()->make('App\Repositories\UserRepository');
            return $user->manager(1);  //主要为了修正 `php artisan route:list` 命令出错问题
        } else {
            return 'No Auth::check()';
        }
    }
}


function is_exist($keyWord, $stack)
{
    foreach ($stack as $key => $val) {
        if ($keyWord == $val) {
            return "1";
        }
    }
    return "0";
}

//二维数组
function search($keyWord, $stack)
{
    foreach ($stack as $key => $val) {
        if (in_array($keyWord, $val)) {
            return "1";
        }
    }
    return "0";
}

function getsre($str, $len)
{
    $strlen = strlen($str);
    if ($strlen < $len) {
        return $str;
    } else {
        return mb_substr($str, 0, $len) . "...";
    }
}

function isToday($publishDate)
{
    if (empty($publishDate)) {
        return false;
    }
    $curDate = date("Y-m-d");
    $publishDate = substr($publishDate, 0, 10);
    if ($curDate === $publishDate) {
        return true;
    }
    return false;
}

function GetIP()
{
    if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
        $cip = $_SERVER["HTTP_CLIENT_IP"];
    } elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
        $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    } elseif (!empty($_SERVER["REMOTE_ADDR"])) {
        $cip = $_SERVER["REMOTE_ADDR"];
    } else {
        $cip = "IP无法获取";
    }
    return $cip;
}

function order_source()
{
    $useragent = strtolower($_SERVER["HTTP_USER_AGENT"]);
    // iphone
    $is_iphone = strripos($useragent, 'iphone');
    if ($is_iphone) {
        return 'iphone';
    }
    // android
    $is_android = strripos($useragent, 'android');
    if ($is_android) {
        return 'android';
    }
    // 微信
    $is_weixin = strripos($useragent, 'micromessenger');
    if ($is_weixin) {
        return 'weixin';
    }
    // ipad
    $is_ipad = strripos($useragent, 'ipad');
    if ($is_ipad) {
        return 'ipad';
    }
    // ipod
    $is_ipod = strripos($useragent, 'ipod');
    if ($is_ipod) {
        return 'ipod';
    }
    // pc电脑
    $is_pc = strripos($useragent, 'windows nt');
    if ($is_pc) {
        return 'pc';
    }
    return 'other';
}

function is_weixin()
{
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
        return true;
    }
    return false;
}

function is_weixin_versions()
{
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    if (strpos($user_agent, 'MicroMessenger') === false) {
        echo "非微信浏览器禁止浏览";
    } else {
        echo "微信浏览器，允许访问";
        preg_match('/.*?(MicroMessenger\/([0-9.]+))\s*/', $user_agent, $matches);
        if (intval($matches[2]) >= 5.2) {
            echo '<br>你的微信版本号为:' . $matches[2];
        } else {
            echo '你的微信版本太低，自带浏览器暂不支持上传功能，请升级版本或者点击右上角功能选择使用其他浏览器进行上传，谢谢！';
        }

    }
}

function is_weixin_upload()
{
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    if (strpos($user_agent, 'MicroMessenger') === false) {
        return 'false';
    } else {
        preg_match('/.*?(MicroMessenger\/([0-9.]+))\s*/', $user_agent, $matches);
        if (strstr($matches[2], '5.1') == true) {
            return 'false';
        } else {
            return 'true';
        }

    }
}

function is_mobile($mobile, $area_select = "86")
{
    /*
    $pattern = "/^(1)\d{10}$/";
    if(preg_match($pattern, $mobile)){
        return TRUE;
    }
    return false;
    */
    $patrn = "";
    if ($area_select == "852") {
        $patrn = "/^[6,8][0-9]{7}$/";
    } else if ($area_select == "853") {
        $patrn = "/^6[0-9]{7}$/";
    } else {
        $patrn = "/^1[0-9]{10}$/";
    }

    if (preg_match($patrn, $mobile)) {
        return TRUE;
    }
    return false;
}

/**
 * 是否在地址里
 */
function in_url($page)
{
    $url = $_SERVER['PHP_SELF'];
    if (preg_match(sprintf("/%s/i", str_replace("/", "\/", $page)), $url)) {
        return TRUE;
    }
    return FALSE;
}


/**
 * 生成uuid
 */
function uuid()
{
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),

        // 16 bits for "time_mid"
        mt_rand(0, 0xffff),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand(0, 0x0fff) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand(0, 0x3fff) | 0x8000,

        // 48 bits for "node"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

/**
 * http://stackoverflow.com/questions/1201798/use-php-to-convert-png-to-jpg-with-compression
 */
function png2jpg($originalFile, $outputFile, $quality)
{
    $image = imagecreatefrompng($originalFile);
    imagejpeg($image, $outputFile, $quality);
    imagedestroy($image);
}

function cut_str($sourcestr, $cutlength)
{
    $returnstr = '';
    $i = 0;
    $n = 0;
    $str_length = strlen($sourcestr);//字符串的字节数
    while (($n < $cutlength) and ($i <= $str_length)) {
        $temp_str = substr($sourcestr, $i, 1);
        $ascnum = Ord($temp_str);//得到字符串中第$i位字符的ascii码
        if ($ascnum >= 224)    //如果ASCII位高与224，
        {
            $returnstr = $returnstr . substr($sourcestr, $i, 3); //根据UTF-8编码规范，将3个连续的字符计为单个字符
            $i = $i + 3;            //实际Byte计为3
            $n++;            //字串长度计1
        } elseif ($ascnum >= 192) //如果ASCII位高与192，
        {
            $returnstr = $returnstr . substr($sourcestr, $i, 2); //根据UTF-8编码规范，将2个连续的字符计为单个字符
            $i = $i + 2;            //实际Byte计为2
            $n++;            //字串长度计1
        } elseif ($ascnum >= 65 && $ascnum <= 90) //如果是大写字母，
        {
            $returnstr = $returnstr . substr($sourcestr, $i, 1);
            $i = $i + 1;            //实际的Byte数仍计1个
            $n++;            //但考虑整体美观，大写字母计成一个高位字符
        } else                //其他情况下，包括小写字母和半角标点符号，
        {
            $returnstr = $returnstr . substr($sourcestr, $i, 1);
            $i = $i + 1;            //实际的Byte数计1个
            $n = $n + 0.5;        //小写字母和半角标点等与半个高位字符宽...
        }
    }
    if ($str_length > $cutlength) {
        $returnstr = $returnstr . "...";//超过长度时在尾处加上省略号
    }
    return $returnstr;

}


/**
 * 保存上传文件
 * @param $file string 文件
 * @param $save_path string 文件路径
 * @param $save_name string 文件名称，包括扩展名
 * @return 返回文件路径名
 */
function save_upload_file($file, $save_path, $save_name = NULL)
{
    $file_name = '';
    if ($file["error"] == 0) {
        if (!is_dir($_SERVER["DOCUMENT_ROOT"] . $save_path)) {
            mkdir($_SERVER["DOCUMENT_ROOT"] . $save_path, 0777, TRUE);
        }
        if (empty($save_name)) {
            $file_ext = pathinfo($file["tmp_name"], PATHINFO_EXTENSION);
            $save_name = time() . rand(10000, 99999) . $file_ext;
        }
        $file_name = $save_path . "/" . $save_name;
        move_uploaded_file($file["tmp_name"], $_SERVER["DOCUMENT_ROOT"] . $file_name);
    }
    return $file_name;
}

/**
 * 保存上传图片
 * @param $file string 文件
 * @param $save_path string 文件路径
 * @param $save_name string 文件名称，包括扩展名
 * @return array 返回数组
 */
function save_upload_image($file, $save_path, $save_name = NULL)
{
    //图片类型
    $allow_type = explode(',', 'image/png,image/jpeg,image/gif,application/octet-stream');
    //图片大小 5M
    $allow_size = 5 * 1024 * 1024 * 1024;
    $file_name = '';
    $data = array("name" => "", "error" => 0);
    if ($file["error"] == 0) {
        if (!in_array($file["type"], $allow_type)) {
            $data["error"] = 1;
        } else if ($file['size'] > $allow_size) {
            $data["error"] = 2;
        } else {
            if (!is_dir($_SERVER["DOCUMENT_ROOT"] . $save_path)) {
                mkdir($_SERVER["DOCUMENT_ROOT"] . $save_path, 0777, TRUE);
            }
            if (empty($save_name)) {
                $file_ext = ".jpg";
                $save_name = time() . rand(10000, 99999) . $file_ext;
            }
            $file_name = $save_path . "/" . $save_name;
            move_uploaded_file($file["tmp_name"], $_SERVER["DOCUMENT_ROOT"] . $file_name);
            $data["name"] = $file_name;
        }
    } else {
        $data["error"] = $file["error"];
    }
    return $data;
}

/**
 * 保存上传的录音
 * @param $file string 文件
 * @param $save_path string 文件路径
 * @param $save_name string 文件名称，包括扩展名
 * @return array 返回数组
 */
function save_upload_sound($file, $save_path, $save_name = NULL)
{
    //图片类型
    $allow_type = explode(',', 'video/3gpp,application/octet-stream');
    //图片大小 5M
    $allow_size = 5 * 1024 * 1024 * 1024;
    $file_name = '';
    $data = array("name" => "", "error" => 0);
    if ($file["error"] == 0) {
        if (!in_array($file["type"], $allow_type)) {
            $data["error"] = 1;
        } else if ($file['size'] > $allow_size) {
            $data["error"] = 2;
        } else {
            if (!is_dir($_SERVER["DOCUMENT_ROOT"] . $save_path)) {
                mkdir($_SERVER["DOCUMENT_ROOT"] . $save_path, 0777, TRUE);
            }
            if (empty($save_name)) {
                $file_ext = ".3gp";
                $save_name = time() . rand(10000, 99999) . $file_ext;
            }
            $file_name = $save_path . "/" . $save_name;
            move_uploaded_file($file["tmp_name"], $_SERVER["DOCUMENT_ROOT"] . $file_name);
            $data["name"] = $file_name;
        }
    } else {
        $data["error"] = $file["error"];
    }
    return $data;
}

/**
 * 获取图片新尺寸
 * @param $image_url 图片地址
 * @param $new_width 新宽度
 */
function get_image_size($image_url, $new_width)
{
    $img_w = $new_width;
    $img_h = 0;
    $scale = 0;
    if (is_file($image_url)) {
        $image = getimagesize($image_url);
        $ori_w = $image[0];
        $ori_h = $image[1];
        $scale = $new_width / $ori_w;
        $img_h = intval($ori_h * $scale);
    }
    $data = array();
    $data['width'] = $img_w;
    $data['height'] = $img_h;
    $data['scale'] = $scale;
    $data[0] = $img_w;
    $data[1] = $img_h;
    $data[2] = $scale;
    return $data;

}

/**
 * 获取数据列表
 * @param array $arr_data_list 数组列表
 * @param array 返回列表和当前列表最大id
 */
function get_data_list($list)
{
    //获取since_id
    if (is_array($list) && count($list) > 0) {
        $last = end($list);
        $since_id = $last['id'];
    } else {
        $since_id = 0;
    }

    return array("list" => $list, "since_id" => $since_id);
}

/**
 * 获取数据列表
 * @param array $arr_data_list 数组列表
 * @param array 返回列表和当前列表最大id
 */
function get_data_list_modified_on($list)
{
    //获取since_id
    if (is_array($list) && count($list) > 0) {
        $last = end($list);
        $since_id = $last['modified_on'];
    } else {
        $since_id = 0;
    }

    return array("list" => $list, "since_id" => $since_id);
}

/**
 * 检测参数签名
 * @return 签名正确返回true，否则返回false
 */
function check_params_sig()
{
    //私密令牌
    $secret = '3886818e022a2f8c4251caa85b3f51bc';
    //过期时间10分钟
    $time_expired = 600;
    $params = $_REQUEST;
    if (count($params) > 0) {
        if (isset($params['ts']) && isset($params['sig'])) {
            natsort($params);
            $keys = array_keys($params);
            $temp = array();
            $ts = 0;
            $sig = '';
            for ($i = 0; $i < count($keys); $i++) {
                $key = strtolower($keys[$i]);
                if ($key == 'sig') {
                    $sig = $params['sig'];
                } else if ($key == 'ts') {
                    $ts = intval($params['ts']);
                } else {
                    $value = $params[$key];
                    array_push($temp, $key . "=" . $value);
                }
            }

            //验证过期日间
            $now = time();
            if ($timestamp > 0 && $now - $ts <= $time_expired) {
                //验证参数签名
                $new_sig = md5(implode($temp, '&') . $ts . $secret);
                return (!empty($sig) && $new_sig == $sig);
            }
        }
    }
    return FALSE;
}

function writeLog($msg, $filename = '')
{
    $logFile = $filename . "/" . date('Y-m-d') . '.txt';
    $msg = date('Y-m-d H:i:s') . ' >>> ' . $msg . "\r\n";
    if (!file_exists($filename)) {
        mkdir($filename, 0777, true);
    }
    file_put_contents($logFile, $msg, FILE_APPEND);
}

function json_result($arr_data)
{
    if (is_array($arr_data)) {
        echo json_encode($arr_data);
    }
    return TRUE;
}


function randStr($len = 6, $format = 'ALL')
{
    switch ($format) {
        case 'ALL':
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-@#~';
            break;
        case 'CHAR':
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-@#~';
            break;
        case 'NUMBER':
            $chars = '0123456789';
            break;
        default :
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-@#~';
            break;
    }
    mt_srand((double)microtime() * 1000000 * getmypid());
    $str = "";
    while (strlen($str) < $len)
        $str .= substr($chars, (mt_rand() % strlen($chars)), 1);
    return $str;
}

/**
 * 字符串转换为数组，主要用于把分隔符调整到第二个参数
 * @param  string $str 要分割的字符串
 * @param  string $glue 分割符
 * @return array
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function str2arr($str, $glue = ',')
{
    if (empty($str)) {
        return array();
    } else {
        return explode($glue, $str);
    }
}

/**
 * 数组转换为字符串，主要用于把分隔符调整到第二个参数
 * @param  array $arr 要连接的数组
 * @param  string $glue 分割符
 * @return string
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function arr2str($arr, $glue = ',')
{
    if (empty($arr)) {
        return "";
    } else {
        return implode($glue, $arr);
    }
}


if (!function_exists('page_lib')) {
    /**
     * password_verify()
     *
     * @param    int $page_size 每页数据量
     * @param    int $total 总数据
     * @return   string $create_links 分页代码
     */
    function page_lib($page_size, $total)
    {

//        $CI =& get_instance();
//        $CI->load->library('pagination');
//         分页
//        $config['base_url'] = currentUrl();
//        $config['use_page_numbers'] = TRUE;
//        $config['page_query_string'] = TRUE;
//        $config['query_string_segment'] = 'page';
//        $config['per_page'] = $page_size;
//        $config['total_rows'] = $total;
//        $config['first_link'] = '首页';
//        $config['last_link'] = '末页';
//        $CI->pagination->initialize($config);
//
//        $create_links = $CI->pagination->create_links();
//
//        return $create_links;
    }
}

//获取地址
function currentUrl()
{
    $_url = $_SERVER["REQUEST_URI"];
    $_par = parse_url($_url);
    if (isset($_par['query'])) {
        parse_str($_par['query'], $_query);
        unset($_query['page']);
        $_url = $_par['path'] . '?' . http_build_query($_query);
    } else {
        $_url = $_par['path'] . '?';
    }
    return $_url;
}

function time_tran($the_time)
{
    $now_time = date("Y-m-d H:i:s", time());
    //echo $now_time;
    $now_time = strtotime($now_time);
    $show_time = strtotime($the_time);
    $dur = $now_time - $show_time;
    if ($dur < 0) {
        return $the_time;
    } else {
        if ($dur < 60) {
            return $dur . '秒前';
        } else {
            if ($dur < 3600) {
                return floor($dur / 60) . '分钟前';
            } else {
                if ($dur < 86400) {
                    return floor($dur / 3600) . '小时前';
                } else {
                    if ($dur < 259200) {//3天内
                        return floor($dur / 86400) . '天前';
                    } else {
                        return $the_time;
                    }
                }
            }
        }
    }
}

/*
 * 获取用户下级地区
 */
function get_region($partent_id = 0, $region_type = 0)
{
    $region = Regions::where('parent_id', $partent_id)->get();
    return $region;
}

/*
 * 获取自己地区信息
 */
function get_self_region($id = 1)
{
    return Regions::where('id', $id)->first();
}

/*
 * 获取用户信息
 */
function get_user_info($user_id = 0)
{
    return \App\User::find($user_id);
}

/*
 * 写操作记录
 * 2016-6-1 01:35:30
 */
function adminLog($msg)
{
    if (Auth::check()) {
        $self = Auth::user()->toArray();
    } else {
        $self['id'] = 0;
    }
    $logs = new \App\AdminLogs();
    $logs->ctime = time();
    $logs->cip = GetIP();
    $logs->user_id = $self['id'];
    $logs->info = $msg;
    $logs->save();
}

/*
 * 修改env配置文件
 * 2016-6-2 01:35:12
 */
function modifyEnv(array $data)
{
    $envPath = base_path() . DIRECTORY_SEPARATOR . '.env';

    $contentArray = collect(file($envPath, FILE_IGNORE_NEW_LINES));

    $contentArray->transform(function ($item) use ($data) {
        foreach ($data as $key => $value) {
            if (str_contains($item, $key)) {
                if (strpos($key, 'BASE64') !== false) {
                    return $key . '=' . base64_encode($value);
                }
                return $key . '=' . $value;
            }
        }

        return $item;
    });

    $content = implode($contentArray->toArray(), "\n");

    \File::put($envPath, $content);
}

//获取文件列表
function getFile(& $Dir)
{
    if (is_dir($Dir)) {
        $FileArray = [];
        if (false != ($Handle = opendir($Dir))) {
            while (false != ($File = readdir($Handle))) {
                if ($File != '.' && $File != '..' && strpos($File, '.')) {
                    if (empty($FileArray)) {
                        $FileArray[] = $File;
                    } else {
                        array_unshift($FileArray, $File);
                    }
                }
            }
            closedir($Handle);
        }
    } else {
        $FileArray[] = '[Path]:' . $Dir . ' is not a dir or not found!';
    }
    return $FileArray;
}


function hasSonCat($cat_id)
{
    $cat = \App\ArticleCategories::where('parent_id', $cat_id)->first();
    if (isset($cat->id)) {
        return true;
    } else {
        return false;
    }
}


/**
 * 随机生成指定长度的字符串
 * @param $len 字符串长度
 * @return string
 */
function GetRandStr($len){
    $chars_array = array(
        "0", "1", "2", "3", "4", "5", "6", "7", "8", "9",
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
        "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
        "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
        "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
        "S", "T", "U", "V", "W", "X", "Y", "Z",
    );
    $charsLen = count($chars_array) - 1;

    $outputstr = "";
    for ($i=0; $i<$len; $i++)
    {
        $outputstr .= $chars_array[mt_rand(0, $charsLen)];
    }
    return $outputstr;
}
/**
 * 删除数组中的键值
 * @param array $arr  待处理一维数组
 * @param array $fields 待删除键
 * @return 新数组
 */
function GetFilterArray($arr, $fields){

    if(!empty($fields) && is_array($fields)){
        foreach ($fields as $key){
            if(array_key_exists($key, $arr)){
                unset($arr[$key]);
            }
        }
    }
    return $arr;
}
/**
 * 文件扩展名
 * @param  $file 文件
 * @return 扩展名
 */
function getExtension($file) {
    return pathinfo ( $file, PATHINFO_EXTENSION );
}
/**
 * 加密算法
 * $string 明文或密文
 * $operation 加密ENCODE或解密DECODE
 * $key 密钥
 * $expiry 密钥有效期
 */
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
    // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
    // 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
    // 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
    // 当此值为 0 时，则不产生随机密钥
    $ckey_length = 4;

    // 密匙
    // $GLOBALS['discuz_auth_key'] 这里可以根据自己的需要修改
    $key = md5($key ? $key : $GLOBALS['discuz_auth_key']);

    // 密匙a会参与加解密
    $keya = md5(substr($key, 0, 16));
    // 密匙b会用来做数据完整性验证
    $keyb = md5(substr($key, 16, 16));
    // 密匙c用于变化生成的密文
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
    // 参与运算的密匙
    $cryptkey = $keya.md5($keya.$keyc);
    $key_length = strlen($cryptkey);
    // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，解密时会通过这个密匙验证数据完整性
    // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确
    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
    $string_length = strlen($string);
    $result = '';
    $box = range(0, 255);
    $rndkey = array();
    // 产生密匙簿
    for($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }
    // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上并不会增加密文的强度
    for($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }
    // 核心加解密部分
    for($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        // 从密匙簿得出密匙进行异或，再转成字符
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
    if($operation == 'DECODE') {
        // substr($result, 0, 10) == 0 验证数据有效性
        // substr($result, 0, 10) - time() > 0 验证数据有效性
        // substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16) 验证数据完整性
        // 验证数据有效性，请看未加密明文的格式
        if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
        // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
        return $keyc.str_replace('=', '', base64_encode($result));
    }
}
/**
 * 普通加密解密算法
 * $string 明文或密文
 * $operation 加密ENCODE或解密DECODE
 * $key 密钥
 */
function normalEncrypt($string, $operation, $key){

    $key    =   md5($key);
    $x      =   0;
    $l      =   strlen($key);
    $char = '';
    $string = strval($string);
    switch ($operation){
        case 'ENCODE':
            $len    =   strlen($string);
            for ($i = 0; $i < $len; $i++){
                if ($x == $l){
                    $x = 0;
                }
                $char .= $key{$x};
                $x++;
            }
            $str = '';
            for ($i = 0; $i < $len; $i++){
                $str .= chr(ord($string{$i}) + (ord($char{$i})) % 256);
            }
            $result =  base64_encode($str);
            break;
        case 'DECODE':
            $string = base64_decode($string);
            $len = strlen($string);
            for ($i = 0; $i < $len; $i++){
                if ($x == $l){
                    $x = 0;
                }
                $char .= substr($key, $x, 1);
                $x++;
            }
            $str = '';
            for ($i = 0; $i < $len; $i++){
                if (ord(substr($string, $i, 1)) < ord(substr($char, $i, 1))){
                    $str .= chr((ord(substr($string, $i, 1)) + 256) - ord(substr($char, $i, 1)));
                }else{
                    $str .= chr(ord(substr($string, $i, 1)) - ord(substr($char, $i, 1)));
                }
            }
            $result = $str;
            break;
        default:
            $result = '加解密方法不存在';
            break;
    }

    return $result;
}
/**
 * Utf-8字符串截取函数
 *
 * @param $str 字符串
 * @param $start 开始位置
 * @param $length 长度
 * @return 截取的字符串
 */
function subString($str, $start, $length) {
    $i = 0;
    // 完整排除之前的UTF8字符
    while ( $i < $start ) {
        $ord = ord ( $str {$i} );
        if ($ord < 192) {
            $i ++;
        } elseif ($ord < 224) {
            $i += 2;
        } else {
            $i += 3;
        }
    }
    // 开始截取
    $result = '';
    while ( $i < $start + $length && $i < strlen ( $str ) ) {
        $ord = ord ( $str {$i} );
        if ($ord < 192) {
            $result .= $str {$i};
            $i ++;
        } elseif ($ord < 224) {
            $result .= $str {$i} . $str {$i + 1};
            $i += 2;
        } else {
            $result .= $str {$i} . $str {$i + 1} . $str {$i + 2};
            $i += 3;
        }
    }
    if ($i < strlen ( $str )) {
        $result .= '...';
    }
    return $result;
}
/**
 * 请求或响应字段参数排序
 * @param $data 待排序数据
 * @param $fieldname 确定个数字段
 * @return 已排序数据
 */
function fieldParamSort($data, $fieldname){

    $inside = array_keys($data[$fieldname]);
    $outside = array_keys($data);
    $result = array();
    foreach($inside as $vol){
        foreach($outside as $value){
            $result[$vol][$value] = $data[$value][$vol];
        }
    }

    return $result;
}
/**
 * 获取内存使用情况
 * @return string
 */
function memory_usage() {
    $memory     = ( ! function_exists('memory_get_usage')) ? '0' : round(memory_get_usage()/1024/1024, 2).'MB';
    return $memory;
}
/**
 * 重新拼接url
 * @param $url 待拼接url
 * @param $param 参数
 * @return 新的url
 */
function urlSplice($url, $param=array()){

    $data = parse_url($url);
    $data['query'] = !empty($data['query']) ? $data['query'] : '';
    parse_str($data['query'], $arr);
    $param = array_merge($arr, $param);
    if(empty($data['path'])) $data['path'] = '';
    if(!empty($param)){
        $newUrl = $data['scheme']."://".$data['host'].$data['path']."?".http_build_query($param);
    }else{
        $newUrl = $data['scheme']."://".$data['host'].$data['path'];
    }

    return $newUrl;
}
/**
 * 判断是否是https
 * @return boolean
 */
function is_HTTPS(){
    if(!isset($_SERVER['HTTPS']))  return FALSE;
    if($_SERVER['HTTPS'] === 1){  //Apache
        return TRUE;
    }elseif($_SERVER['HTTPS'] === 'on'){ //IIS
        return TRUE;
    }elseif($_SERVER['SERVER_PORT'] == 443){ //其他
        return TRUE;
    }
    return FALSE;
}