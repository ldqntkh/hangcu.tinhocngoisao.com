
#-------------Cấu hình static page-----------------
# 1. Checkout (xem image: http://prntscr.com/lfamjl)
# 2. Xây dựng cấu hình PC
#       - Tạo 1 page build pc với SLUG là build-pc (http://prntscr.com/lfamxl)
#       - Tạo 1 page share build pc với SLUG là share-buildpc ()
# 3. Xây dựng menu trên mobile (http://prntscr.com/lfankj)
#   3.1 Trang chủ (default)
#   3.2 Danh sách ngành hàng
#       - Tạo 1 page với SLUG là danh-sach-mat-hang (http://prntscr.com/lfappm)
#   3.3 Quản lý tài khoản (link trực tiếp đến trang my account)
#   3.4 Xây dựng cấu hình (Link trực tiếp đến trang build pc)
#   3.5 Sản phẩm đã xem (chưa làm)
#       - Tạo 1 page với Slug là san-pham-da-xem
#   3.6 Tư vấn mua hàng (liên kết với chat page)
#   3.7 Hot line (click vao sẽ gọi đến số hot line)
#   3.8 Hệ thống show room
#       - tạo 1 page với Slug là he-thong-show-room (http://prntscr.com/lfdktz)
#   3.9 Chức năng gửi mail nhắc khách hàng
#       . Trong mục Settings (http://prntscr.com/m7icgj)
#       . Trong mục Email Templates
#           + Cấu hình thời gian gửi email: http://prntscr.com/m7igvf
#           + Nội dung {{products.cart}} lúc nào cũng cần phải có vì dùng để hiển thị giỏ hàng
#       . Trong mục Abandoned Orders
#           + Dùng để theo dõi những giỏ hàng nào mà chưa thanh toán, ta có thể xem được chi tiết đơn hàng và tổng tiền của đơn hàng đó
# 4. Chỗ banner trên trang chủ (http://prntscr.com/mqe0ad)
#   4.1 Cột dưới cùng (http://prntscr.com/mqdy3w)
#      - Thêm widget Ảnh vào title Under Feature Section (http://prntscr.com/mqe01p)
#   4.2 Cột bên tay phải (http://prntscr.com/mqe1eg)
#      - Thêm widget Ảnh vào title Right Feature (http://prntscr.com/mqe1po)
#   4.3 Cột bên tay trái (http://prntscr.com/mqy29j)
#      - Thêm widget Ảnh vào title Left Feature (http://prntscr.com/mqy2mm)
# 5. Trang chi tiết sản phẩm
#   5.1 Sản phẩm có thể người dùng yêu thích (http://prntscr.com/nyg526)
#
# 6. Trang tracking order
#   - tạo 1 page với slug là "tracking_order" và chọn template là "Tracking Order"
#   - tạo 1 page với slug là "order-details" và chọn template là "Order detail"
# 7. Checkout
#   - tạo 1 page với slug là "checkout" và chọn template là "Custom checkout page"
#   - tạo 1 page với slug là "order-summary" với nội dung:
    <div class="thank-content">
        <h3 class="title">
            Cảm ơn bạn đã đặt hàng ở TinHocNgoiSao
        </h3>
        <p>Đơn hàng của bạn với mã số {link_order} đã được chúng tôi tiếp nhận.<br/>
            Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất để xác nhận lại đơn hàng của bạn</p>
        <p>Thời gian giao hàng dự kiến từ 3 đến 4 ngày làm việc</p>
        <p>Chi tiết về đơn hàng chúng tôi đã gửi đến địa chỉ email : {order_email} <br/>
            <i>Nếu bạn không nhận được email này vui lòng kiểm tra trong <strong>spam</strong> hoặc <strong>Junk folder</strong></i></p>
        
        <br/>
        <h3>Các câu hỏi thường gặp trong quá trình mua hàng tại cửa hàng của chúng tôi</h3>
        <p><a href="#">1. Chính sách bảo mật</a></p>
        <p><a href="#">2. Kiểm tra đơn hàng như thế nào?</a></p>
        <p><a href="#">3. Thời gian giao hàng</a></p>
        <p><a href="#">4. Chính sách bảo hành / đổi trả hàng</a></p>
    </div>
