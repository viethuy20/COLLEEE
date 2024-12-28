<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use \Validator;

class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Validator::extend('custom_email', function ($attribute, $value, $parameters, $validator) {
            /*
            * ドメインの存在確認する？
            */
            $check_dns = empty($parameters[0]) ? 0 : 1;

            switch (true) {
                /*
                * PHP7.1.0 よりも前では FILTER_FLAG_EMAIL_UNICODE が無いため
                * filter_var($email, FILTER_VALIDATE_EMAIL, FILTER_FLAG_EMAIL_UNICODE)
                * と書けないのです。
                */
                /*
                case false === filter_var($value, FILTER_VALIDATE_EMAIL):
                case !preg_match('/@(?!\[)(.+)\z/', $value, $m):
                    return false;
                */
                case !preg_match('/^([a-z0-9_]|\-|\.|\+)+@((([a-z0-9_]|\-)+\.)+[a-z]{2,6})$/', $value, $m):
                    return false;
                case !$check_dns:
                case checkdnsrr($m[2], 'MX'):
                case checkdnsrr($m[2], 'A'):
                case checkdnsrr($m[2], 'AAAA'):
                    return true;
                default:
                    return false;
            }
        });
        //
        Validator::extend('custom_email_unblock', function ($attribute, $value, $parameters, $validator) {
            // ブロック確認
            return \App\EmailBlockDomain::checkEmail($value);
        });
        Validator::extend('user_email_unique', function ($attribute, $value, $parameters, $validator) {
            /*
            * ユーザーIDの存在確認
            */
            $user_id = empty($parameters[0]) ? null : $parameters[0];
            // ユーザーユニーク確認
            return \App\User::checkUnique(['email' => $value], $user_id);
        });
        Validator::extend('colleee_tel', function ($attribute, $value, $parameters) {
            // IP電話除去
            if (Str::startsWith($value, '050')) {
                return false;
            }
            return preg_match("/^0[0-9]{9,10}$/", $value);
        });
        Validator::extend('custom_datetime_array', function ($attribute, $value, $parameters) {
            $year_key = 'year';
            $month_key = 'month';
            $day_key = 'day';
            $hour_key = 'hour';
            $min_key = 'min';
            $sec_key = 'sec';
            // 空の場合
            // ※required,nullableで事前にデータが存在するか確認
            if (!isset($value[$year_key]) && !isset($value[$month_key]) && !isset($value[$day_key])
                    && !isset($value[$hour_key]) && !isset($value[$min_key]) && !isset($value[$sec_key])) {
                return true;
            }
            // 日付確認
            if (!isset($value[$year_key]) || !preg_match('/^[0-9]+$/', $value[$year_key])
                    || !isset($value[$month_key]) || !preg_match('/^[0-9]+$/', $value[$month_key])
                    || !isset($value[$day_key]) || !preg_match('/^[0-9]+$/', $value[$day_key])
                    || !checkdate($value[$month_key], $value[$day_key], $value[$year_key])) {
                return false;
            }
            // 時確認
            if (isset($value[$hour_key]) && !preg_match('/^([01]?[0-9]|2[0-3])$/', $value[$hour_key])) {
                return false;
            }
            // 分確認
            if (isset($value[$min_key]) && !preg_match('/^[0-5]?[0-9]$/', $value[$min_key])) {
                return false;
            }
            // 秒確認
            if (isset($value[$sec_key]) && !preg_match('/^[0-5]?[0-9]$/', $value[$sec_key])) {
                return false;
            }
            return true;
        });
        Validator::extend('custom_katakana', function ($attribute, $value, $parameters) {
            return preg_match("/^[ァ-ヾ]+$/u", $value);
        });
        Validator::extend('colleee_password', function ($attribute, $value, $parameters) {
            return preg_match('/^[A-Za-z\d\x21\x23-\x26\x2B\x2D\x2E\x3C-\x40\x5E\x5F\x7E]{8,20}$/', $value);
        });
        Validator::extend('custom_ebank_name', function ($attribute, $value, $parameters) {
            //書式確認
            if (!preg_match("/^[ァ-ヾ０-９Ａ-Ｚ．（），－　]+$/u", $value)) {
                //書式確認
                return false;
            }
            // 引数がない場合
            if (!isset($parameters) || $parameters == '') {
                return true;
            }
            // 濁点,半濁点を1文字として文字数を検証
            // 濁点,半濁点を分離
            $_value = mb_convert_kana(mb_convert_kana($value, 'k'), 'K');

            return mb_strlen($_value) <= $parameters;
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
