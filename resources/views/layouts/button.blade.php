<div class="form-group">
    <div class="col-md-10 col-md-offset-2">

        <div class="actions btn-set">
            <a type="button" name="back" onclick="window.history.go(-1)"
               class="btn btn-default"><i
                        class="fa fa-angle-left"></i> 返回</a>
            <button class="btn btn-default" type="reset"><i class="fa fa-refresh"></i> 重置
            </button>
            <button class="btn btn-success ajax-post no-refresh comfirm" type="submit">
                <i class="fa fa-check"></i> 保存
            </button>
            <input type="hidden" name="next" value="{{ $next or '' }}">
            <input type="hidden" name="info[id]" value="{{ $item->id or 0 }}">
        </div>
    </div>
</div>