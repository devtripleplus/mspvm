@extends('admin/main')

@section('navigation')
    <li>
        <a href="#">Settings</a>
    </li>
    <li>
        <a href="#">General</a>
    </li>
@endsection




@section('content')

<?php 
/*use \App\Http\Controllers\AdminSettingsController;
$networkSettings = AdminSettingsController::getNetworkSettings();
if(!$networkSettings['bandwidthsuspension']){
    if($networkSettings['bandwidthsuspension'] == 0){
        $dcolor = 'red';
        $ecolor = '';
    }
    else{
        $dcolor = '';
        $ecolor = 'green';
    }
}
if($networkSettings['networkadapter']) {
    $networkSettings['networkadapter'] = '';
}
if($networkSettings['maxbandwidth']) {
    $networkSettings['maxbandwidth'] = '';
}
if($networkSettings['speed_capping']) {
    $networkSettings['speed_capping'] = '';
}
if($networkSettings['limit']) {
    $networkSettings['limit'] = '';
}
*/


?>

<div class="col-sm-6">
    {!! Form::open( ['method' => 'post', 'files' => true] ) !!}

    

    <div class="form-group">
        <label>Network Adapter</label>
        {{ Form::select('networkadapter', ['eth0' => 'etho', 'eth1' => 'eth1', 'eth2' => 'eth3'], 'eth0', ['class' => 'form-control']) }}

    </div>

    <div class="form-group">
        <label>Node max bandwidth (In MB)</label>
        {!! Form::text('maxbandwidth', Settings('bandwidthsuspension'), ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>Speed Capping</label>
        {{ Form::checkbox('cap', 'true', null,['class' => 'checkbox']) }}

    </div>

    <div class="form-group capAmmount hide" >
        <label>Cap The Ammout (In MB)</label>
        <input type="number" class="form-control" name="limit">
        
    </div>

    

    <input type="submit" class="btn btn-primary" value="Update">

    {!! Form::close() !!}
    </div>
    <div class="col-sm-6">
        <div class="form-group">
        <label>Bandwidth Suspension</label>
        <div>
            <a href="/admin/settings/network/1" class="btn btn-primary" style="background: green">Enable</a>
            <a href="/admin/settings/network/2" class="btn btn-primary" style="background: red">Disable</a>
        </div>
        
    </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready( function(){
            $('.checkbox').click(function() {
              if ($(this).is(':checked')) {
                $('.capAmmount').removeClass('hide').addClass('show');
              }
              else{
                $('.capAmmount').removeClass('show').addClass('hide');
              }
            });
        })

    </script>

@endsection