<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Word extends Model
{

    public function getParentIdsAttribute()
    {
        return $this->getParentIds($this, []);
    }
    public function getParentAttribute()
    {
        return self::find($this->parent_id);
    }
    public function getResultTextAttribute()
    {
        if (is_null($this->result)) {
            return '関係ありません';
        }
        $text = $this->result ? 'はい' : 'いいえ';
        return $text;
    }

    public function getParentIds($element, $parents)
    {
        if (is_null($element->parent)) {
            return $parents;
        }
        $parents[$element->parent->level] = $element->parent->id;
        return $this->getParentIds($element->parent, $parents);
    }
}
