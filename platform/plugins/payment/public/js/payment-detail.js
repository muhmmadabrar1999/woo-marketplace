(()=>{"use strict";$((function(){$(document).on("click",".get-refund-detail",(function(e){e.preventDefault();var n=$(e.currentTarget);$.ajax({type:"GET",cache:!1,url:n.data("url"),beforeSend:function(){n.find("i").addClass("fa-spin")},success:function(e){e.error?Woo.showError(e.message):$(n.data("element")).html(e.data)},error:function(e){Woo.handleError(e)},complete:function(){n.find("i").removeClass("fa-spin")}})}))}))})();