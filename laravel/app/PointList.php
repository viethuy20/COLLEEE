<?php
namespace App;

use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * ポイントの集合体.
 * ポイント操作に関するビジネスルールを定義.
 */
class PointList extends Point
{
    public static function getPointList($program_id = null) : PointList
    {
        $all_point = new self();
        if (is_null($program_id)) {
            $all_point->points = collect();
        } else {
            $all_point->program_id = $program_id;

            $query = Point::where('points.program_id', $all_point->program_id)
                        ->ofEnable();

            // コースの場合表示順序を考慮する必要があるため修正
            $isCourse = (clone $query)
                ->where('points.course_id', '!=', null)
                ->count() > 0;
            if ($isCourse) {
                $query
                    ->join('courses', 'points.course_id', '=', 'courses.id')
                    ->orderBy('courses.priority', 'asc')
                    ->select('points.*')
                    ->get();
            }

            $all_point->point_list = $query->get();
        }
        return $all_point;
    }

    // override
    public function getPointAttribute() : ?float
    {
        return $this->calcTotalPoint();
    }

    // override
    public function getRatePercentAttribute() : ?float
    {       
        return $this->calcTotalRate() * 100;
    }

    public function getBonusAttribute() : int
    {
        return $this->point_list->where('bonus', 1)->count() > 0 ? 1 : 0;
    }
    
    private function calcTotalPoint() : ?float
    {
        return $this->point_list->where('fee_type', 1)->sum('point');
    }

    private function calcTotalRate() : ?float
    {
        return $this->point_list->where('fee_type', 2)->sum('rate');
    }

    // override
    public function getRateAttribute() : ?float
    {
        return $this->calcTotalRate();
    }

    // override
    public function getFullPointAttribute() : int
    {
        // 複数種類のポイントがある場合は、全還元を優先
        return $this->point_list->where('all_back', 1)->count() > 0 ? 1 : 0;
    }

    // override
    public function getTimeSaleAttribute() : int
    {
        // 複数種類のポイントがある場合は、タイムセールを優先
        return $this->point_list->where('time_sale', 1)->count() > 0 ? 1 : 0;
    }

    // override
    public function getTodayOnlyAttribute() : int
    {
        // 複数種類のポイントがある場合は、当日限定を優先
        return $this->point_list->where('today_only', 1)->count() > 0 ? 1 : 0;
    }

    // override
    public function getFeeTypeAttribute() : int
    {
        // 複数種類のポイントがある場合は、定率種別表記を優先
        return $this->point_list->where('fee_type', 2)->count() > 0 ? 2 : 1;
    }

    // override
    public function getFeeLabelAttribute() : ?string
    {
        $fixedAmountLabelvalue = $this->calcTotalPoint();
        $fixedRateFeeLabelvalue = $this->rate_percent;

        $feeLabel = '';
        if ($fixedRateFeeLabelvalue > 0) {
            $feeLabel .= ('購入額の' . $fixedRateFeeLabelvalue. '%'); 
        } 
        if ($fixedRateFeeLabelvalue > 0 && $fixedAmountLabelvalue > 0) {
            $feeLabel .= ' + ';
        }
        if ($fixedAmountLabelvalue > 0) {
            $feeLabel .= number_format($fixedAmountLabelvalue); 
        } 

        return $feeLabel;
    }

    // override
    public function getFeeLabelSAttribute() : ?string
    {
        $fixedAmountLabelvalue = $this->calcTotalPoint();
        $fixedRateFeeLabelvalue = $this->rate_percent;

        $feeLabel = '';
        if ($fixedRateFeeLabelvalue > 0) {
            $feeLabel .= ($fixedRateFeeLabelvalue . '%'); 
        } 
        if ($fixedRateFeeLabelvalue > 0 && $fixedAmountLabelvalue > 0) {
            $feeLabel .= ' + ';
        }
        if ($fixedAmountLabelvalue > 0) {
            $feeLabel .= number_format($fixedAmountLabelvalue); 
        } 

        return $feeLabel;
    }

    public function getFeeLabelSFeatureAttribute() : ?string
    {
        $fixedAmountLabelvalue = $this->calcTotalPoint();
        $fixedRateFeeLabelvalue = $this->rate_percent;

        $feeLabel = '';
        if ($fixedRateFeeLabelvalue > 0) {
            $feeLabel .= ($fixedRateFeeLabelvalue . '<span class="feature__show_P">%</span>'); 
        } 
        if ($fixedRateFeeLabelvalue > 0 && $fixedAmountLabelvalue > 0) {
            $feeLabel .= ' + ';
        }
        if ($fixedAmountLabelvalue > 0) {
            $feeLabel .= number_format($fixedAmountLabelvalue); 
        } 

        return $feeLabel;
    }

    // override
    public function getPreviousPointAttribute()
    {
        $all_point_previus = new self();
        $all_point_previus->point_list = collect();
        foreach ($this->point_list as $point) {
            $all_point_previus->point_list->push($point->previous_point);
        }
        return $all_point_previus;
    }

    // override
    public function getStopAtAttribute() : Carbon
    {
        if ($this->multi_course == 1) {
            foreach ($this->point_list as $point) {
                $point->stop_at;
            }    
        } else {
            return $this->point_list->first()->stop_at;
        }
    }

}
