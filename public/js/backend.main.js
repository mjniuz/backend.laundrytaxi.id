$(document).ready(function () {
    $('.date').datepicker({
        format: "yyyy-mm-dd"
    });

    $('.date-time').datetimepicker({
        format:"Y-MM-DD LT"
    });

    $('.date-range').daterangepicker({
        timePicker: false,
        timePickerIncrement: 30,
        locale: {
            format: 'YYYY-MM-DD',
            cancelLabel: 'Clear'
        }
    }).on('cancel.daterangepicker', function(ev, picker) {
        //do something, like clearing an input
        $(this).val('');
    });


    // dynamic element
    $(document).on('focus', '.date-picker', function (e) {
        $(this).datepicker({
            format: "yyyy-mm-dd"
        });
    });


    $('.form').submit(function (e) {
        $(this).find("button[type='submit']").prop('disabled',true).text("Loading...");
    });


    $('.confirm').on('click', function (e) {
        $(this).prop('disabled',true).text("Loading...");
        if($(this).attr('type') === 'submit'){
            e.preventDefault();
            $(this).closest('form').submit();
        }
    });

    $(document).on('click', '.btn-confirm', function (e) {
        e.preventDefault();

        var link = $(this).attr('href');
        var title   = $(this).data('confirm');
        swal({
            title: 'Are you sure?',
            text: title,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then(function () {
            window.location.href = link;
        }).catch(swal.noop);
    });

    $(".delete").on("click", function (e) {
        e.preventDefault();

        var link = $(this).attr('href');
        swal({
            title: 'Are you sure?',
            text: "You will delete this and cannot be undo!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then(function () {
            window.location.href = link;
        }).catch(swal.noop);
    });

    $('.number').on('keyup', function (e) {
        $(this).val(numberWithCommas(parseFloatComma($(this).val())));
    });

    window.parseFloatComma = function (x) {
        var num = x.replace(/,/g, '');
        var afterDot   = num.split('.')[1];
        if(num.includes(".") === true && typeof afterDot !== 'undefined' && (afterDot.length === 0 || afterDot[afterDot.length -1] === '0')){
            return num;
        }

        if(!isNaN(num)){
            return Number(num);
        }

        return 0;
    };

    function numberWithCommas(y) {
        if(isNaN(y)){
            return 0;
        }

        var parts = y.toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return parts.join(".");
    }


    $(document).on('click', ".img", function (e) {
        var modal       = $("#modal-image");
        var modalImg    = $("#img-place");
        var captionText = $("#img-caption");

        modal.show();
        modalImg.attr('src', $(this).attr('src'));
        captionText.html($(this).attr('alt'));
    });

    $(document).on("click", "#close-modal-image, #img-place", function (e) {
        var modal       = $("#modal-image");
        modal.hide();
    });
});