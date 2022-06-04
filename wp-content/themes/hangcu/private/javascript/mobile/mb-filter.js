const $ = jQuery;

const mbFilter = {
    init: function() {
        mbFilter.showFilter();
    },

    showFilter : function() {
        $(".handheld-sidebar-toggle .sidebar-toggler").off("click").on("click", function() {
            $(this).closest(".site-content").toggleClass("active-hh-sidebar");
            $("#primary").addClass("off-canvas-bg-opacity");
            $("body").toggleClass("off-canvas-active");
            setTimeout(function() {
                $('#primary.off-canvas-bg-opacity').on('click', function() {
                    $('#primary.off-canvas-bg-opacity').off('click');
                    $("#primary").removeClass("off-canvas-bg-opacity");
                    $("body").toggleClass("off-canvas-active");
                });
            }, 500);
            
        });
    }
}

module.exports = mbFilter;