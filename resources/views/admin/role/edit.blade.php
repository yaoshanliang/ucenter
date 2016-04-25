@extends('admin.base')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">编辑角色
        <div class="pull-right">
            <a href="/admin/role"><i class="fa fa-eye"></i> 角 色 </a> /
            编辑角色
        </div>
    </div>
    <div class="panel-body">

    @if (isset($errors) && (count($errors) > 0))
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
        </div>
    @endif

    <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/role/edit/'.$role->id) }}">
        <input name="_method" type="hidden" value="PUT">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <label class="col-md-3 control-label">代号</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="name" value="{{ $role->name }}">
                    <input type="hidden" class="form-control" name="old_name" value="{{ $role->name }}">
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label">名称</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="title" value="{{ $role->title }}">
                    <input type="hidden" class="form-control" name="old_title" value="{{ $role->title }}">
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label">描述</label>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="description" value="{{ $role->description }}">
                    <input type="hidden" class="form-control" name="old_description" value="{{ $role->description }}">
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-2 col-md-offset-3">
                    <button type="submit" class="btn btn-primary">
                        编辑
                    </button>
                </div>
            </div>
        </form>

    </div>
    </div>
</div>
@endsection
