!function(e){"use strict";e(".menu-item.has-submenu .menu-link").on("click",(function(s){s.preventDefault(),e(this).next(".submenu").is(":hidden")&&e(this).parent(".has-submenu").siblings().find(".submenu").slideUp(200),e(this).next(".submenu").slideToggle(200)})),e("[data-trigger]").on("click",(function(s){s.preventDefault(),s.stopPropagation();var t=e(this).attr("data-trigger");e(t).toggleClass("show"),e("body").toggleClass("offcanvas-active"),e(".screen-overlay").toggleClass("show")})),e(".screen-overlay, .btn-close").click((function(){e(".screen-overlay").removeClass("show"),e(".mobile-offcanvas, .show").removeClass("show"),e("body").removeClass("offcanvas-active")})),e(".btn-aside-minimize").on("click",(function(){window.innerWidth<768?(e("body").removeClass("aside-mini"),e(".screen-overlay").removeClass("show"),e(".navbar-aside").removeClass("show"),e("body").removeClass("offcanvas-active")):e("body").toggleClass("aside-mini")})),e(".select-nice").length&&e(".select-nice").select2(),e((function(){e("#shop-url").on("keyup",(function(){var s=e(this).closest(".form-group").find("span small");s.html(s.data("base-url")+"/<strong>"+e(this).val().toLowerCase()+"</strong>")})).on("change",(function(){var s=this;e(".shop-url-wrapper").addClass("content-loading"),e(this).closest("form").find("button[type=submit]").addClass("btn-disabled").prop("disabled",!0),e.ajax({url:e(this).data("url"),type:"POST",data:{url:e(this).val(),reference_id:e("input[name=reference_id]").val()},success:function(t){e(".shop-url-wrapper").removeClass("content-loading"),t.error?e(".shop-url-status").removeClass("text-success").addClass("text-danger").text(t.message):(e(".shop-url-status").removeClass("text-danger").addClass("text-success").text(t.message),e(s).closest("form").find("button[type=submit]").prop("disabled",!1).removeClass("btn-disabled"))},error:function(){e(".shop-url-wrapper").removeClass("content-loading")}})})),e(".custom-select-image").on("click",(function(s){s.preventDefault(),e(this).closest(".image-box").find(".image_input").trigger("click")})),e(".image_input").on("change",(function(){!function(s){if(s.files&&s.files[0]){var t=new FileReader;t.onload=function(t){e(s).closest(".image-box").find(".preview_image").prop("src",t.target.result)},t.readAsDataURL(s.files[0])}}(this)})),e(document).on("click",".btn_remove_image",(function(s){s.preventDefault();var t=e(s.currentTarget).closest(".image-box").find(".preview-image-wrapper .preview_image");t.attr("src",t.data("default-image")),e(s.currentTarget).closest(".image-box").find(".image-data").val("")})),window.noticeMessages&&window.noticeMessages.length&&noticeMessages.map((function(e){Woo.showNotice(e.type,e.message,"")}))}))}(jQuery);