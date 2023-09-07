<?php

namespace App\Models;

use App\Models\Traits\Serialize;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\ServerLog
 *
 * @property int $id
 * @property int $user_id
 * @property int $server_id
 * @property string $sever_type 服务器类型
 * @property string $u
 * @property string $d
 * @property string $rate
 * @property string $method
 * @property string $log_date
 * @property string $server_type
 * @property int $log_at
 * @property int $created_at
 * @property int $updated_at
 * @method static Builder|TrafficServerLog newModelQuery()
 * @method static Builder|TrafficServerLog newQuery()
 * @method static Builder|TrafficServerLog query()
 * @method static Builder|TrafficServerLog whereCreatedAt($value)
 * @method static Builder|TrafficServerLog whereD($value)
 * @method static Builder|TrafficServerLog whereId($value)
 * @method static Builder|TrafficServerLog whereLogAt($value)
 * @method static Builder|TrafficServerLog whereMethod($value)
 * @method static Builder|TrafficServerLog whereRate($value)
 * @method static Builder|TrafficServerLog whereServerId($value)
 * @method static Builder|TrafficServerLog whereU($value)
 * @method static Builder|TrafficServerLog whereUpdatedAt($value)
 * @method static Builder|TrafficServerLog whereUserId($value)
 * @method static Builder|TrafficServerLog whereLogDate($value)
 * @method static Builder|TrafficServerLog whereSeverType($value)
 * @method static Builder|TrafficServerLog whereServerType($value)
 * @mixin Eloquent
 */
class TrafficServerLog extends Model
{
    use Serialize;
    
    const FIELD_ID = "id";
    const FIELD_SERVER_ID = "server_id";
    const FIELD_SERVER_TYPE = "server_type";
    const FIELD_U = "u";
    const FIELD_D = "d";
    const FIELD_LOG_DATE = 'log_date';
    const FIELD_LOG_AT = "log_at";
    const FIELD_CREATED_AT = "created_at";
    const FIELD_UPDATED_AT = "updated_at";
    protected $table = 'traffic_server_log';
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