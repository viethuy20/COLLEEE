<?php
namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Device\Device;
use App\HelpfulReview;
use App\Program;
use App\Review;
use App\ReviewPointManagement;
use App\User;

class ReviewsController extends Controller
{
    use ControllerTrait;
    
    /**
     * レビュー登録セッションキー.
     */
    const REVIEW_SESSION_KEY = 'review_key';

    /**
     * 口コミ確認.
     * @param Request $request {@link Request}
     */
    public function confirm(Request $request)
    {
        // 入力値チェック
        $this->validate(
            $request,
            [
                'program_id' => ['required', 'integer',
                    Rule::exists('programs', 'id')->where(function ($query) {
                        $now = Carbon::now();
                        $query->where('status', '=', 0)
                            ->where('stop_at', '>', $now)
                            ->where('start_at', '<=', $now);
                    }),
                ],
                'message' => ['required', 'string', 'between:10,1000'],
                'assessment' => ['required', 'integer', 'between:1,5'],
            ],
            [
                'program_id.*'=>'エラーが発生しました。お手数ですが画面の再読込を行って頂き、最初からやり直してください。',
                'message.between'=>'口コミは10文字以上1000文字以下で投稿してください。'
            ],
            [
                'message'=>'口コミ',
                'assessment'=>'評価'
            ]
        );

        $set_date   = date('Y-m-d H:i:s');
        $review_point_management = ReviewPointManagement::where('start_at', '<=', $set_date)->where(function ($query) use ($set_date) {
            // stop_atがnullの場合（終了日が設定されていない）もしくは終了日の範囲内
            $query->whereNull('stop_at')
                ->orWhere('stop_at', '>=', $set_date);
        })->first();

        $review = $request->only(['program_id', 'assessment', 'message']);
        $program = Program::find($review['program_id']);

        // 既にユーザーが投稿済みか確認
        $user = Auth::user();
        $already_reviewed = Review::ofPostCheck($program->id, $user->id)->exists();

        // セッションに保存
        session()->put(self::REVIEW_SESSION_KEY, $review);

        return view('reviews.confirm', ['program' => $program, 'review' => $review,
            'already_reviewed' => $already_reviewed, 'review_point_management' => $review_point_management]);
    }

    /**
     * 口コミ保存.
     */
    public function store()
    {
        // セッションを確認
        if (!session()->has(self::REVIEW_SESSION_KEY)) {
            abort(404, 'Not Found.');
        }

        // セッションから値を取得
        $review_data = session()->get(self::REVIEW_SESSION_KEY);

        $program = Program::find($review_data['program_id']);

        $user = Auth::user();

        $review = new Review();
        $review->user_id = $user->id;
        $review->reviewer = $user->nickname ?? $user->name;
        $review->sex = $user->sex;
        $review->generation = $user->generation;
        $review->program_id = $program->id;
        $review->assessment = $review_data['assessment'];
        $review->message = $review_data['message'];
        $review->ip = Device::getIp();
        $review->ua = request()->header('User-Agent');
        $review->status = 2;
        $review->helpful_total = 0;

        // レビュー登録
        $res = DB::transaction(function () use ($review) {
            // 登録実行
            $review->save();
            return true;
        });

        // レビュー登録
        if (!$res) {
            return redirect()
                ->back()
                ->with('message', "登録作業に失敗しました");
        }
        // セッションから値を削除
        session()->forget($this::REVIEW_SESSION_KEY);
        
        return view('reviews.store', ['program' => $program]);
    }

    private function getReviewerViewData(User $reviewer, int $sort)
    {
        $query = $reviewer->reviews()
            ->ofEnable();
        // 有効レビュー総数取得
        $review_total = $query->count();
        if ($review_total < 0) {
            abort(404, 'Not Found.');
        }
        
        $set_date   = date('Y-m-d H:i:s');
        $review_point_management = ReviewPointManagement::where('start_at', '<=', $set_date)->where(function ($query) use ($set_date) {
            // stop_atがnullの場合（終了日が設定されていない）もしくは終了日の範囲内
            $query->whereNull('stop_at')
                ->orWhere('stop_at', '>=', $set_date);
        })->first();

        $review_list = ($review_total > 0) ? $query->ofSort($sort)->get() : collect();
        return [
            'reviewer' => $reviewer,
            'review_total' => $review_total,
            'review_list' => $review_list,
            'condition' => (object) ['sort' => $sort],
            'review_point_management' => $review_point_management
        ];
    }
    
    /**
     * ユーザー口コミ
     * @param User $user ユーザー
     * @param int $sort ソート
     */
    public function reviewer(User $user, int $sort = 0)
    {
        $set_date   = date('Y-m-d H:i:s');
        $review_point_management = ReviewPointManagement::where('start_at', '<=', $set_date)->where(function ($query) use ($set_date) {
            // stop_atがnullの場合（終了日が設定されていない）もしくは終了日の範囲内
            $query->whereNull('stop_at')
                ->orWhere('stop_at', '>=', $set_date);
        })->first();

        return view('reviews.reviewer', $this->getReviewerViewData($user, $sort));
    }
    
    /**
     * ユーザー口コミAjax.
     * @param User $user ユーザー
     * @param int $sort ソート
     */
    public function ajaxReviewer(User $user, int $sort = 0)
    {
        $data = $this->getReviewerViewData($user, $sort);
        $data['for_ajax'] = true;
        return view('elements.reviewer_review_list', $data);
    }
    
    /**
     * 参考になった登録.
     * @param Review $review レビュー
     */
    public function addHelpful(Review $review)
    {
        // ユーザー情報を取得
        $user = Auth::user();
        
        // 参考になった登録実行
        HelpfulReview::addHelpful($user->id, $review->id);

        return redirect()
            ->back();
    }
}
