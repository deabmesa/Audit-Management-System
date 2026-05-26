<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'name','route','controller','service',
        'icon','parent_id','permission','order_no'
    ];

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')
            ->with('children.children.children.children')
            ->orderBy('order_no');
    }

    public function childrenRecursive()
    {
        return $this->hasMany(Menu::class, 'parent_id')
            ->with('childrenRecursive')
            ->orderBy('order_no');
    }

    // 🔥 CRITICAL: DO NOT USE route()
    public function getRouteUrl(): string
    {
        if (!empty($this->route)) {
            return url($this->route);
        }

        // parent menu → go to first child
        $child = $this->children()->first();

        if ($child && !empty($child->route)) {
            return url($child->route);
        }

        return '#';
    }
}