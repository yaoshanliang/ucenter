@extends('oauth.base')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">授权</div>
				<div class="panel-body">
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems with your input.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

                    <form method="post" action="{{ route('oauth.authorize.post', $params) }}">
						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
                                <h2>{{ $client->getName() }}</h2>
                                将访问您的如下资料：
                                <input type="hidden" name="client_id" value="{{$params['client_id']}}">
                                <input type="hidden" name="redirect_uri" value="{{$params['redirect_uri']}}">
                                <input type="hidden" name="response_type" value="{{$params['response_type']}}">
                                <input type="hidden" name="state" value="{{$params['state']}}">
                                <input type="hidden" name="scope" value="{{$params['scope']}}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" name="approve" value="1" class="btn btn-primary">授权</button>
								<button type="submit" name="deny" value="1" class="btn btn-primary">取消</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