#   bạn có thể tùy chỉnh template theo ý mình nhưng nhất thiết phải có 2 tham số {order_email} và {link_order}
#   Không được thay đổi cấu trúc của 2 tham số này
#





############################### Cấu hình source ###############################
# Build css cho wp-content: dùng koala build file style.scss (online_shop_child) thành file custom-style.css (online_shop_child)
# BuildPC
#   Sử dụng build js webpack cho cả server và client
#   Build scss cho server, dùng koala


############################### Cấu hình preference ###############################
# google map key : .....
# showrooms : 
[
    {
        "default" : true,
        "store_name" : "Tin Học Ngôi Sao - Chi Nhánh Chính",
        "address" : "384/8/C1 Cộng Hòa, phường 13, Tân Bình,Hồ Chí Minh, Việt Nam",
        "phone" : "0123456789"
    }
]
# Cấu hình buildPC :
[
    {
        "name" : "Choose a product type",
        "value" : ""
    },
    {
        "name" : "Main",
        "value" : "main",
        "require-by" : null,
        "require" : true,
        "link" : null
    },
    {
        "name" : "Cpu",
        "value" : "cpu",
        "require-by" : ["main"],
        "require-field" : "socket",
        "require" : true,
        "link" : null
    },
    {
        "name" : "RAM",
        "value" : "ram",
        "require-by" : ["main"],
        "require-field" : "kenh-ram-ho-tro",
        "require" : true,
        "link" : null
    },
    {
        "name" : "SSD",
        "value" : "ssd",
        "require-by" : ["main"],
        "require-field" : "sata",
        "require" : false,
        "link" : "hdd"
    },
    {
        "name" : "HDD",
        "value" : "hdd",
        "require-by" : ["main"],
        "require-field" : null,
        "require" : false,
        "link" : "ssd"
    },
    {
        "name" : "Optane",
        "value" : "optane",
        "require-by" : ["main"],
        "require-field" : null,
        "require" : false,
        "link" : null
    },
    {
        "name" : "Power",
        "value" : "power",
        "require-by" : null,
        "require-field" : null,
        "require" : true,
        "link" : null
    },
    {
        "name" : "VGA",
        "value" : "vga",
        "require-by" : ["cpu", "power"],
        "require-field" : null,
        "require" : false,
        "link" : null
    },
    
    {
        "name" : "Case",
        "value" : "case",
        "require-by" : ["main", "power", "vga"],
        "require-field" : null,
        "require" : true,
        "link" : null
    },
    {
        "name" : "Screen",
        "value" : "screen",
        "require-by" : ["main", "vga"],
        "require-field" : null,
        "require" : true,
        "link" : null
    },
    {
        "name" : "Radiator",
        "value" : "radiator",
        "require-by" : null,
        "require-field" : null,
        "require" : false,
        "link" : null
    },
    {
        "name" : "Keyboard",
        "value" : "keyboard",
        "require-by" : null,
        "require-field" : null,
        "require" : true,
        "link" : null
    },
    {
        "name" : "Mouse",
        "value" : "mouse",
        "require-by" : null,
        "require-field" : null,
        "require" : true,
        "link" : null
    },
    {
        "name" : "Headphone",
        "value" : "headphone",
        "require-by" : null,
        "require-field" : null,
        "require" : false,
        "link" : null
    },
    {
        "name" : "Soundcase",
        "value" : "soundcase",
        "require-by" : null,
        "require-field" : null,
        "require" : false,
        "link" : null
    }
]

##################################Cấu hình .htaccess file
##################################
# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>

php_value max_execution_time 300

# END WordPress

