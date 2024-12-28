<div class="header__search">
    <form id="form1" name="form1" class="" method="get" action="{{ route('programs.list') }}">
        <input type="submit" value="" class="header__search__submit">
        <input type="text" value="" class="header__search__keyword" placeholder="キーワードで探す" name="keywords">
    </form>
    {{ $slot }}
</div>