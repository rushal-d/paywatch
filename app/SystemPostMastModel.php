<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;

class SystemPostMastModel extends Model
{
    //
    use SearchableTrait;
    use SoftDeletes;
    protected $table = 'system_post_mast';
    protected $primaryKey = 'post_id';
    protected $searchable = [
        'columns' => [
            'post_title' => 1
        ]
    ];

    public const ACTIVE_STATUS = 1;
    public const INACTIVE_STATUS = 0;

    public function grade()
    {
        return $this->belongsTo('App\GradeModel', 'grade_id');
    }

    public function staffs()
    {
        return $this->hasMany(StafMainMastModel::class, 'post_id');
    }

    public function parentPost()
    {
        return $this->belongsTo(SystemPostMastModel::class, 'parent_id');
    }

    public function getParentsAttribute()
    {
        $parents = collect([]);

        $parent = $this->parentPost;

        while(!is_null($parent)) {
            $parents->push($parent);
            $parent = $parent->parentPost;
        }

        return $parents;
    }
}
