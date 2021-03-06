<?php

namespace App\Admin\Models;

use App\Admin\Traits\ModelTree;

class SystemMediaCategory extends Model
{
    use ModelTree {
        delete as modelTreeDelete;
    }

    protected $fillable = ['parent_id', 'name', 'folder'];

    protected $casts = [
        'parent_id' => 'integer',
    ];

    public function media()
    {
        return $this->hasMany(SystemMedia::class, 'category_id');
    }

    public function setParentIdAttribute($value)
    {
        $this->attributes['parent_id'] = $value ?: 0;
    }

    protected function orderColumn()
    {
        return 'id';
    }

    public function delete()
    {
        $res = $this->modelTreeDelete();
        $this->media()->update(['category_id' => 0]);

        return $res;
    }

    /**
     * 格式化 folder 的值
     *
     * @param $value
     */
    public function setFolderAttribute($value)
    {
        $value = strtolower(implode('/', array_filter(explode('/', (string) $value))));

        $this->attributes['folder'] = $value ?: null;
    }
}
