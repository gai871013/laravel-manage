@php
    $id = isset($id) ? $id : 0;
    $deep = isset($deep) ? (int)$deep : 1;
@endphp
@foreach($categories as $v)
    <option @if($id == $v['id']) selected
            @endif value="{{ $v['id'] }}">@for($i = 0; $i < $deep; $i++)&nbsp;&nbsp;&nbsp;
        &nbsp;@endfor {{ $v['catname'] }}</option>
    @if(isset($v['children']))
        @php($deep++)
        @include('tree.flowSelect', ['categories' => $v['children'], 'id'=> $id, 'deep' => $deep])
        @php($deep--)
    @endif
@endforeach