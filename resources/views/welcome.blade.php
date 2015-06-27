@extends('base')   {{-- 继承master模版 --}}

@section('title')   {{-- 对应@yield('title') --}}
    学生成绩管理系统
@stop

@section('content')   {{-- 对应@yield('content') --}}
    <div class="container">
        <div class="jumbotron">
            <h2><div class="quote">{{ Inspiring::quote() }}</div></h2>
            <p>同学们登录后先修改相关资料</p>
            <p>查询分数,有疑问咨询管理员</p>
            <p><a class="btn btn-primary btn-lg" href="/auth/login" role="button">点击登录</a></p>
        </div>
    </div>
@stop
