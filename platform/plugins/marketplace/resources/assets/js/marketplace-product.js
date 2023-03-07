class MarketplaceProductManagement {
    init() {
        $(document).on('click', '.approve-product-for-selling-button', event => {
            event.preventDefault();
            $('#confirm-approve-product-for-selling-button').data('action', $(event.currentTarget).prop('href'));
            $('#approve-product-for-selling-modal').modal('show');
        });

        $(document).on('click', '#confirm-approve-product-for-selling-button', event => {
            event.preventDefault();
            let _self = $(event.currentTarget);

            _self.addClass('button-loading');

            $.ajax({
                type: 'POST',
                cache: false,
                url: _self.data('action'),
                success: res => {
                    if (!res.error) {
                        Woo.showSuccess(res.message);
                        $('.approve-product-warning').fadeOut(500);
                        window.location.reload();
                    } else {
                        Woo.showError(res.message);
                    }
                    _self.removeClass('button-loading');
                    _self.closest('.modal').modal('hide');
                },
                error: res => {
                    Woo.handleError(res);
                    _self.removeClass('button-loading');
                }
            });
        });
    }
}

$(document).ready(() => {
    new MarketplaceProductManagement().init();
});
