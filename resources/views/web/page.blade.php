@extends('member.dash')

@section('pageTitle', $data[0]->title)
@section('pageDesc', $data[0]->description)
@section('pageKeys', $data[0]->keywords)

@section('content')
<style>

.box-blue{padding-top:0px;}

</style>
<?= $data[0]->text ?>
		
@endsection
