<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GetIPAddressCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:ip {--file_path=ip.log}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $path = $this->option('file_path');
//        $reg = '/(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)(?:[.](?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)){3}/';
        $reg = '/(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)/';
        $ip  = @file_get_contents(storage_path($path));
        if (!$ip) {
            $this->error('文件不存在');
            return false;
        }
        $res = preg_match_all($reg, $ip, $ips);
//        $res2 = preg_match_all($reg2, $ip, $ips2, PREG_PATTERN_ORDER);
        // PREG_SET_ORDER
        $all  = [];
        $res_ip = [];
        $file = storage_path('logs/ip_result_' . date('YmdHis') . '.log');
        foreach ($ips[0] as $v) {
            if (in_array($v, $res_ip)) {
                continue;
            }
            $res_ip[] = $v;
            $ip_add = app('IpLocation')->getLocation($v);
            $all[]  = $result = '[' . $v . ']:' . $ip_add['country'] . ' ' . $ip_add['area'];
            file_put_contents($file, $result . PHP_EOL, FILE_APPEND);
        }
        info($path . ' 解析结果', $all);
        $this->info('共匹配' . $res . '条，共过滤并解析' . count($all) . '条，解析结果请查看log文件');
    }
}
