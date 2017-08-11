<script type="text/javascript">
    globalIndex = 0;

    function daoru(index) {
        globalIndex = typeof index === 'undefined' ? 0 : index;
        $("input[type='file']").eq(globalIndex).click();
        upload();
    }

    /**
     * 上传方法 2017-8-11 15:03:27
     */
    function upload() {
        $("input[type='file']").eq(globalIndex).change(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                }
            });
            var action = "{{ route('admin.upload') }}";
            //创建FormData对象
            var data = new FormData();
            //为FormData对象添加数据
            $.each($(this)[0].files, function (i, file) {
                data.append('upload_file', file);
            });
            $.ajax({
                url: action,
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,    //不可缺
                processData: false,
                dataType: 'json',    //不可缺
                success: function (data) {
                    if (data.status_code == 20001) {
                        $('.filePath').eq(globalIndex).val(data.filename);
                        $('.importData').eq(globalIndex).fadeIn().css('display', 'inline');
                        $('.img-view').eq(globalIndex).attr('src', data.url);
                        layer.msg('上传成功，请及时处理');

                    } else {
                        layer.alert(data.message);
                    }
                }
            });
        });
    }

</script>