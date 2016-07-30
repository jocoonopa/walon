@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <h1>自動產生資料測試頁面<small>編號: {{$game->id}}</small></h1>
        <div class="col-md-10 col-md-offset-1">
            <table class="table">
                <thead> 
                    <tr>
                        <th>流水號</th>
                        <th>姓名</th>
                        <th>角色</th>
                        <th>地點</th>
                    </tr>
                </thead>
                <tbody> 
                @foreach ($game->players()->get() as $key => $player)
                    <tr class=@if(false === $player->character()->first()->is_good)"danger"@endif>
                        <td>{{++$key}}</td>
                        <td>{{$player->user()->first()->name}}</td>
                        <td>{{$player->character()->first()->name}}</td>
                        <td>{{$game->location}}</td>
                    </tr>
                @endforeach
                </tbody>                            
            </table>
            
            <h3>任務歷程 
                <small>
                        @if (true === $game->is_own_by_jus) 
                        {{'成功'}}
                        @else 
                        {{'失敗'}}
                        @endif
                </small>
            </h3>

            <table class="table">
                <thead>
                    <tr>
                        <th>任務編號</th>
                        <th>投票次數</th>
                        <th>結果</th>
                        <th>出任務玩家</th>
                    </tr> 
                </thead>
                <tbody> 
                </tbody>
                @foreach ($game->missions()->get() as $mission)
                <tr>
                    <td>{{$mission->id}}</td>
                    <td>{{$mission->elections()->count()}}</td>
                    <td>
                        @if (true === $mission->is_success)
                            <span class="label label-success">成功</span>
                        @else
                            <span class="label label-danger">失敗</span>
                        @endif
                    </td>
                    <td>
                        @foreach ($mission->elections()->where('is_pass', '=', true)->first()->players()->get() as $player)
                            <span class="@if(false === $player->character()->first()->is_good){{'text-danger'}}@else{{'text-success'}}@endif">
                                {{$player->user()->first()->name}}
                            </span>
                        @endforeach
                    </td>                
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
@endsection
