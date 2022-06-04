<?php
function hangcu_admin_login_logo() { ?>
  <style type="text/css"> 
    body.login div#login h1 a {
      background-image: url( <?php echo get_stylesheet_directory_uri() . "/assets/images/admin-logo.png" ?> );
      background-size: 100px 100px;
      width: 100%;
      padding: 0;
      margin: 0;
      height: 100px;
    } 
  </style>
<?php }

function change_social_login_buttons() {?> 
  <script type="text/javascript">
      window._nsl.push(function ($) {
        $(document).ready(function () {
            var $main = $('#nsl-custom-login-form-main');

            $main.find('.nsl-container')
                .addClass('nsl-container-login-layout-below')
                .css('display', 'block');


            var $jetpackSSO = $('#jetpack-sso-wrap__action');
            if ($jetpackSSO.length) {
                $jetpackSSO
                    .append($main.clone().attr('id', 'nsl-custom-login-form-jetpack-sso'));

                $main.insertBefore('#jetpack-sso-wrap');
            } else {
                var $form = $('#loginform,#registerform,#front-login-form,#setupform');

                if ($form.parent().hasClass('tml')) {
                    $form = $form.parent();
                }

                $form.prepend($main);
            }
        });
      });

      jQuery(document).ready(function () {
        jQuery('#user_login').attr("placeholder", "Tài khoản");
        jQuery('#user_pass').attr("placeholder", "Mật khẩu");
      })
  </script>
  <style type="text/css">
    #nsl-custom-login-form-main {
      border-bottom: 1px solid silver;
      padding-bottom: 20px;
      margin-bottom: 30px;
      position: relative;
    }
    #nsl-custom-login-form-main:after {
      content: 'OR';
      border: 1px solid silver;
      border-radius: 50%;
      width: 30px;
      height: 30px;
      position: absolute;
      text-align: center;
      line-height: 30px;
      background: white;
      transform: translateX(calc(50% - 30px));
      left: 50%;
      bottom: -15px;
      font-weight: bold;
    }
    #nsl-custom-login-form-main .nsl-container-login-layout-below {
        clear: both;
        padding: 0;
    }

    #nsl-custom-login-form-main .nsl-button-facebook, #nsl-custom-login-form-main .nsl-button-google {
      background: transparent!important;
      border-radius: 5px;
      border: 1px solid silver;
      color: RGBA(0, 0, 0, 0.54);
      box-shadow: none;
    }
    #nsl-custom-login-form-main .nsl-button-facebook .nsl-button-svg-container {
      background: #4267b2;
      border-radius: 50%;
      transform: scale(.8);
    }
    #loginform .submit #wp-submit {
      width: 100%;
      margin-top: 20px
    }
    #loginform label[for='user_login'], #loginform label[for='user_pass'] {
      display: none;
    }
    #loginform input[type='text'], #loginform input[type='password'] {
      font-size: 18px;
      line-height: unset;
      padding: 5px;
      min-height: unset;
      height: 35px;
      border: 1px solid rgba(0, 0, 0, 0.2);
      outline: transparent none 0px;
      background: transparent;
    }
    #loginform .user-pass-wrap button span.dashicons {
      top: .15rem;
    }
</style>
<?php }