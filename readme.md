# Laravel Manage System

基于Laravel的后台管理系统

## 服务器要求
本系统基于Laravel开发完成，Laravel 框架会有一些系统上的要求。当然，这些要求在 Laravel Homestead 虚拟机上都已经完全配置好了。所以，非常推荐你使用 Homestead 作为你的本地 Laravel 开发环境。
如果你没有使用 Homestead ，你需要确保你的服务器上安装了下面的几个拓展：
```
PHP >= 5.6.4
OpenSSL PHP Extension
PDO PHP Extension
Mbstring PHP Extension
Tokenizer PHP Extension
XML PHP Extension
composer
node
npm
```   
## 安装前准备
### 1.composer
本系统需要使用composer包管理系统，
#### 下载[composer](https://getcomposer.org/download/)

### 2.npm包管理工具
#### 推荐[链接在此](http://www.runoob.com/nodejs/nodejs-npm.html)


## 安装
#### 1.修改配置文件
```shell
copy .env.example .env
```

#### 2.程序依赖安装
```shell
composer install
```


#### 3.数据迁移
```shell
php artisan migrate
```

#### 4.数据填充
```shell
php artisan db:seed --class=AdminActionTableSeeder
php artisan db:seed --class=RoleTableSeeder
php artisan db:seed --class=AdminsTableSeeder
```

#### 5.前台资源
```shell
npm install
```

#### 6.前台资源编译
##### ① 生产环境
```shell
npm run production
```
##### ② 开发环境
```shell
npm run dev
```
**监听**
```
npm run watch
```
可用参数
```shell
watch-poll
hot
```

登录
```
地址 http://domain/admin
账号 wang.gaichao@163.com
密码 admin888
```

