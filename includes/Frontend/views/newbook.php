<h2 class="booking-section">BOOKING SECTION</h2>

<!-- Facility section -->
<?php
$is_date = false;
if(isset($_POST['sbks_select_date'])){
    $is_date = true;
    $date_1 = $_POST['sbks_select_date'] ? sanitize_text_field( $_POST['sbks_select_date'] ) : date("Y-m-d");
}

?>
<form action="" method="post">
    <div class="search-form">
        <div style="display: inline-flex;"><label>Select Date : </label><input id="sbks_select_date" type="date" name="sbks_select_date" class="sbks_select_date" value="<?php echo ($is_date) ? ($date_1) : (date('Y-m-d')); ?>"></div>
        <div><input type="submit" name="sbks_select_date_submit" class="sbks_select_date_submit" value="search"></div>
    </div>
</form>
<div class="facility-section">
    <div class="toast-container"></div>
    <div class="count-one">
        <div class="count">1</div>
    </div>
    <div class="facility-show-product">
        <div class="facility-selectable-section">
            <?php
            $count = 1;
            foreach ( $products as $product ) {
                // $product = new \ONSBKS_Slots\Includes\WooCommerce\BookingSlotProduct($product);
                ?>
                <div class="facility-single-product">
                    <div>
                        <p><b><?php echo $product->get_title();?></b></p>
                        <p>price : <?php echo get_woocommerce_currency_symbol().$product->get_price();?></p>
                    </div>
                    
                    <button class="facility-btn  product-select" data-slot-id="<?php echo $count; ?>" data-product-id="<?php echo $product->get_id();?>">book</button>
                    
                </div>
            <?php
            $count++;
            }
            ?>
        </div>
    </div>

</div>
<!-- Facility section ends -->
<!-- slot section -->
<div class="slot-section">
    <div class="count-two">
        <div class="count">2</div>
    </div>
    <div style="display:none" class="slot-show">
        <div class="full slot-disable disable-section">
            <p>Please select a facility at first...</p>
        </div>
        <?php
            $count = 1;
            foreach ( $post_metas as $post_meta ) {
                ?>
        <div class="full slot-active <?php echo 'slot-section-'.$count; ?>">
            <form action="" method="post"> 
                    <div width="100%" class="sbks-container">

                        <div class="sbks-col">
                            <div class="sbks-row">Time</div>
                            <?php
                            foreach ( $times as $time ) {
                                $new = explode('_', $time);
                                ?>
                                <div class="sbks-row"><?php echo $new[0] .' '.$new[1]  ?></div>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                        foreach( $post_meta as $key => $post ) {
                            ?>
                            <div class="sbks-col">
                                <div style="text-align: center" class="sbks-row">
                                    <p><b><?php echo date("d", strtotime(trim($key,"sbks_product_date"))); ?></b></p>
                                    <p><?php echo date("l", strtotime(trim($key,"sbks_product_date"))); ?></p>
                                </div>
                            <?php
                            foreach( $post as $time_key => $value ) {
                                ?>
                                <div class="sbks-row">
                                    <?php
                                    if($value != 0){
                                        ?>
                                            <button data-date="<?php echo trim($key,"sbks_product_date");?>" data-time="<?php echo $time_key;?>" class="sbks-btn sbks-btn-gen gen"><?php echo $value . ' left'; ?></button>
                                        <?php
                                    }
                                    else{ ?>
                                        <button data-date="<?php echo trim($key,"sbks_product_date");?>" data-time="<?php echo $time_key;?>" class="sbks-btn sbks-btn-disable">  </button> <?php
                                    }
                                    ?>
                                </div>
                                <?php
                            }
                            ?>
                            </div>
                            <?php
                        }
                        ?>
                </div>
            </form>
        </div>
        <?php
        $count++;
            }
            ?>
        </div>
        <div class="d-card">
            <div>
                <h5>Select Slots</h5>
                <p>Please select a facility at first...</p>
            </div>
        </div>
    </div>
    <div class="facility-section">
        <div class="count-one">
            <div class="count">3</div>
        </div>
        <!-- <div style="display:none" class="facility-show-product payment-button"> -->
        <div style="display:none" class="payment-button">
            <button class="facility-btn proceed-to-payment" name="proceed_to_payment" id="proceed_to_payment">proceed to payment</button>
        </div>
        <div class="d-card-2">
            <div>
                <h5>Select Payment</h5>
                <p>Please select slots at first...</p>
            </div>
        </div>

    </div>

<!-- slot section ends -->
