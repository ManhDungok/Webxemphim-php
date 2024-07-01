@extends('welcome')
@section('content')
    <div class="movie mt-header mb-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <img src="{{url('/uploads/'.$movie->image)}}" alt="" class="movie__main--img">
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="movie__detail">
                        <div class="row-1">
                            <h2 class="movie__name">{{ $movie->name }}</h2>
                            <div class="assess">
                                <img src="{{url('/static/assets/icons/star.svg')}}" alt="">
                                <span>{{$movie->point}}</span>
                            </div>
                        </div>
                        <div class="row-2">
                            <span class="year">{{ $movie->release_date }}</span>
                            <span class="genre">{{ $movie->category->name }}</span>
                            <span class="time">{{ $movie->duration }} phút</span>
                        </div>
                        <div class="row-3">
                            <p class="movie__desc">{{ $movie->description }}</p>
                        </div>
                        <div class="primary-action mb-4">
                            @if(empty($movie->price) || (auth()->guard('web')->check() && auth()->guard('web')->user()->checkMyMovie($movie->id)))
                                <a href="{{ route('web.movie-watch', $movie->id) }}"  class="red" class="btn btn-primary btn-lg"><h2>Xem phim 111</h2></a>
                            @else
                                <a href="{{ route('web.movie-buy', $movie->id) }}" class="btn btn-success btn-lg" onclick="event.preventDefault();
   document.getElementById('movie-buy-form-{{$movie->id}}').submit();"><h2>Mua phim ({{number_format($movie->price)}} VNĐ)</h2></a>

                                <form id="movie-buy-form-{{$movie->id}}" action="{{ route('web.movie-buy', $movie->id) }}" method="POST"
                                      style="display: none;">
                                    @csrf
                                </form>
                            @endif

                            @auth('web')
                                <form action="{{ route('web.movie-like', $movie->id) }}" method="POST">
                                    @csrf
                                    <button class="primary-save" id="likeButton" type="submit">
                                        <img src="{{ asset('static/assets/button').(auth()->guard('web')->user()->checkFavorite($movie->id) ? '/liked.svg' : '/like.svg') }}" class="likeImage" alt="Like" width="35"/>
                            </button>
                        </form>
                        @else
                        <p class="likeImage">Đăng nhập để thích phim!</p>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
