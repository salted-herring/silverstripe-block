(function($){
    $.entwine('ss', function($) {
        var _interval    =    null;
        $('input[name="showBlockbyClass"]').entwine({
            onmatch: function(e) {
                if ($('input[name="showBlockbyClass"]:checked').val() == 0) {
                    $('#Pages').show();
                    $('#shownInClass').hide();
                }else{
                    $('#shownInClass').show();
                    $('#Pages').hide();
                }

                var backlink = $('.backlink').attr('href');
                    backlinks = backlink.split('/');
                if (backlinks[1] == 'pages') {
                    var pid = backlinks[backlinks.length-1],
                        cb = $('ul[name="Pages"] input.checkbox[value="'+pid+'"]').length > 0 ? $('ul[name="Pages"] input.checkbox[value="'+pid+'"]') : $('#Pages input.checkbox[value="'+pid+'"]');
                    cb.parent().find('*').css('pointer-events', 'none');
                    cb.parent().attr('title', 'You cannot remove block from the current working page. Please try to use Blocks list, or a different page');
                }

            },
            onchange: function(e) {
                if ($(this).val() == 0) {
                    $('#Pages').show();
                    $('#shownInClass').hide();
                }else{
                    $('#shownInClass').show();
                    $('#Pages').hide();
                }
            }
        });

        $('.rightsidebar').entwine({
            MinInnerWidth: 620,
            onmatch: function() {
                var rightsidebar_width  =   $('.rightsidebar').length > 0 ? $('.rightsidebar').outerWidth() : 0;
                _interval = setInterval(function() {
                    if ( $('.cms-content-fields').width() != $('#Form_ItemEditForm').width() - rightsidebar_width)) {
                        $('.cms-content-fields').width($('#Form_ItemEditForm').width() - rightsidebar_width);
                    } else if (_interval) {
                        clearInterval(_interval);
                        _interval = null;
                    }
                }, 100);
                $(window).resize(function(e) {
                    $('.cms-content-fields').width($('#Form_ItemEditForm').width() - rightsidebar_width);
                });
            },
            onadd: function() {
                if(this.parent('fieldset').length){
                    this._super();
                }
                this.updateLayout();
            },
            togglePanel: function(bool, silent) {
                this._super(bool, silent);
                this.updateLayout();
            },
            updateLayout: function() {
                var rightsidebar_width  =   $('.rightsidebar').length > 0 ? $('.rightsidebar').outerWidth() : 0;
                $('.cms-content-fields').width($('#Form_ItemEditForm').width() - rightsidebar_width);
            }
        });
    });

}(jQuery));
