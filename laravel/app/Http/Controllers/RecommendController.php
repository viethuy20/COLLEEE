<?php
namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\External\Logrecoai;

class RecommendController extends Controller
{
    private function checkHeader(Request $request): bool {
        $referer = $request->header('X-Recommend-Referer');
        $origin = $request->header('X-Recommend-Origin');
        $base_url = config('app.url');
        if (strpos($referer, $base_url) !== 0 || $origin !== $base_url) {
            return false;
        }
        return Logrecoai::isRequestToSend();
    }

    public function historyView(Request $request) {
        if (!$this->checkHeader($request)) {
            return response()->json([
                'status' => false,
            ]);
        }

        $logrecoai_session_id = Logrecoai::getSessionId();
        $logrecoai_user_id = Logrecoai::getUserId();
        $logrecoai_item_id = '';
        $request_data = $request->all();
        $item_id = $request_data['item_id'] ?? '';
        if (is_string($item_id)) {
            $logrecoai_item_id = $item_id;
        }

        $logrecoai = new Logrecoai();
        $status = $logrecoai->historyView($logrecoai_session_id, $logrecoai_user_id, $logrecoai_item_id);

        return response()->json([
            'status' => $status,
        ]);
    }

    public function kpiClick(Request $request) {
        if (!$this->checkHeader($request)) {
            return response()->json([
                'status' => false,
            ]);
        }

        $logrecoai_session_id = Logrecoai::getSessionId();
        $logrecoai_user_id = Logrecoai::getUserId();
        $logrecoai_item_id = '';
        $request_data = $request->all();
        $item_id = $request_data['item_id'] ?? '';
        if (is_string($item_id)) {
            $logrecoai_item_id = $item_id;
        }
        $type_name = $request_data['type_name'] ?? '';
        if (is_string($type_name)) {
            $logrecoai_type_name = $type_name;
        }
        $spot_name = $request_data['spot_name'] ?? '';
        if (is_string($spot_name)) {
            $logrecoai_spot_name = $spot_name;
        }

        $logrecoai = new Logrecoai();
        $status = $logrecoai->kpiClick($logrecoai_session_id, $logrecoai_user_id, $logrecoai_item_id, $logrecoai_type_name, $logrecoai_spot_name);

        return response()->json([
            'status' => $status,
        ]);
    }

    public function getRecommendArticles(Request $request) {
        $status = false;
        $html = '';

        try {
            $logrecoai_session_id = Logrecoai::getSessionId();
            $logrecoai_user_id = Logrecoai::getUserId();
            $logrecoai_item_ids = '';
            $request_data = $request->all();
            $item_id = $request_data['item_id'] ?? '';
            if (is_string($item_id) && (preg_match('/^(pg\d+(,pg\d+)*)$/', $item_id) || preg_match('/^(wp\d+(,wp\d+)*)$/', $item_id))) {
                $logrecoai_item_ids = $item_id;
            }

            $num = $request_data['num'] ?? 6;
            $device = $request_data['device'] ?? 'pc';
            $purpose = $request_data['purpose'] ?? 'RECOMMEND';
            $page_name = 'PAGE_UNKNOWN';
            if (in_array($request_data['page_name'], ['ARTICLE_TOP'])) {
                $page_name = 'ポイ活お得情報_トップ';
            } elseif (in_array($request_data['page_name'], ['ARTICLE_DETAIL'])) {
                $page_name = 'ポイ活お得情報_記事詳細';
            } elseif (in_array($request_data['page_name'], ['ARTICLE_CATEGORY', 'ARTICLE_TAG'])) {
                $page_name = 'ポイ活お得情報_記事一覧';
            }

            $recommend_data = [];
            $logrecoai = new Logrecoai();
            $status = true;
            if ($purpose === 'RELATED') {
                $spot_name = $page_name . '_' . 'おすすめの記事';
                $recommend_data = $logrecoai->getArticlesRecommendContentBase($logrecoai_session_id, $logrecoai_user_id, $logrecoai_item_ids, $num, $spot_name);
                if (empty($recommend_data)) {
                    $recommend_data = $logrecoai->getArticlesRankingView($logrecoai_session_id, $num, $spot_name);
                }
                $html = $this->getRecommendArticlesHtmlForRelated($recommend_data, $device, $spot_name);
            } elseif ($purpose === 'POPULAR') {
                $spot_name = $page_name . '_' . '人気の記事';
                $recommend_data = $logrecoai->getArticlesRankingView($logrecoai_session_id, $num, $spot_name);
                $html = $this->getRecommendArticlesHtmlForPopular($recommend_data, $device, $spot_name);
            } else {
                $spot_name = $page_name . '_' . 'おすすめの記事';
                $recommend_data = $logrecoai->getArticlesRecommendHybrid($logrecoai_session_id, $logrecoai_user_id, $logrecoai_item_ids, $num, $spot_name);
                if (empty($recommend_data)) {
                    $recommend_data = $logrecoai->getArticlesRankingView($logrecoai_session_id, $num, $spot_name);
                }
                $html = $this->getRecommendArticlesHtml($recommend_data, $device, $spot_name);
            }
        } catch (\Exception $e) {
            $status = false;
            $html = '';
            \Log::error(__METHOD__ . ':' . __LINE__ . ':' . $e->getMessage());
        }

        return response()->json([
            'status' => $status,
            'html' => $html,
        ]);
    }