# BEGIN W3TC Page Cache core
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    RewriteCond %{HTTPS} =on
    RewriteRule .* - [E=W3TC_SSL:_ssl]
    RewriteCond %{SERVER_PORT} =443
    RewriteRule .* - [E=W3TC_SSL:_ssl]
    RewriteCond %{HTTP:X-Forwarded-Proto} =https [NC]
    RewriteRule .* - [E=W3TC_SSL:_ssl]
    RewriteCond %{HTTP:Accept-Encoding} gzip
    RewriteRule .* - [E=W3TC_ENC:_gzip]
    RewriteCond %{HTTP_COOKIE} w3tc_preview [NC]
    RewriteRule .* - [E=W3TC_PREVIEW:_preview]
    RewriteCond %{REQUEST_METHOD} !=POST
    RewriteCond %{QUERY_STRING} =""
    RewriteCond %{HTTP_COOKIE} !(comment_author|wp\-postpass|w3tc_logged_out|wordpress_logged_in|wptouch_switch_toggle) [NC]
    RewriteCond %{REQUEST_URI} \/$
    RewriteCond "%{DOCUMENT_ROOT}/wp-content/cache/page_enhanced/%{HTTP_HOST}/%{REQUEST_URI}/_index%{ENV:W3TC_SSL}%{ENV:W3TC_PREVIEW}.html%{ENV:W3TC_ENC}" -f
    RewriteRule .* "/wp-content/cache/page_enhanced/%{HTTP_HOST}/%{REQUEST_URI}/_index%{ENV:W3TC_SSL}%{ENV:W3TC_PREVIEW}.html%{ENV:W3TC_ENC}" [L]
</IfModule>
# END W3TC Page Cache core


# BEGIN W3TC Browser Cache
<IfModule mod_mime.c>
    AddType text/css .css
    AddType text/x-component .htc
    AddType application/x-javascript .js
    AddType application/javascript .js2
    AddType text/javascript .js3
    AddType text/x-js .js4
    AddType video/asf .asf .asx .wax .wmv .wmx
    AddType video/avi .avi
    AddType image/bmp .bmp
    AddType application/java .class
    AddType video/divx .divx
    AddType application/msword .doc .docx
    AddType application/vnd.ms-fontobject .eot
    AddType application/x-msdownload .exe
    AddType image/gif .gif
    AddType application/x-gzip .gz .gzip
    AddType image/x-icon .ico
    AddType image/jpeg .jpg .jpeg .jpe
    AddType image/webp .webp
    AddType application/json .json
    AddType application/vnd.ms-access .mdb
    AddType audio/midi .mid .midi
    AddType video/quicktime .mov .qt
    AddType audio/mpeg .mp3 .m4a
    AddType video/mp4 .mp4 .m4v
    AddType video/mpeg .mpeg .mpg .mpe
    AddType video/webm .webm
    AddType application/vnd.ms-project .mpp
    AddType application/x-font-otf .otf
    AddType application/vnd.ms-opentype ._otf
    AddType application/vnd.oasis.opendocument.database .odb
    AddType application/vnd.oasis.opendocument.chart .odc
    AddType application/vnd.oasis.opendocument.formula .odf
    AddType application/vnd.oasis.opendocument.graphics .odg
    AddType application/vnd.oasis.opendocument.presentation .odp
    AddType application/vnd.oasis.opendocument.spreadsheet .ods
    AddType application/vnd.oasis.opendocument.text .odt
    AddType audio/ogg .ogg
    AddType application/pdf .pdf
    AddType image/png .png
    AddType application/vnd.ms-powerpoint .pot .pps .ppt .pptx
    AddType audio/x-realaudio .ra .ram
    AddType image/svg+xml .svg .svgz
    AddType application/x-shockwave-flash .swf
    AddType application/x-tar .tar
    AddType image/tiff .tif .tiff
    AddType application/x-font-ttf .ttf .ttc
    AddType application/vnd.ms-opentype ._ttf
    AddType audio/wav .wav
    AddType audio/wma .wma
    AddType application/vnd.ms-write .wri
    AddType application/font-woff .woff
    AddType application/font-woff2 .woff2
    AddType application/vnd.ms-excel .xla .xls .xlsx .xlt .xlw
    AddType application/zip .zip
