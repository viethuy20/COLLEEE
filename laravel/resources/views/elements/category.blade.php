    <?php
    // PC版で表示するラベルマップ
    $label_type_map = [
                'サービスで探す' => [
                    122 => ['icon' => 'ico_serv_car.svg', 'class' => 'large'], 
                    123 => ['icon' => 'ico_serv_foc.svg'], 
                    124 => ['icon' => 'ico_serv_entmt.svg'], 
                    125 => ['icon' => 'ico_serv_credit.svg'], 
                    126 => ['icon' => 'ico_serv_home.svg'], 
                    127 => ['icon' => 'ico_serv_hikkoshi.svg'], 
                    128 => ['icon' => 'ico_serv_kaitori.svg'], 
                    129 => ['icon' => 'ico_serv_pen.svg'], 
                    130 => ['icon' => 'ico_serv_school.svg'], 
                    131 => ['icon' => 'ico_serv_beauty.svg'], 
                    132 => ['icon' => 'ico_serv_pay.svg'], 
                    133 => ['icon' => 'ico_serv_bank.svg', 'class' => 'small'], 
                    134 => ['icon' => 'ico_serv_money.svg'], 
                    135 => ['icon' => 'ico_serv_furusato.svg']],
                'ショッピングで探す' => [
                    109 => ['icon' => 'ico_shop_cart.svg'],
                    110 => ['icon' => 'ico_shop_diet.svg'], 
                    111 => ['icon' => 'ico_shop_beauty.svg', 'class' => 'xsmall'], 
                    112 => ['icon' => 'ico_shop_fashion.svg'], 
                    113 => ['icon' => 'ico_shop_gourmet.svg'], 
                    114 => ['icon' => 'ico_shop_gift.svg'], 
                    115 => ['icon' => 'ico_shop_kaden.svg'], 
                    116 => ['icon' => 'ico_shop_life.svg'], 
                    117 => ['icon' => 'ico_shop_sports.svg'], 
                    118 => ['icon' => 'ico_shop_kids.svg'], 
                    119 => ['icon' => 'ico_shop_pet.svg', 'class' => 'large'], 
                    120 => ['icon' => 'ico_shop_book.svg'], 
                    121 => ['icon' => 'ico_shop_game.svg']],
                '人気条件' => [
                    78 => ['icon' => 'ico_popular_saving.svg'], 
                    80 => ['icon' => 'ico_popular_foc.svg'], 
                    82 => ['icon' => 'ico_popular_shop.svg'], 
                    84 => ['icon' => 'ico_serv_foc.svg']],
            ];
    ?>

    @foreach($label_type_map as $label_type => $label_ids)
    <div class="contents__ttl">{{ $label_type }}</div>
    <ul class="sidebar__list">
        <?php $label_list = \App\Label::whereIn('id', array_keys($label_ids))->pluck('name', 'id')->all(); ?>
        @foreach($label_list as $label_id => $label)
        <?php
            $icon = $label_ids[$label_id]['icon'] ?? null;
            $class = $label_ids[$label_id]['class'] ?? null;
        ?>
        <li>
            <a href="{{ \App\Search\ProgramCondition::getStaticListUrl((object) ['ll' => [$label_id]]) }}">
                <i>{{ Tag::image("/images/common/$icon", null, isset($class) ? ['class' => $class] : null) }}</i>{{ $label }}
            </a>
        </li>
        @endforeach
    </ul>
    @endforeach
