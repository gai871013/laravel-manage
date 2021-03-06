<li class="header">MAIN NAVIGATION</li>
<li class="treeview"><a href="{{ route('admin.home') }}"><i class="fa fa-dashboard"></i><span>后台首页</span></a></li>
@foreach($navAll as $k=>$v)
    @if($v['parent_id'] == 0)
        <li class="treeview">
            <a href="javascript:;">
                <i class="fa fa-{{ $v['icon'] }}"></i>
                <span>{{ trans('admin.'.$v['lang']) }}{{ trans('admin.manage') }}</span>
                <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                @foreach($navAll as $kk => $vv)
                    @if($vv['parent_id'] == $v['id'])
                        @php
                            $url = $vv['route'] ? $vv['route'] : url('admin/'.$v['code']).'/'.$vv['code'];
                        @endphp
                        <li><a href="{{ $url }}" @if(!empty($vv['class']))clsss="{{ $vv['class'] }}"@endif><i
                                        class="fa fa-{{ $vv['icon'] }}"></i>
                                {{ trans('admin.'.$vv['lang']) }}</a></li>
                    @endif
                @endforeach
            </ul>
        </li>
    @endif
@endforeach