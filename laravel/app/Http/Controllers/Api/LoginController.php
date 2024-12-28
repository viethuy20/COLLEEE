<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Authenticate;
use App\User;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    private $error_messages;
    private $base_url;
    private $cookie_data;
    private $user_model;
    private $redirect_uri_ems;

    public function __construct(User $user)
    {
        $this->user_model       = $user;
        $this->redirect_uri_ems = config('redirect_uri.ems');
        $this->base_url         = config('app.url');
        $this->type_uri_ems     = ['login', 'connect'];
        $this->cookie_data      = [
            'name'     => config('cookie.name'),
            'lifetime' => config('cookie.lifetime'),
        ];
        $this->error_messages = [
            'cannot_login'     => 'ログインに失敗しました。',
            'wrong_infomation' => 'ユーザーは存在しません。',
            'wrong_redirect'   => 'リダイレクト先は間違っています。再確認お願いします。',
        ];
        $this->type = ['login', 'connect'];
    }

    public function login(Request $request)
    {
        try {
            $redirect_uri = ($request->redirect_uri) ?? null;
            $referer      = ($request->referer) ?? null;
            $ems_type     = ($request->type && in_array($request->type, $this->type_uri_ems)) ? $request->type : null;

            if (Cookie::has($this->cookie_data['name'])) {
                if ($ems_type == 'login') {
                    $url_redirect = $this->getUrlRedirect('redirect', [
                        'redirect_uri' => $redirect_uri,
                        'ems_type'     => $ems_type,
                    ]);

                } else {
                    $url_redirect = $this->getUrlRedirect('confirm', [
                        'redirect_uri' => $redirect_uri,
                        'ems_type'     => $ems_type,
                    ]);
                }

                return redirect($url_redirect)
                    ->withInput(['referer' => $referer]);
            }

            if (isset($referer) && !Str::startsWith($referer, $this->base_url)) {
                $referer = null;
            }

            return view('ebook.login', [
                'redirect_uri' => $redirect_uri,
                'referer'      => $referer,
                'ems_type'     => $ems_type,
            ]);

        } catch (Exception $e) {
            Log::error($e->getMessage());
            abort(404);
        }
    }

    public function submit(Request $request)
    {
        $referer      = $request->input('referer');
        $redirect_uri = $request->input('redirect_uri');
        $ems_type     = ($request->type && in_array($request->type, $this->type_uri_ems)) ? $request->type : null;

        try {
            $this->validate(
                $request,
                [
                    'email'    => 'required',
                    'password' => 'required',
                    'referer'  => 'required',
                ],
                [],
                [
                    'email'    => 'メールアドレス',
                    'password' => 'パスワード',
                ]
            );

            $email    = $request->input('email');
            $password = $request->input('password');

            if (Authenticate::attempt($email, $password)) {
                $user = Auth::user();
                if ($redirect_uri) {
                    try {
                        Cookie::queue($this->cookie_data['name'], Crypt::encryptString($user->id), $this->cookie_data['lifetime']);
                        $url_redirect = $this->getUrlRedirect('confirm', [
                            'redirect_uri' => $redirect_uri,
                            'ems_type'     => $ems_type,
                        ]);

                        return redirect($url_redirect)
                            ->withInput(['referer' => $referer]);

                    } catch (Exception $e) {
                        return redirect()
                            ->back()
                            ->withInput(['referer' => $referer])
                            ->with('message', $e->getMessage());
                    }
                }
                return redirect($referer);
            }

            return redirect()
                ->back()
                ->withInput(['referer' => $referer])
                ->with('message', $this->error_messages['cannot_login']);

        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput(['referer' => $referer])
                ->with('message', $e->getMessage());
        }
    }

    public function confirm(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                $user_id = Crypt::decryptString(Cookie::get($this->cookie_data['name']));
                $user    = Auth::loginUsingId($user_id);
            }

            $redirect_uri = ($request->redirect_uri) ?? null;
            $referer      = ($request->referer) ?? null;
            $ems_type     = ($request->type && in_array($request->type, $this->type_uri_ems)) ? $request->type : null;
            $data         = [
                'redirect_uri' => $redirect_uri,
                'ems_type'     => $ems_type,
            ];

            if (!Cookie::has($this->cookie_data['name'])) {
                $url_redirect = $this->getUrlRedirect('login', $data);
                return redirect($url_redirect)
                    ->withInput(['referer' => $referer]);
            }

            return view('ebook.confirm', $data);

        } catch (Exception $e) {
            Log::error($e->getMessage());
            abort(404);
        }
    }

    public function redirect(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                $user_id = Crypt::decryptString(Cookie::get($this->cookie_data['name']));
                $user    = Auth::loginUsingId($user_id);
            }

            $referer      = ($request->referer) ?? null;
            $redirect_uri = ($request->redirect_uri) ?? null;
            $ems_type     = ($request->type && in_array($request->type, $this->type_uri_ems)) ? $request->type : null;
            $data         = [
                'email'        => $user->email,
                'password'     => $user->password,
                'ems_type'     => $ems_type,
                'redirect_uri' => $redirect_uri,
            ];
            $url_redirect = $this->getRedirectUri($data);

            if ($url_redirect['error']) {
                return redirect($url_redirect['url'])
                    ->withInput(['referer' => $referer])
                    ->with('message', $url_redirect['message']);
            }

            return redirect($url_redirect['url']);

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()
                ->back()
                ->withInput(['referer' => $referer])
                ->with('message', $e->getMessage());
        }
    }

    public function perform(Request $request)
    {
        try {
            $check = false;
            $user  = $this->user_model->query()
                ->where('email', $request->email)
                ->first();

            if ($user) {
                if (Hash::check($request->password, $user->password) || ($user->password == $request->password)) {
                    $check = true;
                }

                switch ($check) {
                    case true:
                        return response()->json([
                            'error'   => false,
                            'code'    => 200,
                            'message' => '正常',
                            'data'    => [
                                'email'    => $user->email,
                                'userId'   => $user->id,
                                'nickname' => $user->nickname,
                                'birthday' => $user->brithday,
                                'tel'      => $user->tel,
                                'memo'     => $user->memo,
                            ],
                        ]);

                    default:
                        return response()->json([
                            'error'   => false,
                            'code'    => 200,
                            'message' => $this->error_messages['wrong_infomation'],
                            'data'    => [],
                        ]);
                }

            } else {
                return response()->json([
                    'error'   => false,
                    'code'    => 200,
                    'message' => $this->error_messages['wrong_infomation'],
                    'data'    => [],
                ]);
            }

        } catch (Exception $e) {
            return response()->json([
                'error'   => true,
                'code'    => 500,
                'message' => $e->getMessage(),
                'data'    => [],
            ]);
        }
    }

    public function reset(Request $request)
    {
        if (base64_decode($request->redirect_uri) == $this->redirect_uri_ems) {
            $code_data    = base64_encode(0);
            $redirect_uri = $this->redirect_uri_ems . "?code={$code_data}";

            return redirect($redirect_uri);
        }

        return redirect()->back();
    }

    public function useAnotherAccount(Request $request)
    {
        try {
            $redirect_uri = ($request->redirect_uri) ?? null;
            $ems_type     = ($request->type && in_array($request->type, $this->type_uri_ems)) ? $request->type : null;
            $data         = [
                'ems_type'     => $ems_type,
                'redirect_uri' => $redirect_uri,
            ];

            $url_redirect = $this->getUrlRedirect('login', $data);

            return redirect($url_redirect)
                ->withCookie(Cookie::forget($this->cookie_data['name']));

        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('message', $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try {
            $routeName = Route::currentRouteName();

            if (strpos($routeName, 'api') !== false) {
                $parsedUrl = parse_url(URL::previous());
                $url       = route('api.login.login') . '?' . $parsedUrl['query'];

                return redirect($url)
                    ->with(Auth::logout())
                    ->withCookie(Cookie::forget($this->cookie_data['name']));
            }

            return back()
                ->with(Auth::logout())
                ->withCookie(Cookie::forget($this->cookie_data['name']));

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return back()
                ->with(Auth::logout())
                ->withCookie(Cookie::forget($this->cookie_data['name']));
        }
    }

    private function getRedirectUri(array $data)
    {
        try {
            $result = json_encode([
                'email'    => $data['email'],
                'password' => $data['password'],
            ]);

            if (base64_decode($data['redirect_uri']) == $this->redirect_uri_ems) {
                return [
                    'url'     => base64_decode($data['redirect_uri']) . '?type=' . $data['ems_type'] . '&code=' . base64_encode($result),
                    'error'   => false,
                    'message' => '',
                ];
            }

            return [
                'url'     => request()->headers->get('referer'),
                'error'   => true,
                'message' => $this->error_messages['wrong_redirect'],
            ];

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return [
                'url'     => $this->base_url,
                'error'   => true,
                'message' => $e->getMessage(),
            ];
        }
    }

    private function getUrlRedirect(string $type, array $data)
    {
        try {
            if ($data['ems_type']) {
                switch ($type) {
                    case 'login':
                        $url = route('api.login.login') . '?type=' . $data['ems_type'] . '&redirect_uri=' . $data['redirect_uri'];
                        break;

                    case 'confirm':
                        $url = route('api.login.confirm') . '?type=' . $data['ems_type'] . '&redirect_uri=' . $data['redirect_uri'];
                        break;

                    case 'redirect':
                        $url = route('api.login.redirect') . '?type=' . $data['ems_type'] . '&redirect_uri=' . $data['redirect_uri'];
                        break;

                    default:
                        $url = request()->headers->get('referer');
                        break;
                }
                return $url;
            }

            return request()->headers->get('referer');

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return request()->headers->get('referer');
        }
    }
}
