(function($){
    // ============================on clicking single slot to edit==============================
    $(".modal").on("click", function() {
        $("#modalContainer").show();

        /* initialize loader */
        loadingController.create({
            target: $(".loader-container")
        });

        /* define all attrs from target single slot */
        const id = $(this).attr("data-id");
        const metaKey = $(this).attr("data-meta-key");

        /* add attrs to update button */
        $('.modal-update-btn').attr({"data-product-id": id, "data-meta-key": metaKey} );

        /*
        * prepare and send ajax request
        * fetch meta_value of metaKey
        */
        const body = {
            product_id: id,
            meta_key: metaKey,
            action: "sbks_admin_slot_list",
            _wpnonce: bkAjaxObj.nonce
        }
        $.post( bkAjaxObj.ajax_url, body, function(res){
            console.log(res);

            if ( res.success ) {
                /* Appending input fields to show res data slots accordingly */
                for( let i=0; i<(res.data.slots.length)/2; i++ ){
                    $('.modal-section-1').append(`
                    <div class="modal-input-div">
                        <label for="">${res.data.slots[i].time}</label>
                        <input type="number" class="sbks-modal-input" name="${res.data.slots[i].time}" value="${parseInt(res.data.slots[i].slot)}" >
                    </div>
                `);
                }
                for( let i=(res.data.slots.length)/2; i<res.data.slots.length; i++ ){
                    $('.modal-section-2').append(`
                    <div class="modal-input-div">
                        <label for="">${res.data.slots[i].time}</label>
                        <input type="number" class="sbks-modal-input" name="${res.data.slots[i].time}" value="${parseInt(res.data.slots[i].slot)}" >
                    </div>
                `);
                }
            }
            else {
                toastCtrl( {
                    target: $(".toast-container"),
                    class: 'toast-danger',
                    message: res.data.message,
                    duration: 3000
                });
            }
            loadingController.dismiss();
        });
    });

    // ======================================on closing modal======================================
    $(".close").on("click", function() {
        /* delete input fields if exists */
        let div = $("div");
        if ( div.hasClass("modal-input-div") ) {
            $(".modal-input-div").remove();
        }
        if ( div.hasClass("toast-target") ) {
            $(".toast-target").remove();
        }
        $("#modalContainer").hide();
    });

    // ==================================on updating modal==========================================
    $("form#sbks_update_form").submit(function(e) {
        e.preventDefault();

        /* define variables */
        let updateButton = $(".modal-update-btn");
        const id = updateButton.attr("data-product-id");
        const metaKey = updateButton.attr("data-meta-key");
        const form = $(this).serializeArray();
        //console.log( form );

        /* initialize loader */
        loadingController.create({
            target: $(".loader-container")
        });

        /*
        * prepare and send ajax request
        * update meta_key sbks_product_date%DATE%
        */
        const body = {
            action: "sbks_admin_update_list",
            _wpnonce: bkAjaxObj.unonce,
            product_id: id,
            meta_key: metaKey,
            form: form
        }
        $.post( bkAjaxObj.ajax_url, body, function (res){
            //console.log("res : ", res);
            loadingController.dismiss();
            if( res.success ) {
                toastCtrl({
                    target: $(".toast-container"),
                    class: 'toast-success',
                    message: res.data.message,
                    duration: 3000
                });
            }else {
                toastCtrl({
                    target: $(".toast-container"),
                    class: 'toast-danger',
                    message: res.data.message,
                    duration: 3000
                });
            }
        });
    });

    //============================= functions =====================================
    function toastCtrl(toast){
        toast.target.append(`
            <div class="${toast.class} toast-target">${toast.message}</div>
        `);
        setTimeout(function(){
            $(".toast-target").remove();
        }, toast.duration);
    }

    let loadingController = {
        create:function(loading){
            loading.target.append(`
               <div class="loader spinner-1">
                    <div class="loader spinner-2">
                        <div class="loader spinner-3">
                            <div class="loader spinner-4"></div>
                        </div>
                    </div>
                </div>
            `);
        },
        dismiss: function (){
            $(".loader").remove();
        }
    }

})(jQuery)