</IfModule>
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType text/css A31536000
    ExpiresByType text/x-component A31536000
    ExpiresByType application/x-javascript A31536000
    ExpiresByType application/javascript A31536000
    ExpiresByType text/javascript A31536000
    ExpiresByType text/x-js A31536000
    ExpiresByType video/asf A31536000
    ExpiresByType video/avi A31536000
    ExpiresByType image/bmp A31536000
    ExpiresByType application/java A31536000
    ExpiresByType video/divx A31536000
    ExpiresByType application/msword A31536000
    ExpiresByType application/vnd.ms-fontobject A31536000
    ExpiresByType application/x-msdownload A31536000
    ExpiresByType image/gif A31536000
    ExpiresByType application/x-gzip A31536000
    ExpiresByType image/x-icon A31536000
    ExpiresByType image/jpeg A31536000
    ExpiresByType image/webp A31536000
    ExpiresByType application/json A31536000
    ExpiresByType application/vnd.ms-access A31536000
    ExpiresByType audio/midi A31536000
    ExpiresByType video/quicktime A31536000
    ExpiresByType audio/mpeg A31536000
    ExpiresByType video/mp4 A31536000
    ExpiresByType video/mpeg A31536000
    ExpiresByType video/webm A31536000
    ExpiresByType application/vnd.ms-project A31536000
    ExpiresByType application/x-font-otf A31536000
    ExpiresByType application/vnd.ms-opentype A31536000
    ExpiresByType application/vnd.oasis.opendocument.database A31536000
    ExpiresByType application/vnd.oasis.opendocument.chart A31536000
    ExpiresByType application/vnd.oasis.opendocument.formula A31536000
    ExpiresByType application/vnd.oasis.opendocument.graphics A31536000
    ExpiresByType application/vnd.oasis.opendocument.presentation A31536000
    ExpiresByType application/vnd.oasis.opendocument.spreadsheet A31536000
    ExpiresByType application/vnd.oasis.opendocument.text A31536000
    ExpiresByType audio/ogg A31536000
    ExpiresByType application/pdf A31536000
    ExpiresByType image/png A31536000
    ExpiresByType application/vnd.ms-powerpoint A31536000
    ExpiresByType audio/x-realaudio A31536000
    ExpiresByType image/svg+xml A31536000
    ExpiresByType application/x-shockwave-flash A31536000
    ExpiresByType application/x-tar A31536000
    ExpiresByType image/tiff A31536000
    ExpiresByType application/x-font-ttf A31536000
    ExpiresByType application/vnd.ms-opentype A31536000
    ExpiresByType audio/wav A31536000
    ExpiresByType audio/wma A31536000
    ExpiresByType application/vnd.ms-write A31536000
    ExpiresByType application/font-woff A31536000
    ExpiresByType application/font-woff2 A31536000
    ExpiresByType application/vnd.ms-excel A31536000
    ExpiresByType application/zip A31536000
</IfModule>
<IfModule mod_deflate.c>
    <IfModule mod_filter.c>
        AddOutputFilterByType DEFLATE text/css text/x-component application/x-javascript application/javascript text/javascript text/x-js text/html text/richtext text/plain text/xsd text/xsl text/xml image/bmp application/java application/msword application/vnd.ms-fontobject application/x-msdownload image/x-icon application/json application/vnd.ms-access video/webm application/vnd.ms-project application/x-font-otf application/vnd.ms-opentype application/vnd.oasis.opendocument.database application/vnd.oasis.opendocument.chart application/vnd.oasis.opendocument.formula application/vnd.oasis.opendocument.graphics application/vnd.oasis.opendocument.presentation application/vnd.oasis.opendocument.spreadsheet application/vnd.oasis.opendocument.text audio/ogg application/pdf application/vnd.ms-powerpoint image/svg+xml application/x-shockwave-flash image/tiff application/x-font-ttf application/vnd.ms-opentype audio/wav application/vnd.ms-write application/font-woff application/font-woff2 application/vnd.ms-excel
    <IfModule mod_mime.c>
        # DEFLATE by extension
        AddOutputFilter DEFLATE js css htm html xml
    </IfModule>
    </IfModule>
