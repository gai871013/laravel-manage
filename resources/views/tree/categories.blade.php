@foreach($categories as $v)
    @php
        $url = url($path .'/'.$v['code']);
        $route = !empty($v['route']) ? route($v['route']) : '';
        $url = empty($route) ? $url : $route;
    @endphp
    <li class="dd-item" data-id="{{ $v['id'] }}">
        <div class="dd-handle">
            <i class="fa fa-{{ $v['icon'] }}"></i>
            {{ !empty($v['remark']) ? $v['remark'] : trans('admin.' . $v['lang'] ) }}
            @if(!isset($v['children']))
                <a class="dd-nodrag" href="{{ $url }}">{{ $url }}</a>
            @endif
            <span class="pull-right dd-nodrag">
            <a href="{{ route('admin.system.menuEdit', ['id' => $v['id']]) }}"><i class="fa fa-edit"></i></a>
                <a href="{{ route('admin.system.menuDelete', ['id' => $v['id']]) }}" data-id="{{ $v['id'] }}"
                   class="delete"><i
                            class="fa fa-trash"></i></a>
        </span>
        </div>
        @if(isset($v['children']))
            <ol class="dd-list">
                @include('tree.menu', ['menu'=>$v['children'],'path'=>$url])
            </ol>
        @endif
    </li>
@endforeach