    private function getRecommendArticlesHtml($recommend_data, $device, $spot_name) {
        $html = '';

        if ($device === 'pc') {
            $html .= '<div class="article__list">';
            $html .= '<ul>';
            foreach ($recommend_data as $data) {
                $html .= '<li>';
                $html .= '<a onclick="kpiClick(\'' . htmlspecialchars($data['item_id']) . '\', \'' . htmlspecialchars($data['type_name']) . '\', \'\', \'wp\', \'' . htmlspecialchars($spot_name) . '\');" href="' . htmlspecialchars($data['url']) . '">';
                $html .= '<div class="thumb ofi"><img src="' . htmlspecialchars($data['image_url']) . '" alt="' . htmlspecialchars($data['title']) . '"></div>';
                $html .= '<p class="ttl">' . htmlspecialchars($data['title']) . '</p>';
                $html .= '<time datetime="' . htmlspecialchars(date('Y-m-d', strtotime($data['date']))) . '" class="data">' . htmlspecialchars(date('Y-m-d', strtotime($data['date']))) . '</time>';
                $html .= '</a>';
                $html .= '</li>';
            }
            $html .= '</ul>';
            $html .= '</div><!--/article__list-->';
        } elseif ($device === 'sp') {
            $html .= '<div class="article__list">';
            $html .= '<ul>';
            foreach ($recommend_data as $data) {
                $html .= '<li>';
                $html .= '<a onclick="kpiClick(\'' . htmlspecialchars($data['item_id']) . '\', \'' . htmlspecialchars($data['type_name']) . '\', \'\', \'wp\', \'' . htmlspecialchars($spot_name) . '\');" href="' . htmlspecialchars($data['url']) . '">';
                $html .= '<div class="thumb ofi"><img src="' . htmlspecialchars($data['image_url']) . '" alt="' . htmlspecialchars($data['title']) . '"></div>';
                $html .= '<div class="content">';
                $html .= '<p class="ttl">' . htmlspecialchars($data['title']) . '</p>';
                $html .= '<time datetime="' . htmlspecialchars(date('Y-m-d', strtotime($data['date']))) . '" class="data">' . htmlspecialchars(date('Y-m-d', strtotime($data['date']))) . '</time>';
                $html .= '</div>';
                $html .= '</a>';
                $html .= '</li>';
            }
            $html .= '</ul>';
            $html .= '</div><!--/article__list-->';
        }

        return $html;
    }

