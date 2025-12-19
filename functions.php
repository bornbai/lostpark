<?php

add_action( 'wp_enqueue_scripts', 'porto_child_css', 1001 );

// Load CSS
function porto_child_css() {
	// porto child theme styles
	wp_deregister_style( 'styles-child' );
	wp_register_style( 'styles-child', esc_url( get_stylesheet_directory_uri() ) . '/style.css' );
	wp_enqueue_style( 'styles-child' );

	if ( is_rtl() ) {
		wp_deregister_style( 'styles-child-rtl' );
		wp_register_style( 'styles-child-rtl', esc_url( get_stylesheet_directory_uri() ) . '/style_rtl.css' );
		wp_enqueue_style( 'styles-child-rtl' );
	}
}
// 仅在首页且未登录用户时自动弹出 Porto 登录弹窗
add_action('wp_footer', function () {
    if ( is_front_page() && !is_user_logged_in() ) {
        ?>
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            // 页面加载 3 秒后自动弹出 Porto 登录弹窗
            setTimeout(function(){
                var loginBtn = document.querySelector('.porto-link-login');
                if (loginBtn) {
                    loginBtn.click();
                }
            }, 3000);
        });
        </script>
        <?php
    }
});

// 登录表单前添加文字
add_action('woocommerce_login_form_start', function () {
    echo '<p class="custom-login-text">Sign up and get 15% off your first order!</p>';
});

// 移除 WooCommerce 用户中心的 Downloads 页面
function remove_downloads_from_account_menu( $items ) {
    unset( $items['downloads'] );
    return $items;
}
add_filter( 'woocommerce_account_menu_items', 'remove_downloads_from_account_menu', 999 );

// 禁用 Downloads 端点（避免右边继续显示）
function disable_wc_downloads_endpoint( $endpoints ) {
    unset( $endpoints['downloads'] );
    return $endpoints;
}
add_filter( 'woocommerce_get_settings_account', function( $settings ) {
    foreach ( $settings as $key => $setting ) {
        if ( isset( $setting['id'] ) && $setting['id'] === 'woocommerce_myaccount_downloads_endpoint' ) {
            unset( $settings[$key] );
        }
    }
    return $settings;
}, 10, 1 );


add_action('wp_footer', function () {
    ?>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const el = document.querySelector('.share-tiktok');
    
        if (el) {
            el.innerHTML = `
            <svg aria-hidden="true" class="e-font-icon-svg e-fab-tiktok" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg">
                <path d="M448,209.91a210.06,210.06,0,0,1-122.77-39.25V349.38A162.55,162.55,0,1,1,185,188.31V278.2a74.62,74.62,0,1,0,52.23,71.18V0l88,0a121.18,121.18,0,0,0,1.86,22.17h0A122.18,122.18,0,0,0,381,102.39a121.43,121.43,0,0,0,67,20.14Z"></path>
            </svg>`;
        }
    });
    </script>
    <?php
    });


