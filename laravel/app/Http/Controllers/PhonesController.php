<?php
namespace App\Http\Controllers;

use Auth;
use Validator;

use Illuminate\Http\Request;
use App\Http\Middleware\Phone;

class PhonesController extends Controller
{
    /**
     * 認証画面.
     * @param Request $request {@link Request}
     */
    public function index(Request $request)
    {
        // リファラ取得
        $referer = $request->input('referer') ?? route('website.index');
        Phone::clean(Phone::AUTH_SESSION_KEY);
        return view('phones.index', ['referer' => $referer]);
    }

    /**
     * 認証開始描画.
     * @param string $referer リファラ
     */
    private function getInitView(string $referer)
    {
        // トークン取得
        $ost_token = Phone::find(Phone::AUTH_SESSION_KEY);
        // トークン作成に失敗した場合
        if (!isset($ost_token->id)) {
            return redirect(route('phones.index'). '?' . http_build_query(['referer' => $referer]))
                ->with('message', "認証作業に失敗しました。");
        }
        return view('phones.auth', ['ost_token' => $ost_token, 'referer' => $referer]);
    }

    /**
     * 認証開始.
     * @param Request $request {@link Request}
     */
    public function postInit(Request $request)
    {
        // バリデーション
        $validator = Validator::make(
            $request->all(),
            ['referer' => ['required', 'url'],]
        );

        //
        if ($validator->fails()) {
            abort(404, 'Not Found.');
        }

        // リファラ取得
        $referer = $request->input('referer');
        // トークン作成に失敗した場合
        if (!Phone::create(Phone::AUTH_SESSION_KEY, Auth::user()->tel)) {
            return redirect(route('phones.index'). '?' . http_build_query(['referer' => $referer]))
                ->with('message', "認証作業に失敗しました。");
        }
        return $this->getInitView($referer);
    }

    /**
     * 認証開始.
     * @param Request $request {@link Request}
     */
    public function getInit(Request $request)
    {
        // バリデーション
        $validator = Validator::make(
            $request->all(),
            ['referer' => ['required', 'url'],]
        );

        //
        if ($validator->fails()) {
            abort(404, 'Not Found.');
        }

        // リファラ取得
        $referer = $request->input('referer');
        return $this->getInitView($referer);
    }

    /**
     * 認証実行.
     * @param Request $request {@link Request}
     */
    public function auth(Request $request)
    {
        // リファラ取得
        $referer = $request->input('referer');
        // 認証
        $res = Phone::attempt(Phone::AUTH_SESSION_KEY);
        switch ($res) {
            case Phone::ERROR_STATUS:
                return redirect(route('phones.index'). '?' . http_build_query(['referer' => $referer]))
                    ->with('message', "認証作業に失敗しました。");
            case Phone::WAITING_STATUS:
                return redirect(route('phones.init'). '?' . http_build_query(['referer' => $referer]))
                    ->with('message', "発信が確認できません。");
            default:
                break;
        }
        return redirect($referer);
    }
}
