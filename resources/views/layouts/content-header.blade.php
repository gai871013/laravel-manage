<section class="content-header">
    <h1>
        <i class="fa fa-{{ $icon or '' }}"></i>
        {{ $slot }}
        <small>{{ $title or '' }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.home') }}"><i class="fa fa-dashboard"></i> @lang('index.index')</a></li>
        {{ $nav or '' }}
        <li class="active">{{ $slot or '' }}</li>
    </ol>
</section>