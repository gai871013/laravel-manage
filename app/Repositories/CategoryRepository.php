<?php
/**
 * Created by PhpStorm.
 * User: wanggaichao @gai871013
 * Date: 2017-08-15
 * Time: UTC/GMT+08:00 10:44
 * laravel-manage/CategoryRepository.php
 */

namespace App\Repositories;


use App\Models\Categories;

/**
 * Class CategoryRepository
 * @package App\Repositories
 */
class CategoryRepository
{
    /**
     * 注入的Category model
     * @var Categories
     */
    protected $category;

    /**
     * 2017-8-15 11:22:47 by gai871013
     * CategoryRepository constructor.
     * @param Categories $categories
     */
    public function __construct(Categories $categories)
    {
        $this->category = $categories;
    }

    /**
     * 获取分类信息 2017-8-15 11:22:30 by gai871013
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function get(array $where = [])
    {
        return $this->category->where($where)->orderBy('sort', 'asc')->orderBy('id', 'desc')->get();
    }

    /**
     * 获取指定ID的分类 2017-8-15 11:22:34 by gai871013
     * @param string $value
     * @param string $field
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public function find($value = '', $field = '')
    {
        if (empty($field))
            return $this->category->find($value);
        else
            return $this->category->where($field, $value)->first();
    }

    /**
     * 查找取出并添加锁 2017-8-15 11:22:27 by gai871013
     * @param $id
     * @param array $where
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function findForUpdate(array $where = [])
    {
        return $this->category->where($where)->lockForUpdate()->first();
    }

    /**
     * 保存 2017-8-15 11:26:11 by gai871013
     * @param array $where
     * @param array $update
     * @return $this|bool|\Illuminate\Database\Eloquent\Model
     */
    public function save(array $update = [], array $where = [])
    {
        if (empty($where)) {
            return $this->category->create($update);
        }
        return $this->category->where($where)->update($update);
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
            return $this->category->where('id', $value)->delete();
        else
            return $this->category->where($field, $value)->delete();
    }
}