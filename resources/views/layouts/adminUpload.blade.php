<script type="text/javascript">
    function daoru() {
        $("input[type='file']").click();
    }
    $(function () {
        $("input[type='file']").change(function () {
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
                        $('.filePath').val(data.filename);
                        $('.importData').fadeIn().css('display', 'inline');
                        $('.img-view').attr('src', data.url);
                        layer.msg('上传成功，请及时处理');

                    } else {
                        layer.alert(data.message);
                    }
                }
            });
        });
    });

</script>