    private function getRecommendArticlesHtmlForRelated($recommend_data, $device, $spot_name) {
        $html = '';

        if ($device === 'pc') {
            $html .= '<div class="article__list list-col-3">';
            $html .= '<ul>';
            foreach ($recommend_data as $data) {
                $html .= '<li>';
                $html .= '<a onclick="kpiClick(\'' . htmlspecialchars($data['item_id']) . '\', \'' . htmlspecialchars($data['type_name']) . '\', \'\', \'wp\', \'' . htmlspecialchars($spot_name) . '\');" href="' . htmlspecialchars($data['url']) . '">';
                $html .= '<div class="thumb ofi"><img src="' . htmlspecialchars($data['image_url']) . '" alt="' . htmlspecialchars($data['title']) . '"></div>';
                $html .= '<p class="ttl">' . htmlspecialchars($data['title']) . '</p>';
                $html .= '<!-- <p class="txt"></p> -->';
                $html .= '<time datetime="' . htmlspecialchars(date('Y-m-d', strtotime($data['date']))) . '" class="data">' . htmlspecialchars(date('Y-m-d', strtotime($data['date']))) . '</time>';
                $html .= '</a>';
                $html .= '</li>';
            }
            $html .= '</ul>';
            $html .= '</div><!--/article__list-->';
        } elseif ($device === 'sp') {
            $html .= '<div class="article__list list-col-3">';
            $html .= '<ul>';
            foreach ($recommend_data as $data) {
                $html .= '<li>';
                $html .= '<a onclick="kpiClick(\'' . htmlspecialchars($data['item_id']) . '\', \'' . htmlspecialchars($data['type_name']) . '\', \'\', \'wp\', \'' . htmlspecialchars($spot_name) . '\');" href="' . htmlspecialchars($data['url']) . '">';
                $html .= '<div class="thumb ofi"><img src="' . htmlspecialchars($data['image_url']) . '" alt="' . htmlspecialchars($data['title']) . '"></div>';
                $html .= '<div class="content">';
                $html .= '<p class="ttl">' . htmlspecialchars($data['title']) . '</p>';
                $html .= '<time datetime="' . htmlspecialchars(date('Y-m-d', strtotime($data['date']))) . '" class="data">' . htmlspecialchars(date('Y-m-d', strtotime($data['date']))) . '</time>';
                $html .= '</div>';
                $html .= '</a>';
                $html .= '</li>';
            }
            $html .= '</ul>';
            $html .= '</div><!--/article__list-->';
        }

        return $html;
    }

    private function getRecommendArticlesHtmlForPopular($recommend_data, $device, $spot_name) {
        $html = '';

        if ($device === 'pc') {
            $html .= '<ul class="article__sidebar__list">';
            foreach ($recommend_data as $data) {
                $html .= '<li>';
                $html .= '<a onclick="kpiClick(\'' . htmlspecialchars($data['item_id']) . '\', \'' . htmlspecialchars($data['type_name']) . '\', \'\', \'wp\', \'' . htmlspecialchars($spot_name) . '\');" href="' . htmlspecialchars($data['url']) . '">';
                $html .= '<div class="thumb ofi"><img src="' . htmlspecialchars($data['image_url']) . '" alt="' . htmlspecialchars($data['title']) . '"></div>';
                $html .= '<div class="ttl">' . htmlspecialchars($data['title']) . '</div>';
                $html .= '<div class="txt">';
                $html .= '<time datetime="' . htmlspecialchars(date('Y-m-d', strtotime($data['date']))) . '">' . htmlspecialchars(date('Y-m-d', strtotime($data['date']))) . '</time>';
                $html .= '</div>';
                $html .= '</a>';
                $html .= '</li>';
            }
            $html .= '</ul>';
        } elseif ($device === 'sp') {
            $html .= '<div class="article__list">';
            $html .= '<ul>';
            foreach ($recommend_data as $data) {
                $html .= '<li>';
                $html .= '<a onclick="kpiClick(\'' . htmlspecialchars($data['item_id']) . '\', \'' . htmlspecialchars($data['type_name']) . '\', \'\', \'wp\', \'' . htmlspecialchars($spot_name) . '\');" href="' . htmlspecialchars($data['url']) . '">';
                $html .= '<div class="thumb ofi"><img src="' . htmlspecialchars($data['image_url']) . '" alt="' . htmlspecialchars($data['title']) . '"></div>';
                $html .= '<div class="content">';
                $html .= '<p class="ttl">' . htmlspecialchars($data['title']) . '</p>';
                $html .= '<time datetime="' . htmlspecialchars(date('Y-m-d', strtotime($data['date']))) . '" class="data">' . htmlspecialchars(date('Y-m-d', strtotime($data['date']))) . '</time>';
                $html .= '</div>';
                $html .= '</a>';
                $html .= '</li>';
            }
            $html .= '</ul>';
            $html .= '</div><!--/article__list-->';
        }

        return $html;
    }

