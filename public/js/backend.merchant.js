$(document).ready(function (e) {
    $('.modal-detail-transaction').on('click', function (e) {
        e.preventDefault();
        var dataDump    = $(this).data('dump');
        $('#admin_url_value').attr('href', $('base').data('url') + '/backend/administrator/edit/' + dataDump.admin_user_id);
        $('#order_value').attr('href', $('base').data('url') + '/backend/order/detail/' + dataDump.order_id);
        $('#in_out_value').val(dataDump.in_out);
        $('#amount_value').val(dataDump.amount);
        $('#balance_total_value').val(dataDump.balance_total);
        $('#message_value').val(dataDump.message);
        $('#created_at_value').val(dataDump.created_at);

        $('#modal-detail-transaction').modal('show');

    });
});