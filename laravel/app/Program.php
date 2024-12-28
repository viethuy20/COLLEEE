<?php
namespace App;

use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;
use WrapPhp;
use App\Device\Device;
use DateTimeInterface;

/**
 * プログラム.
 */
class Program extends Model
{
    use DBTrait;
    /**
     * モデルに関連付けるデータベースのテーブルを指定.
     * @var string
     */
    protected $table = 'programs';

    /**
     * createメソッド実行時に、入力を禁止するカラムの指定.
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * 日付を変形する属性
     * @var array
     */
    protected $dates = ['start_at', 'stop_at', 'released_at', 'deleted_at'];
    
    protected $casts = [
        'start_at' => 'datetime',
        'stop_at' => 'datetime',
        'released_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /** タイムセールタグID. */
    const TIMESALE_TAG_ID = 216;

    const DEFAULT_SORT = 0;
    const POINT_VALUE_SORT = 1;
    const POINT_RATE_SORT = 2;
    const NEW_SORT = 3;

    /**
     * Add extra attribute.
     */
    protected $appends = ['join_status' => null];

/**
     * 配列／JSONシリアライズのためデータを準備する
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'program_tags', 'program_id', 'tag_id')
            ->wherePivot('status', 0);
    }
    public function courses()
    {
        return $this->hasMany(Course::class, 'program_id', 'id');
    }
    public function points()
    {
        return $this->hasMany(Point::class, 'program_id', 'id');
    }
    // @codingStandardsIgnoreStart
    public function user_points()
    {
        // @codingStandardsIgnoreEnd
        return $this->hasMany(UserPoint::class, 'parent_id', 'id')
            ->where('type', '=', UserPoint::PROGRAM_TYPE);
    }
    public function affiriates()
    {
        return $this->hasMany(Affiriate::class, 'parent_id', 'id')
            ->where('parent_type', '=', Affiriate::PROGRAM_TYPE);
    }
    public function schedule()
    {
        return $this->hasOne(ProgramSchedule::class, 'program_id', 'id')
            ->ofEnable();
    }

    public function programStock()
    {
        return $this->hasOne(ProgramStock::class, 'program_id', 'id');
    }

    public function reviewsAccepted()
    {
        return $this->hasMany(Review::class, 'program_id', 'id')
            ->where('reviews.status', Review::ACCEPTED);
    }

    public function questions()
    {
        return $this->hasMany(ProgramQuestion::class, 'program_id', 'id');
    }

    public function campaigns()
    {
        return $this->hasMany(ProgramCampaign::class, 'program_id', 'id');
    }

    /**
     * 有効.
     * @return bool trueの場合は有効,falseの場合は無効
     */
    public function getIsEnableAttribute() :bool
    {
        $now = Carbon::now();
        return $this->status == 0 && ($this->start_at->lte($now) && $this->stop_at->gte($now));
    }

    public function scopeOfEnable($query)
    {
        $now = Carbon::now();
        return $query->where($this->table.'.status', '=', 0)
            ->where($this->table.'.stop_at', '>=', $now)
            ->where($this->table.'.start_at', '<=', $now);
    }

    public function scopeOfEnableDevice($query)
    {
        $device_id = Device::getDeviceId();
        return $query->ofEnable()
            ->whereRaw($this->table.'.devices & ? > 0', [1 << ($device_id - 1)]);
    }

    public function scopeOfList($query)
    {
        /**
         *  Field devices in programs table
         *
         *  PC: 1
         *  IOS: 2
         *  Android: 4
         *  PC + IOS: 3
         *  PC + Android: 5
         *  IOS + Android: 6
         *  PC + IOS + Android: 7
         */
        return $query->ofEnableDevice()
            ->where($this->table.'.list_show', '=', 1);
    }

    public function scopeOfTimeSale($query, bool $today_only = false)
    {
        return $query->ofList()
            ->whereIn('programs.id', function ($query) use ($today_only) {
                $now = Carbon::now();
                $query->select('program_id')
                    ->from('points')
                    ->where('time_sale', '=', 1)
                    ->where('stop_at', '>=', $now)
                    ->where('start_at', '<=', $now);
                if ($today_only) {
                    $query->where('today_only', '=', 1)
                        ->where('stop_at', '<=', $now->copy()->endOfDay());
                }
            });
    }

    public function scopeOfShopCategory($query, int $shop_category_id)
    {
        $shop_category_id_list = array_keys(config('map.shop_category'));
        // カテゴリが不正な場合
        if ($shop_category_id > max($shop_category_id_list) ||
                $shop_category_id < min($shop_category_id_list)) {
            return $query;
        }

        $shop_category_mask = 1 << ($shop_category_id - 1);
        return $query->whereRaw('programs.shop_categories & ? > 0', [$shop_category_mask]);
    }

    public function scopeOfTag($query, array $tag_id_list)
    {
        return $query->whereIn('programs.id', function ($query) use ($tag_id_list) {
            $query->select('program_id')
                ->from('program_tags')
                ->where('status', '=', 0)
                ->whereIn('tag_id', $tag_id_list);
        });
    }

    public function scopeOfKeyword($query, array $keyword_list)
    {
        // キーワードが空の場合
        if (empty($keyword_list)) {
            return $query;
        }

        foreach ($keyword_list as $keyword) {
            $tag = Tag::where('name', '=', $keyword)
                ->where('status', '=', 0)
                ->first();
            // タグが存在する場合はタグ検索
            if (isset($tag->id)) {
                // タイムセールタグの場合
                if ($tag->id == self::TIMESALE_TAG_ID) {
                    $query->ofTimeSale();
                    continue;
                }
                $query->ofTag([$tag->id]);
                continue;
            }
            // タグが存在しない場合はキーワード検索
            $query->whereRaw(
                '`programs`.`keyword_index` COLLATE utf8mb4_unicode_ci LIKE ?',
                ['%'.addcslashes($keyword, '\_%').'%']
            );
        }
        return $query;
    }
    public function scopeOfLabel($query, array $label_id_list)
    {
        // ラベルが空の場合
        if (empty($label_id_list)) {
            return $query;
        }

        $label_total = WrapPhp::count($label_id_list);
        if ($label_total == 1) {
            return $query->whereIn('programs.id', function ($query) use ($label_id_list) {
                $query->select('program_id')
                    ->from('program_labels')
                    ->where('status', '=', 0)
                    ->where('label_id', $label_id_list[0]);
            });
        }

        $query->whereIn('programs.id', function ($query) use ($label_id_list, $label_total) {
            $program_label_group_query = DB::table('program_labels')
                ->select('program_id', DB::raw('COUNT(label_id) as total'))
                ->where('status', '=', 0)
                ->whereIn('label_id', $label_id_list)
                ->groupBy('program_id');
            $query->select('pl.program_id')
                ->fromSub($program_label_group_query, 'pl')
                ->where('pl.total', '>=', $label_total);
        });

        return $query;
    }

    public function scopeOfContentSpot($query, int $content_spot_id)
    {
        // コンテンツが空の場合
        if (empty($content_spot_id)) {
            return $query;
        }

        $device_id = Device::getDeviceId();
        $device_mask = 1 << ($device_id - 1);
        $now = Carbon::now();

        $content_query = Content::select('data', 'priority')
            ->where('contents.status', '=', 0)
            ->where('contents.spot_id', '=', $content_spot_id)
            ->where('contents.stop_at', '>', $now)
            ->where('contents.start_at', '<=', $now)
            ->whereRaw('contents.devices & ? > 0', [$device_mask])
            ->orderBy('contents.priority', 'asc');

        // MySQL用の記述方法なのでDB切り替え時は注意
        $query = $query->joinSub($content_query, 'contents', function ($join) {
                    $join->on('programs.id', '=', 'contents.data->program_id');
                 })->orderBy('contents.priority', 'asc');


        return $query;
    }

    public function scopeOfSort($query, int $sort)
    {
        switch ($sort) {
            case self::POINT_VALUE_SORT:
                // ポイント数順
                $query = $query->orderBy('points.fee_type', 'asc')
                    ->orderBy('points.point', 'desc')
                    ->orderBy('points.rate', 'desc');
                break;
            case self::POINT_RATE_SORT:
                // ポイント率順
                $query = $query->orderBy('points.fee_type', 'desc')
                    ->orderBy('points.rate', 'desc')
                    ->orderBy('points.point', 'desc');
                break;
            case self::NEW_SORT:
                // 新着順
                $query = $query->orderBy('programs.released_at', 'desc');
                break;
            default:
                // おすすめ順
                $query = $query->orderBy('programs.priority', 'desc')
                    ->orderBy('programs.released_at', 'desc');
                break;
        }
        // 最後にプログラムIDでソート
        return $query->orderBy('programs.id', 'desc');
    }

    public function scopeOfRank($query, $statDate = null, $endDate = null)
    {
        $now = Carbon::now();
        $end = $now->copy()->subDay();

        $program_click_cnt_query = DB::table('external_links')
            ->select('program_id', DB::raw('COUNT(program_id) as total'))
            ->whereBetween('created_at', [$end, $now])
            ->groupBy('program_id');

        $program_rank_query = $query->select('programs.*')
            ->joinSub($program_click_cnt_query, 'program_click_cnt', function ($join) {
                $join->on('programs.id', '=', 'program_click_cnt.program_id');
                })
            ->orderBy('program_click_cnt.total', 'desc');

        return $program_rank_query;
    }

    public function isEnableDevice()
    {
        $device_id = Device::getDeviceId();
        return (($this->devices & (1 << ($device_id - 1))) > 0);
    }

    public function getAffiriateAttribute() : Affiriate
    {
        // 値を持っていた場合
        if (isset($this->appends['affiriate'])) {
            return $this->appends['affiriate'];
        }
        $this->appends['affiriate'] = $this->affiriates()
            ->ofEnable()
            ->first();
        return $this->appends['affiriate'];
    }

    public function getPointAttribute()
    {
        // 値を持っていた場合
        if (isset($this->appends['point'])) {
            return $this->appends['point'];
        }

        $this->appends['point'] = PointList::getPointList($this->id);

        return $this->appends['point'];
    }

    public function getPointList()
    {
        if (isset($this->appends['point'])) {
            return $this->appends['point']->point_list;
        }
        return collect();
    }

    public function getRewardConditionAttribute()
    {
        return $this->schedule->reward_condition;
    }

    public function getTagListAttribute() : array
    {
        // 値を持っていた場合
        if (isset($this->appends['tag_list'])) {
            return $this->appends['tag_list'];
        }

        $this->appends['tag_list'] = $this->tags()
            ->pluck('name')
            ->all();

        // タイムセールタグを追加
        if ($this->point->time_sale == 1) {
            $tag = Tag::find(self::TIMESALE_TAG_ID);
            if (isset($tag->id)) {
                $this->appends['tag_list'][] = $tag->name;
            }
        }

        return $this->appends['tag_list'];
    }

    /**
     * 参加状態.
     * @return int 0:未参加,1:参加済み,2:複数参加,3:獲得予定,4:未認証
     */
    public function getJoinStatusAttribute() : int
    {
        // 認証していない
        if (!Auth::check()) {
            return 4;
        }

        // 値を持っていた場合
        if (isset($this->appends['join_status'])) {
            return $this->appends['join_status'];
        }

        $user_id = Auth::user()->id;
        // ユーザーポイント取得
        $user_point = $this->user_points()
            ->where('user_id', '=', $user_id)
            ->first();

        // 参加済みの場合
        if (isset($user_point->id)) {
            // 複数参加状態を確認
            $this->appends['join_status'] = 1;
            return $this->appends['join_status'];
        }

        $arririate = $this->affiriates()->first();
        // アフィリエイトが見つからなかった場合
        if (!isset($arririate->id)) {
            $this->appends['join_status'] = 0;
            return $this->appends['join_status'];
        }

        // 待ち状態の成果を取得
        $arr_reward = $arririate->aff_rewards()
            ->ofWaiting()
            ->where('user_id', '=', $user_id)
            ->first();
        if (isset($arr_reward->id)) {
            $this->appends['join_status'] = 2;
            return $this->appends['join_status'];
        }

        $this->appends['join_status'] = 0;
        return $this->appends['join_status'];
    }

    public function getScheduleWithActionedAt($actioned_at)
    {
        return $this->hasOne(ProgramSchedule::class, 'program_id', 'id')
            ->where('stop_at', '>=', $actioned_at)
            ->where('start_at', '<=', $actioned_at)->first();
    }


    public function isMultiCourse() : bool
    {
        return $this->multi_course == 1;
    }
}
