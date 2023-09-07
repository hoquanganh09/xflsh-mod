<?php

namespace App\Models;

use App\Models\Traits\Serialize;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\TrafficUserLog
 *
 * @property int $id
 * @property int $user_id
 * @property string $u
 * @property string $d
 * @property int $log_at
 * @property string $log_date
 * @property int $created_at
 * @property int $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TrafficUserLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrafficUserLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrafficUserLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|TrafficUserLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrafficUserLog whereD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrafficUserLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrafficUserLog whereLogAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrafficUserLog whereU($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrafficUserLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrafficUserLog whereUserId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|TrafficUserLog whereLogDate($value)
 */
class TrafficUserLog extends Model
{
    use Serialize;
    
    const FIELD_ID = "id";
    const FIELD_U = "u";
    const FIELD_D = "d";
    CONST FIELD_USER_ID = "user_id";
    const FIELD_LOG_AT = "log_at";
    const FIELD_LOG_DATE = 'log_date';
    const FIELD_CREATED_AT = "created_at";
    const FIELD_UPDATED_AT = "updated_at";
    protected $table = 'traffic_user_log';
    protected $dateFormat = 'U';

    protected $casts = [
        self::FIELD_CREATED_AT => 'timestamp',
        self::FIELD_UPDATED_AT => 'timestamp'
    ];

    /**
     * add traffic
     *
     * @param int $u
     * @param int $d
     * @return bool
     */
    public function addTraffic(int $u, int $d): bool
    {
        $this->setAttribute(User::FIELD_U, $this->getAttribute(User::FIELD_U) + $u);
        $this->setAttribute(User::FIELD_D, $this->getAttribute(User::FIELD_D) + $d);
        return true;
    }
}