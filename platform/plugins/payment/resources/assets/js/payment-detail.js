'use strict';

$(() => {
    $(document).on('click', '.get-refund-detail', function(e) {
        e.preventDefault();
        const $this = $(e.currentTarget);
        $.ajax({
            type: 'GET',
            cache: false,
            url: $this.data('url'),
            beforeSend: () => {
                $this.find('i').addClass('fa-spin');
            },
            success: res => {
                if (!res.error) {
                    $($this.data('element')).html(res.data);
                } else {
                    Woo.showError(res.message);
                }
            },
            error: res => {
                Woo.handleError(res);
            },
            complete: () => {
                $this.find('i').removeClass('fa-spin');
            }
        });
    });
});