    public function getRecommendPrograms(Request $request) {
        $status = false;
        $html = '';

        try {
            $logrecoai_session_id = Logrecoai::getSessionId();
            $logrecoai_user_id = Logrecoai::getUserId();
            $logrecoai_item_ids = '';
            $request_data = $request->all();
            $item_id = $request_data['item_id'] ?? '';
            if (is_string($item_id) && (preg_match('/^(pg\d+(,pg\d+)*)$/', $item_id) || preg_match('/^(wp\d+(,wp\d+)*)$/', $item_id))) {
                $logrecoai_item_ids = $item_id;
            }

            $num = $request_data['num'] ?? 6;
            $device = $request_data['device'] ?? 'pc';
            $purpose = $request_data['purpose'] ?? 'RECOMMEND';
            $page_name = 'PAGE_UNKNOWN';
            if (in_array($request_data['page_name'], ['ARTICLE_TOP'])) {
                $page_name = 'ポイ活お得情報_トップ';
            } elseif (in_array($request_data['page_name'], ['ARTICLE_DETAIL'])) {
                $page_name = 'ポイ活お得情報_記事詳細';
            } elseif (in_array($request_data['page_name'], ['ARTICLE_CATEGORY', 'ARTICLE_TAG'])) {
                $page_name = 'ポイ活お得情報_記事一覧';
            }

            $recommend_data = [];
            $logrecoai = new Logrecoai();
            $status = true;
            if ($purpose === 'RELATED') {
                $spot_name = $page_name . '_' . 'おすすめの広告';
                $recommend_data = $logrecoai->getProgramsRecommendContentBase($logrecoai_session_id, $logrecoai_user_id, $logrecoai_item_ids, $num, $device, $spot_name);
                if (empty($recommend_data)) {
                    $recommend_data = $logrecoai->getProgramsRankingView($logrecoai_session_id, $num, $device, $spot_name);
                }
            } else {
                $spot_name = $page_name . '_' . 'おすすめの広告';
                $recommend_data = $logrecoai->getProgramsRecommendHybrid($logrecoai_session_id, $logrecoai_user_id, $logrecoai_item_ids, $num, $device, $spot_name);
                if (empty($recommend_data)) {
                    $recommend_data = $logrecoai->getProgramsRankingView($logrecoai_session_id, $num, $device, $spot_name);
                }
            }

            $html = $this->getRecommendProgramsHtml($recommend_data, $device, $spot_name);
        } catch (\Exception $e) {
            $status = false;
            $html = '';
            \Log::error(__METHOD__ . ':' . __LINE__ . ':' . $e->getMessage());
        }

        return response()->json([
            'status' => $status,
            'html' => $html,
        ]);
    }

    private function getRecommendProgramsHtml($recommend_data, $device, $spot_name) {
        $html = '';

        $html .= '<div class="swiper-container feature-swiper">';
        $html .= '<ul class="swiper-wrapper feature__list">';
        foreach ($recommend_data as $data) {
            $html .= '<li class="swiper-slide feature__item">';
            $html .= '<a onclick="kpiClick(\'' . htmlspecialchars($data['item_id']) . '\', \'' . htmlspecialchars($data['type_name']) . '\', \'\', \'wp\', \'' . htmlspecialchars($spot_name) . '\');" href="' . htmlspecialchars($data['url']) . '/06/' . '">';
            $html .= '<div class="feature__item__img">';
            $html .= '<img src="' . htmlspecialchars($data['program_data']->affiriate->img_url) . '" alt="' . htmlspecialchars($data['title']) . '">';
            $html .= '</div>';
            $html .= '<div class="feature__item__detail">';
            $html .= '<div class="txt">';
            $html .= '<p class="headline">' . Str::limit(htmlspecialchars($data['title']), 100) . '</p>';
            $html .= '</div>';
            $html .= '<div class="primary">';
            $html .= '<!--ポイント情報-->';
            $html .= '<div class="point">';
            if ($data['program_data']->point->fee_type == "1") {
                $html .= '<p class="point">' . htmlspecialchars($data['program_data']->point->fee_label_s) . '<span class="unit">P</span></p><!--定額の場合-->';
            } else if($data['program_data']->point->fee_type == "2") {
                $html .= '<p class="point">' . htmlspecialchars($data['program_data']->point->rate_percent) . '<span class="unit">&percnt;P</span></p><!--定率の場合-->';
            }
            $html .= '<!--/ポイント情報-->';
            $html .= '</div>';
            $html .= '<div class="btn">詳細</div>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</a>';
            $html .= '</li>';
        }
        $html .= '</ul>';
        $html .= '<div class="swiper-button-prev"></div>';
        $html .= '<div class="swiper-button-next"></div>';
        $html .= '</div>';
        $html .= '<script>$(doSwiper);</script>';

        return $html;
    }
}
