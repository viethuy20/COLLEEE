<?php
namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Program;
use App\Question;

class SitemapController extends Controller
{
    public function programs()
    {
        $sitemap = \App::make("sitemap");
        
        $sitemap->setCache('laravel.sitemap_programs', 3600);
 
        if ($sitemap->isCached()) {
            return $sitemap->render('xml');
        }
        
        // プログラム詳細
        $program_list = Program::ofEnable()
            ->orderBy('id', 'asc')
            ->get();
        foreach ($program_list as $program) {
            $url = route('programs.show', ['program'=> $program]);
            
            $sitemap->add(
                $url,
                $program->updated_at->format('Y-m-d H:i:s'),
                0.9,
                'daily',
                null,
                $program->title,
                [['url' => $url, 'language' => 'ja']],
                null,
                null,
                null
            );
        }
        
        return $sitemap->render('xml');
    }
    
    public function questions()
    {
        $sitemap = \App::make("sitemap");
        $sitemap->setCache('laravel.sitemap_questions', 3600);
 
        if ($sitemap->isCached()) {
            return $sitemap->render('xml');
        }
        
        $backnumber_end_at = Carbon::yesterday()->endOfDay();
        $target = $backnumber_end_at->copy();
        $backnumber_start_at = Carbon::parse('2017-12-05')->startOfDay();
        $i = 0;
        while (true) {
            $d = $target->copy()->addMonths($i);
            if ($backnumber_start_at->gt($d)) {
                break;
            }
            $url = route('questions.monthly', ['target' => $d->format('Ym')]);
            $sitemap->add(
                $url,
                $backnumber_end_at->eq($d) ? $d->format('Y-m-d H:i:s') : $d->endOfMonth()->format('Y-m-d H:i:s'),
                0.9,
                'daily',
                null,
                $d->format('Y年n月').'のデイリーアンケート一覧',
                [['url' => $url, 'language' => 'ja']],
                null,
                null,
                null
            );
            $i = $i - 1;
        }

        // アンケート詳細
        $question_list = Question::where('type', '=', 1)
            ->where('status', '=', 0)
            ->orderBy('id', 'asc')
            ->get();
        foreach ($question_list as $question) {
            $url = route('questions.show', ['question'=> $question]);
            
            $sitemap->add(
                $url,
                $question->updated_at->format('Y-m-d H:i:s'),
                0.9,
                'daily',
                null,
                $question->title,
                [['url' => $url, 'language' => 'ja']],
                null,
                null,
                null
            );
        }
        
        return $sitemap->render('xml');
    }
}
