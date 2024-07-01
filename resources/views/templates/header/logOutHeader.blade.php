<header class="header sticky-top">
    <div class="top-bar px-3 py-3">
        <ul class="top-list">
            <a href="{{ route('web.home') }}" class="logo">
                <img src="{{url('/static/assets/icons/logo.svg')}}" alt="" class="logo__img"/>
            </a>
            <li>
                <a class="top-link" href="#!">Quốc gia</a>
                <ul class="">
                    @foreach ($nations as $nation)
                        <li>
                            <a class="top-link"
                               href="{{ route('web.movie-nation', $nation->id) }}">{{ $nation->name }}</a>
                        </li>
                    @endforeach
                </ul>
            </li>
            <li>
                <a class="top-link" href="#!">Thể loại</a>
                <ul class="">
                    @foreach ($categories as $category)
                        <li>
                            <a class="top-link"
                               href="{{ route('web.movie-category', $category->id) }}">{{ $category->name }}</a>
                        </li>
                    @endforeach
                </ul>
            </li>

            {{-- <li>
                <a class="top-link" href="#!">Thể loại 111</a>
                <ul class="">
                    @foreach ($categories as $category)
                        <li>
                            <a class="top-link"
                               href="{{ route('web.movie-category', $category->id) }}">{{ $category->name }}</a>
                        </li>
                    @endforeach
                </ul>
            </li> --}}

        </ul>
        <div class="top-act">
            <div class="top-act__group">
                <!-- Button kích hoạt thanh tìm kiếm -->
                <a href="#!" class="top-act__search" id="searchToggle">
                    <img src="{{ asset('static/assets/icons/search.svg') }}" alt="Search"/>
                </a>
                <!-- Thanh tìm kiếm ẩn -->
                <form method="get" action="{{ route('web.movie-search') }}" id="searchBar" class="search-container"
                      style="display: none;">
                    <input name="keyword" class="searchInput" type="text" id="searchInput"
                           placeholder="Nhập nội dung tìm kiếm..."/>
                    <button class="searchButton" id="searchButton" type="submit">
                        <img src="{{ asset('static/assets/icons/search.svg') }}"/>
                    </button>
                </form>
                <a href="{{ route('web.login') }}">Đăng nhập</a>
            </div>
        </div>
    </div>
</header>