</IfModule>
<FilesMatch "\.(css|htc|less|js|js2|js3|js4|CSS|HTC|LESS|JS|JS2|JS3|JS4)$">
    FileETag MTime Size
    <IfModule mod_headers.c>
         Header unset Set-Cookie
    </IfModule>
</FilesMatch>
<FilesMatch "\.(html|htm|rtf|rtx|txt|xsd|xsl|xml|HTML|HTM|RTF|RTX|TXT|XSD|XSL|XML)$">
    FileETag MTime Size
    <IfModule mod_headers.c>
        Header append Vary User-Agent env=!dont-vary
    </IfModule>
</FilesMatch>
<FilesMatch "\.(asf|asx|wax|wmv|wmx|avi|bmp|class|divx|doc|docx|eot|exe|gif|gz|gzip|ico|jpg|jpeg|jpe|webp|json|mdb|mid|midi|mov|qt|mp3|m4a|mp4|m4v|mpeg|mpg|mpe|webm|mpp|otf|_otf|odb|odc|odf|odg|odp|ods|odt|ogg|pdf|png|pot|pps|ppt|pptx|ra|ram|svg|svgz|swf|tar|tif|tiff|ttf|ttc|_ttf|wav|wma|wri|woff|woff2|xla|xls|xlsx|xlt|xlw|zip|ASF|ASX|WAX|WMV|WMX|AVI|BMP|CLASS|DIVX|DOC|DOCX|EOT|EXE|GIF|GZ|GZIP|ICO|JPG|JPEG|JPE|WEBP|JSON|MDB|MID|MIDI|MOV|QT|MP3|M4A|MP4|M4V|MPEG|MPG|MPE|WEBM|MPP|OTF|_OTF|ODB|ODC|ODF|ODG|ODP|ODS|ODT|OGG|PDF|PNG|POT|PPS|PPT|PPTX|RA|RAM|SVG|SVGZ|SWF|TAR|TIF|TIFF|TTF|TTC|_TTF|WAV|WMA|WRI|WOFF|WOFF2|XLA|XLS|XLSX|XLT|XLW|ZIP)$">
    FileETag MTime Size
    <IfModule mod_headers.c>
         Header unset Set-Cookie
    </IfModule>
</FilesMatch>
<FilesMatch "\.(bmp|class|doc|docx|eot|exe|ico|json|mdb|webm|mpp|otf|_otf|odb|odc|odf|odg|odp|ods|odt|ogg|pdf|pot|pps|ppt|pptx|svg|svgz|swf|tif|tiff|ttf|ttc|_ttf|wav|wri|woff|woff2|xla|xls|xlsx|xlt|xlw|BMP|CLASS|DOC|DOCX|EOT|EXE|ICO|JSON|MDB|WEBM|MPP|OTF|_OTF|ODB|ODC|ODF|ODG|ODP|ODS|ODT|OGG|PDF|POT|PPS|PPT|PPTX|SVG|SVGZ|SWF|TIF|TIFF|TTF|TTC|_TTF|WAV|WRI|WOFF|WOFF2|XLA|XLS|XLSX|XLT|XLW)$">
    <IfModule mod_headers.c>
         Header unset Last-Modified
    </IfModule>
</FilesMatch>
<IfModule mod_headers.c>
    Header set Referrer-Policy "no-referrer-when-downgrade"
</IfModule>
# END W3TC Browser Cache
#########################################################
#########################################################

#