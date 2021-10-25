@extends('main');
@section('content')
<?php
if(Auth::user()){ ?>
<h3 align='center'>welcome {{Auth::user()->name}}</h3>
<?php }
?>
@endsection
