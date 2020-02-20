<?php

namespace App\Traits\OrganicGroup;

use App\Models\OrganicGroup;

trait Groupable
{
	// $this->is_groupable
    public function getIsGroupableAttribute()
    {
        return true;
    }

    // $live->members() 获取直播的成员
    // TODO:  morphMany  hasManyThrough ？?
    public function members()
    {
        return OrganicGroup::where('groupable_type', self::CLASS)
            ->where('groupable_id', $this->id)
            ->get()
            ->map(function($og){
                return $og->memberable_type::find($og->memberable_id);
            });
    }

}
