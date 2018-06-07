$(document).ready(function () {
    var targetUrl   = $('base').data('url');

    $('#user_id_select2').select2({
        ajax: {
            url: targetUrl + '/api/find-user/',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (data, params) {
                // parse the results into the format expected by Select2
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data, except to indicate that infinite
                // scrolling can be used
                console.log(data);
                params.page = params.page || 1;

                return {
                    results: data,
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
        minimumInputLength: 1,
        templateResult: formatRepo, // omitted for brevity, see the source of this page
        templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
    });

    function formatRepo (repo) {
        if (repo.loading) return repo.text;

        var markup = "<div class='select2-result-repository clearfix'>" +
            "<div class='select2-result-repository__meta'>" +
            "<div class='select2-result-repository__title'>" + repo.name + " (" + repo.phone + ")</div>";


        markup += "<div class='select2-result-repository__statistics'></div>";

        return markup;
    }


    function formatRepoSelection (repo) {
        return repo.name || repo.text;
    }

    $('#user_id_select2').on('change', function (e) {
        var value   = $(this).val();
        $.get(targetUrl + '/api/get-last-order?user_id=' + value , function(data, status){
            if(Boolean(data) === false){
                return false;
            }

            $("#phone").val(data.phone);
            $("#full_name").val(data.full_name);
            $("#full_address").val(data.full_address);
            $("#address_note").val(data.address_note);
            $("#lng").val(data.lng);
            $("#lat").val(data.lat);
            $("#actual_weight").val(data.actual_weight);
            $("#actual_count").val(data.actual_count);
            $("#note").val(data.note);
        });
    });
});