@extends('api.base')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">登录</div>
				<div class="panel-body">
					<div class="alert alert-danger">
						<strong>错误!</strong> 不存在此应用.<br><br>
						<ul>
							<li>1、应用未申请</li>
							<li>2、应用不合法</li>
							<li>3、联系管理员</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
