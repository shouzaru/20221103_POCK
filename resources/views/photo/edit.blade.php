<!-- resources/views/books.blade.php -->
@extends('layouts.app')
@section('content')
<!-- Bootstrapの定形コード… -->

<!--タグ付はajax通信でやる。そのためのJSコード呼び出し -->
<script src="{{asset('js/ajaxPost.js')}}"></script>

<!-- ナビゲーションメニュー -->
<div class="container mt-2 mb-2">
        <ul class="nav nav-tabs">
            <il class="nav-item">
                <a href="{{ route('photo.index') }}" class="nav-link active">写真一覧</a>
            </il>
            <il class="nav-item">
                <a href="{{ route('photo.create') }}" class="nav-link">写真アップロード</a>
            </il>
            <il class="nav-item">
                <a href="{{ url('players') }}" class="nav-link">選手登録</a>
            </il>
        </ul>
</div>


@canany(['isAdmin','isReadandTag']) <!-- roleがAdminとReadandTagのユーザーは以下を表示する -->
<div class="container">
    <div class="row text-center">
        <div class="col">
            <img src="{{asset('storage/uploads')}}{{'/'}}{{ $photo->path }}"  class=" img-fluid"/> <!-- asset()関数で -->
            <p>{{ $photo->date }}</p>
            <form action="{{ route('photo.update',$photo->id)}}" method="POST" target="iframe">  <!-- targetの指定でボタン押しても画面遷移しない -->
                    @csrf
                    @method('PUT')
                <p>
                    @foreach ($playerList->sortBy('number')  as $player)
                    <label class="checkbox">
                        <input class="PlayerCheckBox" type="checkbox" name="players[]" value="{{$player->id}}"
                        @if(in_array($player->id , $photo->players->pluck('id')->toArray())) checked @endif> <!-- player_idとphotoが持っているplayer_idが一致したらチェック -->
                        {{ $player->number }}{{ $player->nickname }}
                    </label>
                    @endforeach
                </p>
            </form>
        </div>
    </div>
</div>

@else
<div class="container">
    <div class="row text-center">
        <h1>この権限では許可されていません。ログインしてください。</h1>
    </div>
</div>
@endcan

<div class="container text-end">
    <div class="row">
        <div class="col">
            <a href="{{ route('photos.download', $photo->id) }}" class="btn btn-success" role="button">この写真をダウンロードする</a>
        </div>
    </div>
</div>


@can('isAdmin') <!-- roleがAdminのユーザーのみ以下を表示する -->
<div class="container text-end">
    <div class="row">
        <div class="col">
        <h3>この写真を削除する</h3>
        <form action="{{ route('photo.destroy', $photo->id)}}" method="POST">
                @csrf
                @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick='return confirm("本当に削除しますか")'>削除</button>
        </form>
        </div>
    </div>
</div>
@endcan









@endsection