<?php

namespace App\OrganicGroup\Traits;

use App\Models\OrganicGroup;

trait Groupable
{
	// $this->groupable
    public function getGroupableAttribute()
    {
        return true;
    }

    // $live->members 获取直播的成员
    public function members()
    {
    	// $related, $name, $type = null, $id = null, $localKey = null
        return $this->morphMany(OrganicGroup::class, 'memberable',
            'groupable_type', 
            'groupable_id', 
            'id' //lives.id
        );
    }

}
