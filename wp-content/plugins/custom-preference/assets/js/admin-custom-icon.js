
jQuery(document).ready(function(){
  const $ = jQuery;
  const containerImage = $('#display-image #image');
  var imageUrl;
  var icons = {};
  var saving = false;
  var pointSize = {
    realW: 0,
    realH: 0,
    fakeW: 0,
    fakeH: 0
  }
  var point = {x: 0, y: 0};
  var pointClick = {x: 0, y: 0};

  if ( typeof icondata !== 'undefined' ) {
    imageUrl = icondata.image_url;
    icons = icondata.icons;
    initSelectImage( imageUrl );
    renderListIcon();
  }

  $('.select-icon-btn').click(function(e) {
    $(this).addClass('selected');
    e.preventDefault();
    var image = wp.media({ 
        title: 'Upload Image',
        multiple: false
    }).open()
    .on('select', function(){
        var uploaded_image = image.state().get('selection').first();
        var image_url = uploaded_image.toJSON().url;
        initSelectImage(image_url);
    });
  });

  function initSelectImage(image_url) {
    $('#image-icon-selected').val(image_url);
    $('.select-icon-btn').removeClass('selected');

    var img = $('#display-image #image img');
    img.attr('src', image_url);
    imageUrl = image_url;

    // Create dummy image to get real width and height
    $("<img>").attr("src", image_url).load(function(){
        var realWidth = this.width;
        var realHeight = this.height;
        pointSize.realH = realHeight;
        pointSize.realW = realWidth;
        pointSize.fakeW = realWidth;

        $('#real-size').text(`Kích thước ${realWidth}px X ${realHeight}px`);
        $('#image-icon-size').val(realWidth);
        $('.btn-set-size').trigger('click');
        containerImage.css('width', realWidth)
        containerImage.css('height', realHeight);
        document.getElementById('image').onmousemove = function(e){
          let x = e.layerX;
          let y = e.layerY;
          let icon = $('#overlay-icon');
          let iconWidth = $('#image-icon-width').val();
          let iconHeight = $('#image-icon-height').val();
          icon.css({top: y-iconHeight, left: x-iconWidth, width: iconWidth, height: iconHeight});
          point.x = y-iconHeight;
          point.y = x-iconWidth;
        }
        // set overlay size
    });
  }

  $('.btn-set-size').click(function(e) {
    e.preventDefault();
    let size = $('#image-icon-size').val();
    if ( size < 1 ) size = 1;
    if ( size > 1000 ) size = 1000;
    $('#display-image #image img').css('width', size + "px");
    pointSize.fakeW = size;
    pointSize.fakeH = pointSize.fakeW / pointSize.realW * pointSize.realH;
    containerImage.css('width', pointSize.fakeW)
    containerImage.css('height', pointSize.fakeH);
  });

  $('body').on('click', '.btn-remove-icon', function(e) {
    let class_id = $(this).attr('class_id');
    if ( class_id ) {
      delete(icons[class_id]);
      renderListIcon();
    }
    return;
  })

  $('body').on('click', '.btn-save-config', function() {
   
    if ( saving ) return;
    // if (Object.keys(icons).length === 0) return;
    saving = true;
    let that = $(this);
    that.val('Đợi tí nhé');
    jQuery.ajax({
      url: '/wp-admin/admin-ajax.php',
      method: 'POST',
      data: {
        action: 'saveconfigicon',
        dataicon: {
          image_url: Object.keys(icons).length === 0 ? '' : imageUrl,
          icons
        }
      },
      success: function (res) {
        window.alert('Đã lưu danh sách Icons. Vui lòng xóa cache (nếu có) và kiểm tra lại trang chủ.')
      },
      error: function (err) {
        window.alert('Đã có lỗi khi lưu danh sách Icons. Vui lòng thử lại.')
        console.log(err);
      }, 
      complete: function() {
        saving = false;
        that.val('Lưu');
      }
    });
  })

  containerImage.on('click', function() {
    if (!imageUrl || imageUrl.trim() === '') return;
    pointClick = point;

    let iconWidth = $('#image-icon-width').val();
    let iconHeight = $('#image-icon-height').val();

    let css = `width: ${iconWidth}px; height: ${iconHeight}px; background: url(${imageUrl}) top left no-repeat; background-size: ${pointSize.fakeW}px ${pointSize.fakeH}px; background-position: ${-pointClick.y}px ${-pointClick.x}px; display: inline-block;`;

    flag = true;
    while(flag) {
      var classname = prompt("Tên class (viết liền, không dấu)", "");
      if (classname) {
        if ( icons[classname] ) {
          alert('Tên class đã tồn tại. Vui lòng chọn tên khác.');
        } else {
          flag = false;
          
          icons[classname] = css;
          renderListIcon();
        }
      } else {
        console.log('ahihi')
        flag = false;
      }
    }
  });

  function renderListIcon() {
    let rightContent = $('#lst-icons');

    let keys = Object.keys(icons);
    rightContent.html('')
    for(let i = 0; i < keys.length; i++) {
      rightContent.append(`<div style="width: 100%;"><i style="${icons[keys[i]]}"></i><span class_id="${keys[i]}" class="btn-remove-icon">${keys[i]} | Xóa</span></div>`);  
    }

  }


});