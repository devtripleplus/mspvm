@extends('admin/main')

@section('content')
    <div class="well">
        File: {{$template->path}} <br />
        Architecture: {{get_friendly_arc_name($template->architecture)}} <br /><br />
        {{$template->description}}
    </div>

    <h4>Deployment</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Server</th>
                <th>Status</th>
            </tr>
        </thead>
    @foreach ($deployment as $server)
        <tr>
            <td width="30%">
                {{$server['server']->name}}
            </td>
            <td>
                @if ($server['deployed'])
                    Deployed
                @elseif ($server['deploying'])
                    Deploying
                @else
                    <a href="{{route('admin.template-deploy', ['template_id' => $template->id, 'server_id' => $server['server']->id])}}">
                        Deploy
                    </a>
                @endif
            </td>
        </tr>
    @endforeach
    </table>
@endsection

@section('actionbar')
    <a href="{{route('admin.template-delete', ['template_id' => $template->id])}}" class="btn btn-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i> Delete</a>
@endsection