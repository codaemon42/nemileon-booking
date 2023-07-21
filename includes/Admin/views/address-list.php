<?php
use ONSBKS_Slots\Includes\Admin\SlotList;
$slots = new SlotList();
?>
<div class="wrap">
      <h1 class="wp-heading-inline">Booking Slots</h1>

      <a href="<?php echo admin_url( 'admin.php?page=reactions-address-book&action=new' ) ?>" class="page-title-action">Add New</a>
      <hr class="wp-header-end">

    <div class="sbks-edit-section">
        <div class="sbks-search-list">
            <form method="post">
                <label class="sbks-search-title" > Date : </label>
                <input type="date" id="sbks_search_date" name="sbks_search_date" value="<?php echo date("Y-m-d") ?>" >
                <input type="submit" id="sbks_search_date_submit" name="sbks_search_date_submit" value="Search" >
            </form>
        </div>
        <div class="sbks-edit-wrap">
            <?php

                $id_title_key_array = $slots->get_id_title_key();
            foreach ( $id_title_key_array as $value ) {
                ?>
                <div class="sbks-single-edit modal" data-id="<?php echo $value['ID']; ?>" data-meta-key="<?php echo $value['meta_key']; ?>">
                    <p><?php echo $value['post_title']; ?></p>
                    <h3><?php echo date("jS", strtotime(trim($value['meta_key'], 'sbks_product_date'))); ?></h3>
                    <h6><?php echo date("F, Y", strtotime(trim($value['meta_key'], 'sbks_product_date'))); ?></h6>
                </div>
            <?php
            }
            ?>

        </div>
    </div>
<!--    <a href="#" class="modal">Click Me</a>-->

    <div id="modalContainer">
        <form id="sbks_update_form" method="post">
            <div class="modal-content">
                <div>
                    <span class="close">X</span>
                </div>
                <div class="toast-container"></div>
                <div class="sbks-contents">
                    <div class="modal-section-1">12:00 am -- 11:00 pm</div>
                    <div class="modal-section-2">12:00 pm -- 11:00 am</div>
                </div>
                <div class="loader-container"></div>
                <div class="modal-footer">
                    <button class="modal-update-btn" type="submit" >Update</button>
                </div>
            </div>
        </form>
    </div>
</div>


<?php
$page_links = $slots->get_pagination();

if ( $page_links ) {
    echo '<div class="tablenav" style="width: 99%;"><div class="tablenav-pages" style="margin: auto">' . $page_links . '</div></div>';
}
else{
    echo '<div class="tablenav" style="width: 99%;"><div class="tablenav-pages" style="margin: auto"> No More Slots available </div></div>';
}