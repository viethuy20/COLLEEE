<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Illuminate\Session\TokenMismatchException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
        MaintenanceException::class, LockException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    //public function report(Exception $exception)
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response
     */
    //public function render($request, Exception $exception)
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof TokenMismatchException) {
            return redirect(route('website.index'));
        }
        // ユーザーロック
        if ($exception instanceof LockException) {
            if ($exception->getType() == LockException::LOCK1_TYPE) {
                return response()->view('errors.lock1');
            }
            return response()->view('errors.lock2');
        }
        // メンテナンス
        if ($exception instanceof MaintenanceException) {
            return response()->view('errors.mainte', ['message' => $exception->getMessage()]);
        }
        // HTTP
        if ($this->isHttpException($exception)) {
            $http_status = $exception->getStatusCode();
            // 専用ページのあるエラーページの場合
            $http_status_list = [403, 404, 503];
            if (in_array($http_status, $http_status_list, true)) {
                return response()->view('errors.'.$http_status, [], $http_status);
            }
            // それ以外
            return response()->view('errors.500', [], $http_status);
        }
        return parent::render($request, $exception);
    }
}
