<?php

namespace App\Models;

use App\Models\Traits\Serialize;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ServerArea
 *
 * @property int $id
 * @property string $flag
 * @property string $country
 * @property string $country_code
 * @property string $city
 * @property float $lng
 * @property float $lat
 * @property int $created_at
 * @property int $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ServerArea newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServerArea newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServerArea query()
 * @method static \Illuminate\Database\Eloquent\Builder|ServerArea whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServerArea whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServerArea whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServerArea whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServerArea whereFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServerArea whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServerArea whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServerArea whereLng($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServerArea whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ServerArea extends Model
{
    use Serialize;

    const FIELD_ID = "id";
    const FIELD_FLAG = "flag";
    const FIELD_COUNTRY = "country";
    const FIELD_COUNTRY_CODE = "country_code";
    const FIELD_CITY = "city";
    const FIELD_LNG = "lng";
    const FIELD_LAT = "lat";
    const FIELD_CREATED_AT = "created_at";
    const FIELD_UPDATED_AT = "updated_at";

    protected $table = 'server_area';
    protected $dateFormat = 'U';

    protected $casts = [
        self::FIELD_CREATED_AT => 'timestamp',
        self::FIELD_UPDATED_AT => 'timestamp',
    ];
}