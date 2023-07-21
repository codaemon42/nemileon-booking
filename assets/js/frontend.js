(function($){

    let pageId;
    let productId;
    let slots = [];
    const facilitySingleProduct = $(".facility-single-product");
    const slotActive = $(".slot-active");

    /**
     * product selection
     */
    facilitySingleProduct.on("click", 'button.product-select', function(e){
        e.preventDefault();
        slots = [];
        $(".d-card").hide();
        $(".slot-show").show();
        pageId = $(this).attr("data-slot-id");
        productId = $(this).attr("data-product-id");
        $(".slot-active").hide();
        $(".slot-section-"+pageId).show();

        $(".facility-single-product").hide();
        $(this).parent().show().css("width", "100%");

        $(this).addClass("editable");
        $(this).removeClass("product-select");
        $(this).html("change");
    });

    facilitySingleProduct.on("click", 'button.editable', function(e){
        e.preventDefault();
        slots = [];
        const btnGen = $("button.sbks-btn-gen");
        $(this).removeClass("editable");
        $(this).addClass("product-select");
        $(this).html("Book");
        $(this).parent().show().css("width", "50%");
        $(".facility-single-product").show();
        $(".slot-show").hide();
        btnGen.removeClass("black");
        btnGen.addClass("gen");

        $("div.payment-button").hide();
        $(".d-card").show();
        $(".d-card-2").show();
    });

    /**
     * slot selection
     */
    slotActive.on("click", 'button.sbks-btn-gen', function(e){
         e.preventDefault();
        $(".d-card-2").hide();
        $("div.payment-button").show();
        //$(this).css("pointer-events","none");
         let time = $(this).attr("data-time");
         let date = $(this).attr("data-date");

         let slot = {
            date_time: date+'__'+time
        }
        
        let already = false;
        slots = slots.filter(function( obj ) {
            if(obj.date_time === slot.date_time){
                already = true;
                $(this).css("background", "#0BB0FF");
            }
                return obj.date_time !== slot.date_time;
        });
        if(!already){
            slots.push(slot);
            $(this).removeClass("gen");
            $(this).addClass("black");
        }
        else{
            $(this).removeClass("black");
            $(this).addClass("gen");
        }

        //console.log("slots : ", slots);
    });

    slotActive.on("click", 'button.sbks-btn-disable', function(e){
        e.preventDefault();
    });

    /**
     * ajax call to add to cart with custom cart meta and redirect to cart page
     */
     $(".payment-button").on("click", 'button#proceed_to_payment', function(e){
        e.preventDefault();

         let body = {
            action: 'sbks_select_action',
            _wpnonce: sbksAjaxObj.nonce,
            product_id: productId,
            slots: slots
        }
        $.post( sbksAjaxObj.ajax_url, body, function(res){
            console.log( "ajax response : ", res );
            if ( ! res.success ) {
                toastCtrl({
                    target: $(".toast-container"),
                    class: 'toast-danger',
                    message: res.data.message,
                    duration: 4000
                });
            } else {
                toastCtrl({
                    target: $(".toast-container"),
                    class: 'toast-success',
                    message: res.data.message,
                    duration: 4000
                });
                window.location.href = res.data.url;
            }

        } );
    });

    function toastCtrl(toast){
        toast.target.append(`
            <div class="${toast.class} toast-target">${toast.message}</div>
        `);
        setTimeout(function(){
            $(".toast-target").remove();
        }, toast.duration);
    }

})(jQuery)