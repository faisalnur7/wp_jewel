<table class="wqpmb-table universal-setting">
    <thead>
        <tr>
            <th class="wqpmb-inside">
                <div class="wqpmb-table-header-inside">
                    <h3><?php echo esc_html__( 'Custom CSS', 'wc-quantity-plus-minus-button' ); ?></h3>
                </div>
                
            </th>
            <th>
            <div class="wqpmb-table-header-right-side"></div>
            </th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <td colspan="2">
                <div class="wqpmb-form-control">
                    <div class="form-label col-lg-12">
                        <label for="wqpmb-custom-css"><?php echo esc_html__( 'Add your custom CSS here', 'wc-quantity-plus-minus-button' ); ?></label>
                        <p class="description"><?php echo esc_html__( 'Write your custom CSS code here. It will be applied to the quantity buttons and input fields.', 'wc-quantity-plus-minus-button' ); ?></p>
                    </div>
                    <div class="form-field col-lg-12">
                        

                        <?php
                        $custom_css = isset( $our_data['custom_css'] ) ? $our_data['custom_css'] : '';
                        ?>
                        <textarea 
                        style="width:100%;height:200px;background:#0d3748;color:#CDDC39;font-family:monospace;padding:20px;border-radius:8px;"
                            name="custom_css" 
                            spellcheck="false"
                            id="wqpmb-custom-css" 
                            rows="10" 
                            class="large-text code"
                            placeholder="/* Example: */
.qib-button-wrapper button.qib-button {
    /* Your custom CSS here */
}"
                        ><?php echo esc_textarea( $custom_css ); ?></textarea>                       
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</table>
