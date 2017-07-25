@extends('layouts.a')
@section('section-content')
    <div class="box box-primary">
        <form action="{{ route('admin.system.menuManage') }}" id="admin_system_menuManage" method="post">
        <div class="box-header">
            {{ csrf_field() }}
            <div class="btn-group" id="nestable-menu">
                <a class="btn btn-primary btn-sm" data-action="expand-all">
                    <i class="fa fa-plus-square-o"></i>&nbsp;展开
                </a>
                <a class="btn btn-primary btn-sm" data-action="collapse-all">
                    <i class="fa fa-minus-square-o"></i>&nbsp;收起
                </a>
            </div>
            <button class="btn btn-info btn-sm" type="submit"><i
                        class="fa fa-save"></i>&nbsp;@lang('admin.save')</button>
            <a class="btn btn-warning btn-sm" onclick="window.location=window.location.href"><i
                        class="fa fa-refresh"></i>&nbsp;@lang('admin.refresh')</a>
            <a class="btn btn-success btn-sm" href="{{ route('admin.system.menuEdit') }}"><i
                        class="fa fa-plus"></i>&nbsp;@lang('admin.addMenu')</a>
        </div>
        <div class="box-body">
                <div class="dd" id="nestable" style="width: 100%; max-width:100%;">
                    <ol class="dd-list">
                        @include('tree.branch',['menu'=>$menu,'path' => config('app.admin_path') ])
                    </ol>
                </div>
                <textarea name="menu" style="display: none;" id="nestable-output"></textarea>
        </div>
        </form>
    </div>
@endsection
@section('scripts')
    {{--    <script src="{{ asset('js/jquery.nestable.js') }}"></script>--}}
    <script>
        $(function () {
            $('#nestable-output').val("");
            var updateOutput = function (e) {
                var list = e.length ? e : $(e.target),
                    output = list.data('output');
//                console.log(list, output);
                if (window.JSON) {
                    output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
                } else {
                    output.val('JSON browser support required for this demo.');
                }
            };

            // activate Nestable for list 1
            $('#nestable').nestable().on('change', updateOutput);

            updateOutput($('#nestable').data('output', $('#nestable-output')));

            $('#nestable-menu').on('click', function (e) {
                var target = $(e.target),
                    action = target.data('action');
                if (action === 'expand-all') {
                    $('.dd').nestable('expandAll');
                }
                if (action === 'collapse-all') {
                    $('.dd').nestable('collapseAll');
                }
            });

        });
    </script>
@endsection
