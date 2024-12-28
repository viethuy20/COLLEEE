<div class="search__box__keyword">
    {!! Tag::formOpen(['url' => route('programs.list'), 'method' => 'get', 'name' => 'form1', 'id' => 'form1', 'action' => '検索結果', 'class' => 'search__box__keyword__form']) !!}
    @csrf    
    {!! Tag::formText('keywords','', ['placeholder'=>'キーワードで探す', 'name'=>'keywords', 'class'=>'search__box__keyword__box']) !!}
    {!! Tag::formClose() !!}

    {{ $slot }}
</div>