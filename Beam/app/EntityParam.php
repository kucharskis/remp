<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EntityParam extends Model
{
    use SoftDeletes;

    const TYPE_STRING = "string";
    const TYPE_STRING_ARRAY = "string_array";
    const TYPE_NUMBER = "number";
    const TYPE_NUMBER_ARRAY = "number_array";
    const TYPE_BOOLEAN = "boolean";
    const TYPE_DATETIME = "datetime";

    protected $table = 'entity_params';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'name',
        'type'
    ];

    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }

    public static function getAllTypes()
    {
        return [
            self::TYPE_STRING => __("entities.types." . self::TYPE_STRING),
            self::TYPE_STRING_ARRAY => __("entities.types." . self::TYPE_STRING_ARRAY),
            self::TYPE_NUMBER => __("entities.types." . self::TYPE_NUMBER),
            self::TYPE_NUMBER_ARRAY => __("entities.types." . self::TYPE_NUMBER_ARRAY),
            self::TYPE_BOOLEAN => __("entities.types." . self::TYPE_BOOLEAN),
            self::TYPE_DATETIME => __("entities.types." . self::TYPE_DATETIME),
        ];
    }
}
