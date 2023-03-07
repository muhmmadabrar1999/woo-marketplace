'use strict';

let Shippo = Shippo || {};

Shippo.init = () => {

    $(document).on('show.bs.modal', '#shippo-view-n-create-transaction', function (e) {
        const $self = $(e.currentTarget);
        const $related = $(e.relatedTarget);
        $self.find('.modal-body').html('');

        $.ajax({
            type: 'GET',
            url: $related.data('url'),
            beforeSend: () => {
                $related.addClass('button-loading');
            },
            success: res => {
                if (res.error) {
                    Woo.showError(res.message);
                } else {
                    $self.find('.modal-body').html(res.data.html);
                }
            },
            error: res => {
                Woo.handleError(res);
            },
            complete: () => {
                $related.removeClass('button-loading');
            }
        });
    });

    $(document).on('click', '#shippo-view-n-create-transaction .create-transaction', function (e) {
        const $self = $(e.currentTarget);

        $.ajax({
            type: 'POST',
            url: $self.data('url'),
            beforeSend: () => {
                $self.addClass('button-loading');
            },
            success: res => {
                if (res.error) {
                    Woo.showError(res.message);
                } else {
                    $('[data-bs-target="#shippo-view-n-create-transaction"]').addClass('d-none');
                    $('#shippo-view-n-create-transaction').modal('hide');
                    Woo.showSuccess(res.message);
                }
            },
            error: res => {
                Woo.handleError(res);
            },
            complete: () => {
                $self.removeClass('button-loading');
            }
        });
    });

    $(document).on('click', '#shippo-view-n-create-transaction .get-new-rates', function (e) {
        const $self = $(e.currentTarget);

        $.ajax({
            type: 'GET',
            url: $self.data('url'),
            beforeSend: () => {
                $self.addClass('button-loading');
            },
            success: res => {
                if (res.error) {
                    Woo.showError(res.message);
                } else {
                    Woo.showSuccess(res.message);
                    $self.addClass('d-none');
                    $self.parent().append(res.data.html)
                }
            },
            error: res => {
                Woo.handleError(res);
            },
            complete: () => {
                $self.removeClass('button-loading');
            }
        });
    });

    $(document).on('submit', '.update-rate-shipment', function (e) {
        e.preventDefault();
        const $self = $(e.currentTarget);
        const $button = $self.find('button[type=submit]');

        $.ajax({
            type: 'POST',
            url: $self.prop('action'),
            data: $self.serializeArray(),
            beforeSend: () => {
                $button.addClass('button-loading');
            },
            success: res => {
                if (res.error) {
                    Woo.showError(res.message);
                } else {
                    Woo.showSuccess(res.message);
                    $('#shippo-view-n-create-transaction').find('.modal-body').html(res.data.html);
                }
            },
            error: res => {
                Woo.handleError(res);
            },
            complete: () => {
                $button.removeClass('button-loading');
            }
        });
    });
};

$(() => {
    Shippo.init();
});