// 登录成功后弹出欢迎弹窗
add_action('wp_footer', function () {
    // 仅在用户未登录时执行（避免重复触发）
    if ( ! is_user_logged_in() ) {
        ?>
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            // 监听AJAX完成事件
            jQuery(document).ajaxComplete(function(event, xhr, settings) {
                // 检查是否是Porto主题的登录请求
                if (settings.data && settings.data.includes('action=ajax_login')) {
                    var response = xhr.responseJSON;
                    
                    // 检查登录是否成功
                    if (response && response.success === true) {
                        // 显示登录成功弹窗
                        showLoginSuccessPopup();
                    }
                }
            });
			
			//添加shipping手机号
			
 
add_filter( 'woocommerce_checkout_fields', 'bbloomer_shipping_phone_checkout' );
 
function bbloomer_shipping_phone_checkout( $fields ) {
   $fields['shipping']['shipping_phone'] = array(
      'label' => 'Phone',
      'type' => 'tel',
      'required' => false,
      'class' => array( 'form-row-wide' ),
      'validate' => array( 'phone' ),
      'autocomplete' => 'tel',
      'priority' => 25,
   );
   return $fields;
}
  
add_action( 'woocommerce_admin_order_data_after_shipping_address', 'bbloomer_shipping_phone_checkout_display' );
 
function bbloomer_shipping_phone_checkout_display( $order ){
    echo '<p><b>Shipping Phone:</b> ' . get_post_meta( $order->get_id(), '_shipping_phone', true ) . '</p>';
}
			
			
				//添加shipping手机号
				
				//
			
			/**
 * @snippet       Hide "View Cart" @ Woo Shop Page
 * @tutorial      https://businessbloomer.com/woocommerce-customization
 * @author        Rodolfo Melogli, Business Bloomer
 * @compatible    WooCommerce 8
 * @community     Join https://businessbloomer.com/club/
 */
 
add_action( 'wp_footer', 'bbloomer_no_ajax_view_cart_button' );
    
function bbloomer_no_ajax_view_cart_button() {
   wc_enqueue_js( "
      $( document.body ).on('wc_cart_button_updated', function(){
         $('.added_to_cart.wc-forward').remove();
      });   
   " );
}

				/**
 * @snippet       Hide "View Cart" @ Woo Shop Page

 */		
			
			
            
            function showLoginSuccessPopup() {
                // 创建Porto风格弹窗HTML
                var popupHtml = `
                    <div class="porto-login-success-popup" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:999999;">
                        <div class="porto-popup-content" style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); background:#fff; border-radius:8px; max-width:450px; width:90%; box-shadow:0 5px 40px rgba(0,0,0,0.3); opacity:0; transform:translate(-50%, -50%) scale(0.9);">
                            <div class="popup-header" style="padding:25px; border-bottom:1px solid #eee; position:relative;">
                                <h3 style="margin:0; color:#222; font-size:20px;">👋 欢迎回来！</h3>
                                <button class="popup-close" style="position:absolute; top:15px; right:15px; background:none; border:none; font-size:24px; cursor:pointer; color:#999; padding:0; width:30px; height:30px; line-height:30px;">&times;</button>
                            </div>
                            <div class="popup-body" style="padding:20px 25px; color:#555; line-height:1.6;">
                                <p>您已成功登录！现在开始探索更多精彩内容吧。</p>
                            </div>
                            <div class="popup-footer" style="padding:0 25px 25px; text-align:center;">
                                <button class="popup-confirm" style="background:#0088cc; color:#fff; border:none; padding:12px 30px; border-radius:5px; cursor:pointer; font-size:14px; font-weight:600;">立即开始</button>
                            </div>
                        </div>
                    </div>
                `;
                
                // 添加到页面底部
                document.body.insertAdjacentHTML('beforeend', popupHtml);
                
                var popup = document.querySelector('.porto-login-success-popup');
                var content = popup.querySelector('.porto-popup-content');
                
                // 显示弹窗并添加动画
                popup.style.display = 'block';
                setTimeout(function() {
                    content.style.transition = 'all 0.3s ease';
                    content.style.opacity = '1';
                    content.style.transform = 'translate(-50%, -50%) scale(1)';
                }, 50);
                
                // 关闭弹窗函数
                function closePopup() {
                    content.style.opacity = '0';
                    content.style.transform = 'translate(-50%, -50%) scale(0.9)';
                    setTimeout(function() {
                        popup.style.display = 'none';
                        popup.remove();
                        
                        // 刷新页面以反映登录状态
                        window.location.reload();
                    }, 300);
                }
                
                // 绑定关闭事件
                popup.querySelector('.popup-close').addEventListener('click', closePopup);
                popup.querySelector('.popup-confirm').addEventListener('click', closePopup);
                popup.addEventListener('click', function(e) {
                    if (e.target === popup) closePopup();
                });
            }
        });
        </script>
        <?php
    }
});

 // checkout去除additional notes
add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );


 
