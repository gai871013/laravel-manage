@extends('layouts.a')
@section('section-content')
	<div class="box box-primary">
		<div class="box-header"></div>
		<div class="box-body">

		</div>
	</div>
@endsection
@section('scripts')
	<script>
        setUrl('{{ route('admin.WeChat.message') }}');
	</script>
@ENDSECTION