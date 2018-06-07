$(document).ready(function (e) {
    $(".number-role").on('keyup', function (e) {
        e.preventDefault();
        var value   = $(this).val();

        if(value && $.isNumeric(value) === false)
        {
            swal(
                'Oops...',
                "Silahkan memasukan angka yang benar. Untuk menggunakan koma, silahkan pakai dot (.). Cth: 0.001 dan Bukan 0,001",
                'error'
            ).catch(swal.noop);

            // reset value
            $(this).val('');
        }
    });
    var img_min_width   = 400;
    var img_min_height  = 600;

    $('[data-toggle="tooltip"]').tooltip();
    $(document).on("click", ".btn-delete", function(){
        var ok = confirm("Are you sure want to delete this?");
        if(ok){
            return true;
        }else{
            return false;
        }
    });

    $('.image').change(function (e) {
		e.preventDefault();

        var img     = new Image();
        var file    = this.files && this.files[0];
        var newThis = this;
        img.src     = window.URL.createObjectURL( file );
        img.onload = function () {
            if(this.width < img_min_width || this.height < img_min_height){
                alert("Ukuran gambar minimal " + img_min_width + "px X " + img_min_height + 'px');
                $(newThis).val('');

                return false;
            }else{
                hideImg();
                readURL(newThis);

                var imgCroppie  = $('#img-croppie');
                $('.crop-result').toggle();
                imgCroppie.show();

                imgCroppie.html('');
                var basic       = imgCroppie.croppie({
                    viewport: {
                        width: 800,
                        height: 500
                    },
                    boundary: { width: 880, height: 520 }
                });

                basic.croppie('bind', {
                    url: img.src
                });
            }
        };
    });

    $('.crop-result').on('click', function (e) {
        e.preventDefault();
        $uploadCrop = $('#img-croppie');
        $uploadCrop.croppie('result', {
            type: 'canvas',
            size: {width: 1920, height: 1080}
        }).then(function (resp) {
            $.ajax({
                url: $('base').data('url') + '/admin/product/upload',
                method: 'post',
                data:{
                    '_token': $('#csrf-token').attr('content'),
                    'image_blob' : resp
                },
                beforeSend: function (e) {
                    $(this).prop('disabled', true);
                },
                success: function (result) {
                    if(result.status != 200){
                        alert('error, try again!')
                    }

                    $(this).prop('disabled', false);
                    $('.image-box').removeClass('hide');
                    $('#preview').attr('src', result.image_url);

                    $('input[name=image_crop]').val(result.image_name);
                    $('.crop-result').toggle();
                    $uploadCrop.toggle();
                    $uploadCrop.bind('');
                }
            });
        });
    });

    $('.img-modal').on('click', function (e) {
        e.preventDefault();
        var src     = $(this).attr('src');
        var modal   = $(this).attr('data-modal');
        $('#image-preview').attr('src', src);
        $('#modal-image').modal('show');
    });

    function hideImg() {
        $('.image-box').addClass('hide');
    }

    function readURL(input){
        if (input.files && input.files[0]) {
            var reader 		= new FileReader();
            var previewID	= $(input).attr('data-preview');

            reader.onload = function (e) {
                $('#' + previewID).attr('src', e.target.result);
                //$('.image-box').removeClass('hide');
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    $(document).on("click", ".show-as-modal", function(){
        var modal = $('<div class="modal fade" tabindex="-1" role="dialog">'+
                        '<div class="modal-dialog" role="document">'+
                            '<div class="modal-content">'+
                                '<div class="modal-header">'+
                                    '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                                    '<h4 class="modal-title"></h4>'+
                                '</div>'+
                                '<div class="modal-body">'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                    '</div>');
        var title = $(this).data("title");
        var modalLarge = $(this).data("modal-large");
        if(title != null){
            modal.find(".modal-title").html($(this).data("title"));
        }
        if(modalLarge == true){
            modal.find(".modal-dialog").addClass("modal-lg");
        }
        $("body").append(modal);
        $.ajax({
            url : $(this).attr("href"),
            success : function(data){
                var content = $(data).find(".main-content").html();
                modal.find(".modal-body").html(content);
                if(title == null){
                    modal.find(".modal-title").html($(data).find(".main-title").html());
                }
                modal.modal('show');
            }
        });
        modal.on('hidden.bs.modal', function () {
            modal.remove();
        });
        return false;
    });
});
