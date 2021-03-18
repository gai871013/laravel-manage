<?php

use Illuminate\Database\Seeder;

class AdminActionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('admin_actions')->insert([
            [
                "id" => 1,
                "parent_id" => 0,
                "code" => "user",
                "lang" => "user",
                "route" => "",
                "param" => "",
                "enable" => 1,
                "remark" => "用户管理",
                "icon" => "users",
                "list_order" => 6
            ],
            [
                "id" => 2,
                "parent_id" => 0,
                "code" => "permissions",
                "lang" => "permissions",
                "route" => "",
                "param" => "",
                "enable" => 1,
                "remark" => "权限管理",
                "icon" => "wrench",
                "list_order" => 11
            ],
            [
                "id" => 3,
                "parent_id" => 0,
                "code" => "system",
                "lang" => "system",
                "route" => "",
                "param" => "",
                "enable" => 1,
                "remark" => "系统管理",
                "icon" => "cogs",
                "list_order" => 17
            ],
            [
                "id" => 4,
                "parent_id" => 1,
                "code" => "userManage",
                "lang" => "userManage",
                "route" => "",
                "param" => "",
                "enable" => 1,
                "remark" => "用户管理",
                "icon" => "users",
                "list_order" => 7
            ],
            [
                "id" => 5,
                "parent_id" => 1,
                "code" => "userEdit",
                "lang" => "userEdit",
                "route" => "",
                "param" => "",
                "enable" => 0,
                "remark" => "编辑用户",
                "icon" => "pencil",
                "list_order" => 8
            ],
            [
                "id" => 6,
                "parent_id" => 1,
                "code" => "userDelete",
                "lang" => "userDelete",
                "route" => "",
                "param" => "",
                "enable" => 0,
                "remark" => "删除用户",
                "icon" => "trash",
                "list_order" => 9
            ],
            [
                "id" => 7,
                "parent_id" => 2,
                "code" => "adminManage",
                "lang" => "adminManage",
                "route" => "",
                "param" => "",
                "enable" => 1,
                "remark" => "后台用户管理",
                "icon" => "users",
                "list_order" => 12
            ],
            [
                "id" => 8,
                "parent_id" => 2,
                "code" => "roleManage",
                "lang" => "roleManage",
                "route" => "",
                "param" => "",
                "enable" => 1,
                "remark" => "角色管理",
                "icon" => "book",
                "list_order" => 13
            ],
            [
                "id" => 9,
                "parent_id" => 2,
                "code" => "profile",
                "lang" => "profile",
                "route" => null,
                "param" => "",
                "enable" => 0,
                "remark" => "用户资料",
                "icon" => "address-card-o",
                "list_order" => 14
            ],
            [
                "id" => 10,
                "parent_id" => 2,
                "code" => "roleEdit",
                "lang" => "roleEdit",
                "route" => null,
                "param" => "",
                "enable" => 0,
                "remark" => "",
                "icon" => "pencil",
                "list_order" => 15
            ],
            [
                "id" => 11,
                "parent_id" => 2,
                "code" => "roleDelete",
                "lang" => "roleDelete",
                "route" => null,
                "param" => "",
                "enable" => 0,
                "remark" => "",
                "icon" => "trash-o",
                "list_order" => 16
            ],
            [
                "id" => 12,
                "parent_id" => 3,
                "code" => "menuManage",
                "lang" => "menuManage",
                "route" => "",
                "param" => "",
                "enable" => 1,
                "remark" => "菜单管理",
                "icon" => "book",
                "list_order" => 18
            ],
            [
                "id" => 13,
                "parent_id" => 3,
                "code" => "menuEdit",
                "lang" => "addMenu",
                "route" => "admin.system.menuEdit",
                "param" => "",
                "enable" => 0,
                "remark" => "添加菜单",
                "icon" => "plus",
                "list_order" => 19
            ],
            [
                "id" => 14,
                "parent_id" => 3,
                "code" => "config",
                "lang" => "systemConfig",
                "route" => "",
                "param" => "",
                "enable" => 1,
                "remark" => "系统设置",
                "icon" => "cog",
                "list_order" => 20
            ],
            [
                "id" => 15,
                "parent_id" => 25,
                "code" => "follower",
                "lang" => "follower",
                "route" => null,
                "param" => "",
                "enable" => 1,
                "remark" => "用户管理",
                "icon" => "users",
                "list_order" => 10
            ],
            [
                "id" => 16,
                "parent_id" => 3,
                "code" => "operationLogs",
                "lang" => "operationLogs",
                "route" => null,
                "param" => "",
                "enable" => 1,
                "remark" => "操作记录",
                "icon" => "clock-o",
                "list_order" => 21
            ],
            [
                "id" => 17,
                "parent_id" => 0,
                "code" => "news",
                "lang" => "news",
                "route" => null,
                "param" => "",
                "enable" => 1,
                "remark" => "内容管理",
                "icon" => "newspaper-o",
                "list_order" => 1
            ],
            [
                "id" => 18,
                "parent_id" => 17,
                "code" => "newsList",
                "lang" => "newsList",
                "route" => null,
                "param" => "",
                "enable" => 1,
                "remark" => "文章管理",
                "icon" => "bars",
                "list_order" => 2
            ],
            [
                "id" => 19,
                "parent_id" => 17,
                "code" => "categories",
                "lang" => "categories",
                "route" => null,
                "param" => "",
                "enable" => 1,
                "remark" => "分类目录",
                "icon" => "code-fork",
                "list_order" => 3
            ],
            [
                "id" => 20,
                "parent_id" => 17,
                "code" => "comment",
                "lang" => "comment",
                "route" => null,
                "param" => "",
                "enable" => 1,
                "remark" => "评论管理",
                "icon" => "comments-o",
                "list_order" => 4
            ],
            [
                "id" => 21,
                "parent_id" => 17,
                "code" => "pages",
                "lang" => "pages",
                "route" => null,
                "param" => "",
                "enable" => 1,
                "remark" => "页面管理",
                "icon" => "clone",
                "list_order" => 5
            ],
            [
                "id" => 22,
                "parent_id" => 15,
                "code" => "lists",
                "lang" => "lists",
                "route" => null,
                "param" => "",
                "enable" => 1,
                "remark" => "粉丝列表",
                "icon" => "list",
                "list_order" => 10
            ],
            [
                "id" => 23,
                "parent_id" => 15,
                "code" => "tags",
                "lang" => "tags",
                "route" => null,
                "param" => "",
                "enable" => 1,
                "remark" => "标签管理",
                "icon" => "tags",
                "list_order" => 10
            ],
            [
                "id" => 24,
                "parent_id" => 15,
                "code" => "groups",
                "lang" => "groups",
                "route" => null,
                "param" => "",
                "enable" => 1,
                "remark" => "分组管理",
                "icon" => "group",
                "list_order" => 10
            ],
            [
                "id" => 25,
                "parent_id" => 0,
                "code" => "WeChat",
                "lang" => "WeChat",
                "route" => null,
                "param" => "",
                "enable" => 1,
                "remark" => "微信管理",
                "icon" => "wechat",
                "list_order" => 10
            ],
            [
                "id" => 26,
                "parent_id" => 25,
                "code" => "material",
                "lang" => "material",
                "route" => null,
                "param" => "",
                "enable" => 1,
                "remark" => "素材管理",
                "icon" => "file-archive-o",
                "list_order" => 10
            ],
            [
                "id" => 27,
                "parent_id" => 26,
                "code" => "forever",
                "lang" => "forever",
                "route" => null,
                "param" => "",
                "enable" => 1,
                "remark" => "永久素材",
                "icon" => "database",
                "list_order" => 10
            ],
            [
                "id" => 28,
                "parent_id" => 26,
                "code" => "temporary",
                "lang" => "temporary",
                "route" => null,
                "param" => "",
                "enable" => 1,
                "remark" => "临时素材",
                "icon" => "history",
                "list_order" => 10
            ],
            [
                "id" => 29,
                "parent_id" => 25,
                "code" => "menu",
                "lang" => "menu",
                "route" => null,
                "param" => "",
                "enable" => 1,
                "remark" => "菜单管理",
                "icon" => "sitemap",
                "list_order" => 10
            ],
            [
                "id" => 30,
                "parent_id" => 25,
                "code" => "message",
                "lang" => "message",
                "route" => null,
                "param" => "",
                "enable" => 1,
                "remark" => "自动回复",
                "icon" => "comments-o",
                "list_order" => 10
            ],
            [
                "id" => 31,
                "parent_id" => 25,
                "code" => "broadcast",
                "lang" => "broadcast",
                "route" => null,
                "param" => "",
                "enable" => 1,
                "remark" => "消息群发",
                "icon" => "object-group",
                "list_order" => 10
            ]]);
    }
}
