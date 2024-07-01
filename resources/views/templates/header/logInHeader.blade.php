<header class="header sticky-top">
    <div class="top-bar px-3 py-3">
        <ul class="top-list">
            <a href="{{route('web.home')}}" class="logo">
                {{-- <img src="{{ asset('static/assets/icons/logo.svg') }}" alt="" class="logo__img"> --}}
                WEB XEM PHIM
            </a>

            <!-- Nav 1 -->
            <ul class="nav flex-column nav-1">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('web.home') }}">
                        <img src="{{ asset('static/assets/icons/film.svg') }}" alt="" class="nav-link__logo"/>
                        <p>Trang chủ</p>
                    </a>
                </li>
                @auth('web')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('web.favorites') }}">
                            <img src="{{ asset('static/assets/icons/heart.svg') }}" alt="" class="nav-link__logo"/>
                            <p>Yêu thích</p>
                        </a>
                    </li>
                @endauth
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <img src="{{ asset('static/assets/icons/trending.svg') }}" alt="" class="nav-link__logo"/>
                        <p>Thịnh hành</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <img src="{{ asset('static/assets/icons/calendar.svg') }}" alt="" class="nav-link__logo"/>
                        <p>Sắp ra mắt</p>
                    </a>
                </li>
            </ul>

            <!-- Nav 3 -->
            <ul class="nav flex-column nav-3">
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <img src="{{ asset('static/assets/icons/setting.svg') }}" alt="" class="nav-link__logo"/>
                        Settings
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('web.logout') }}" onclick="event.preventDefault();
   document.getElementById('logout-form').submit();">
                        <img src="{{ asset('static/assets/icons/log-out.svg') }}" alt="" class="nav-link__logo"/>
                        Logout
                    </a>

                    <form id="logout-form" action="{{ route('web.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>

                </li>
            </ul>
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
                <a class="top-link" href="#!">Thể loại 123</a>
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

{{--                <a href="#!" class="top-act__noti">--}}
{{--                    <img src="{{ asset('static/assets/icons/bell.svg') }}" alt=""/>--}}
{{--                </a>--}}

                <h4 >Số dư trong ví: {{number_format(auth()->guard('web')->user()->wallet->balance)}} VNĐ</h4>

                <ul class="top-list top-list-user">
                    <li>
                        <div class="top-act__user">
                            <img src="{{ asset('static/assets/imgs/avatar.jpg') }}" alt="" class="top-act__avt"/>
                            <p class="top-act__name">{{ auth()->guard('web')->user()->name }}</p>
                        </div>
                        <ul>
                            <li>
                                <a class="user-action" href="{{ route('web.favorites') }}">
                                    <img src="{{ asset('static/assets/icons/heart.svg') }}" alt=""
                                         class="nav-link__logo"/>
                                    <p>Yêu thích</p>
                                </a>
                            </li>
                            <li>
                                <a class="user-action" href="{{ route('web.wallet') }}">
                                    <img src="{{ asset('static/assets/icons/wallet.svg') }}" alt=""
                                         class="nav-link__logo"/>
                                    <p>Ví của tôi</p>
                                </a>
                            </li>
                            <li>
                                <a class="user-action" href="{{route('web.profile-settings')}}">
                                    <img src="{{ asset('static/assets/icons/setting.svg') }}" alt=""
                                         class="nav-link__logo"/>
                                    Cài đặt
                                </a>
                            </li>
                            <li>
                                <a class="user-action" href="{{ route('web.logout') }}" onclick="event.preventDefault();
   document.getElementById('logout-form').submit();">
                                    <img src="{{ asset('static/assets/icons/log-out.svg') }}" alt=""
                                         class="nav-link__logo"/>
                                    Đăng xuất
                                </a>

                                <form id="logout-form" action="{{ route('web.logout') }}" method="POST"
                                      style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
