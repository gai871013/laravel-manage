<?php
/**
 * Created by PhpStorm.
 * User: wanggaichao @gai871013
 * Date: 2017-08-15
 * Time: UTC/GMT+08:00 10:38
 * laravel-manage/NewsRepository.php
 */

namespace App\Repositories;


use App\Models\News;

class NewsRepository
{
    /**
     * 注入的News model
     * @var News
     */
    protected $news;

    /**
     * 构造函数
     * NewsRepository constructor.
     * @param News $news
     */
    public function __construct(News $news)
    {
        $this->news = $news;
    }

    /**
     * 获取指定新闻列表分页
     * @param $where
     * @param $page_size
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginate($where, $page_size = 0)
    {
        if ($page_size == 0) {
            $page_size = env('PAGE_SIZE');
        }
        return $this->news->orderBy('id', 'desc')->where($where)->paginate($page_size);
    }

    /**
     * 查找指定ID的新闻 2017-8-15 11:28:12 by gai871013
     * @param string $value
     * @param string $field
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public function find($value = '', $field = '')
    {
        if (empty($field))
            return $this->news->find($value);
        else
            return $this->news->where($field, $value)->first();
    }

    /**
     * 保存 2017-8-15 11:26:11 by gai871013
     * @param array $where
     * @param array $update
     * @return $this|bool|\Illuminate\Database\Eloquent\Model
     */
    public function save($update = [], $where = [])
    {
        if (empty($where)) {
            return $this->news->create($update);
        }
        return $this->news->where($where)->update($update);
    }

    /**
     * 删除 2017-8-15 11:51:05 by gai871013
     * @param $value
     * @param string $field
     * @return bool|null
     */
    public function delete($value, $field = '')
    {
        if (empty($field))
            return $this->news->where('id', $value)->delete();
        else
            return $this->news->where($field, $value)->delete();
    }
}