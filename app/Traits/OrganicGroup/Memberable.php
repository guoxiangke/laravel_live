<?php

namespace App\Traits\OrganicGroup;

use App\Models\OrganicGroup;

trait Memberable
{
	// $user->is_memberable = ture;
	public function getIsMemberableAttribute()
	{
	    return true;
	}
    // TODO:  morphMany  hasManyThrough withPivot？?
    // $user->groups() 获取用户的所有小组
    public function groups()
    {
        return OrganicGroup::where('memberable_type', self::CLASS)
            ->where('memberable_id', $this->id)
            ->get()
            ->map(function($og){
                return $og->groupable_type::find($og->groupable_id);
            });
    }
}
