<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>统一身份认证</title>

	<link href="{{ asset('/css/app.css') }}" rel="stylesheet">

	<!-- Fonts -->
	<!--<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>-->

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="{{url('/')}}">统一身份认证</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<a class="navbar-brand" href="{{url('/admin')}}">管理后台</a>
				</ul>

				<ul class="nav navbar-nav navbar-right">
					@if (Auth::guest())
						<li><a href="{{ url('/auth/login') }}">登录</a></li>
					@else
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->username }} <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="{{ url('/auth/logout') }}">个人信息</a></li>
							</ul>
						</li>
						<li><a href="{{ url('/auth/logout') }}">登出</a></li>
					@endif
				</ul>
			</div>
		</div>
	</nav>
<!--
	<div class="container">
		<div class="row">
			<div class="col-md-1">
				<a class="navbar-brand" href="{{url('/admin')}}">管理后台</a>
			</div>
			<div class="col-md-pull-10">
			</div>
		</div>
	</div>
-->
<style>
#left{width:10%;}
#right{width:90%;}
#left,#right{float:left;}
</style>

	<div class="container">
		<div id="left">
				<ul class="nav navbar-nav">
					<a class="navbar-brand" href="{{url('/admin')}}">管理后台</a>
					<a class="navbar-brand" href="{{url('/admin')}}">管理后台</a>
				</ul>
		</div>
		<div id="right">
			@yield('content')
		</div>
	</div>

	<!-- Scripts -->
	<script src="{{ asset('/js/jquery.min.js') }}"></script>
	<script src="{{ asset('/js/bootstrap.min.js') }}"></script>
</body>
</html>
