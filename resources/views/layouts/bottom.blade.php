@php
    $index = isset($index) ? $index : 0;
@endphp
<div class="bottom">
    <ul class="nav">
        <li @if($index == 0)class="on"@endif><a href="{{ route('weChat.home') }}"><p>@lang('index.index')</p>
            </a>
        </li>
        <li @if($index == 1)class="on"@endif><a href="{{ route('weChat.task') }}"><p>@lang('index.myTask')</p>
            </a></li>
        <li @if($index == 2)class="on"@endif><a href="{{ route('weChat.journeys') }}">
                <p>@lang('index.myJourney')</p></a></li>
    </ul>
</div>