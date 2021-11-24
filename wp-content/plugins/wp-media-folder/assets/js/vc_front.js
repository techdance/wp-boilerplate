(function ($) {
    'use strict';
    /**
     * run masonry layout
     */
    function wpmfVcInitSlider($container) {
        var columns = parseInt($container.data('wpmfcolumns'));
        var gutterwidth = $container.data('gutterwidth');
        var autoplay = $container.data('auto_animation');
        if ($container.hasClass('flexslider-is-active')) {
            var columns_width = ($container.width() - (columns - 1) * gutterwidth) / columns;
            $container.find('.wpmf-gallery-item').width(columns_width);
            return;
        }

        if (jQuery().wpmfflexslider) {
            $container.imagesLoaded(function () {
                var columns_width = ($container.width() - (columns - 1) * gutterwidth) / columns;
                $container.addClass('flexslider-is-active');
                /* call flexslider function */
                $container.wpmfflexslider({
                    animation: 'slide',
                    animationLoop: true,
                    slideshow: (autoplay === 1),
                    smoothHeight: (columns === 1),
                    itemWidth: (columns === 1) ? 0 : columns_width,
                    itemMargin: (columns === 1) ? 0 : gutterwidth,
                    pauseOnHover: true,
                    slideshowSpeed: 5000,
                    prevText: "",
                    nextText: ""
                });
                setTimeout(function () {
                    $container.find('.wpmf-gallery-item').css({'width': columns_width + 'px', 'max-width': columns_width + 'px'});
                }, 120);
            });
        }
    }

    function wpmfVcInitMasonry($container) {
        if ($container.hasClass('masonry')) {
            $container.masonry('destroy');
        }

        $container.imagesLoaded(function () {
            var $postBox = $container.children('.wpmf-gallery-item');
            var o = wpmfVcCalculateGrid($container);
            $postBox.css({'width': o.columnWidth + 'px', 'margin-bottom': o.gutterWidth + 'px'});
            $container.masonry({
                itemSelector: '.wpmf-gallery-item',
                columnWidth: o.columnWidth,
                gutter: o.gutterWidth,
                transitionDuration: 400
            });
            $container.css('visibility', 'visible');
        });
    }

    function wpmfVcInitFlowsSlide($container) {
        $container.imagesLoaded(function () {
            var enableNavButtons = $container.data('button');
            if (typeof enableNavButtons !== "undefined" && parseInt(enableNavButtons) === 1) {
                $container.flipster({
                    style: 'coverflow',
                    buttons: 'custom',
                    spacing: 0,
                    loop: true,
                    autoplay: 5000,
                    buttonNext: '<i class="flipto-next material-icons"> keyboard_arrow_right </i>',
                    buttonPrev: '<i class="flipto-prev material-icons"> keyboard_arrow_left </i>',
                    onItemSwitch: function (currentItem, previousItem) {
                        $container.find('.flipster__container').height(jQuery(currentItem).height());
                    },
                    onItemStart: function (currentItem) {
                        $container.find('.flipster__container').height(jQuery(currentItem).height());
                    }
                });
            } else {
                $container.flipster({
                    style: 'coverflow',
                    spacing: 0,
                    loop: true,
                    autoplay: 5000,
                    onItemSwitch: function (currentItem, previousItem) {
                        $container.find('.flipster__container').height(jQuery(currentItem).height());
                    },
                    onItemStart: function (currentItem) {
                        $container.find('.flipster__container').height(jQuery(currentItem).height());
                    }
                });
            }
        });
    }

    function wpmfVcCalculateGrid($container) {
        let columns = parseInt($container.data('wpmfcolumns'));
        let gutterWidth = $container.data('gutterWidth');
        let containerWidth = $container.width();

        if (isNaN(gutterWidth)) {
            gutterWidth = 5;
        } else if (gutterWidth > 50 || gutterWidth < 0) {
            gutterWidth = 5;
        }

        if (parseInt(columns) < 2 || containerWidth <= 450) {
            columns = 2;
        }

        gutterWidth = parseInt(gutterWidth);

        let allGutters = gutterWidth * (columns - 1);
        let contentWidth = containerWidth - allGutters;

        let columnWidth = Math.floor(contentWidth / columns);

        return {columnWidth: columnWidth, gutterWidth: gutterWidth, columns: columns};
    }

    // gallery detect render
    window.InlineShortcodeView_vc_wpmf_gallery = window.InlineShortcodeView.extend( {
        render: function () {
            //var model_id = this.model.get( 'id' );
            window.InlineShortcodeView_vc_wpmf_gallery.__super__.render.call( this );
            var masonry_container = jQuery(this.el).find('.gallery-masonry');
            var tab_container;
            if (masonry_container.length) {
                tab_container = masonry_container.closest('.vc_tta-panel');
                if (tab_container.length) {
                    if (tab_container.hasClass('vc_active')) {
                        wpmfVcInitMasonry(masonry_container);
                    }
                } else {
                    wpmfVcInitMasonry(masonry_container);
                }
            }

            var slider_container = jQuery(this.el).find('.wpmfflexslider_life');
            if (slider_container.length) {
                tab_container = slider_container.closest('.vc_tta-panel');
                if (tab_container.length) {
                    if (tab_container.hasClass('vc_active')) {
                        wpmfVcInitSlider(slider_container);
                    }
                } else {
                    wpmfVcInitSlider(slider_container);
                }
            }
            return this;
        }
    });

    // gallery addon detect render
    window.InlineShortcodeView_vc_wpmf_gallery_addon = window.InlineShortcodeView.extend( {
        render: function () {
            window.InlineShortcodeView_vc_wpmf_gallery_addon.__super__.render.call( this );
            jQuery(this.el).find('.loading_gallery').hide();
            var masonry_container = jQuery(this.el).find('.gallery-masonry');
            var tab_container;
            if (masonry_container.length) {
                tab_container = masonry_container.closest('.vc_tta-panel');
                if (tab_container.length) {
                    if (tab_container.hasClass('vc_active')) {
                        wpmfVcInitMasonry(masonry_container);
                    }
                } else {
                    wpmfVcInitMasonry(masonry_container);
                }
            }

            var slider_container = jQuery(this.el).find('.gallery_addon_flexslider');
            if (slider_container.length) {
                tab_container = slider_container.closest('.vc_tta-panel');
                if (tab_container.length) {
                    if (tab_container.hasClass('vc_active')) {
                        wpmfVcInitSlider(slider_container);
                    }
                } else {
                    wpmfVcInitSlider(slider_container);
                }
            }

            var flowslide_container = jQuery(this.el).find('.flipster');
            if (flowslide_container.length) {
                tab_container = flowslide_container.closest('.vc_tta-panel');
                if (tab_container.length) {
                    if (tab_container.hasClass('vc_active')) {
                        wpmfVcInitFlowsSlide(flowslide_container);
                    }
                } else {
                    wpmfVcInitFlowsSlide(flowslide_container);
                }
            }
            return this;
        }
    });

    // pdf embed detect render
    window.InlineShortcodeView_vc_pdf_embed = window.InlineShortcodeView.extend( {
        render: function () {
            window.InlineShortcodeView_vc_pdf_embed.__super__.render.call( this );
            if (jQuery(this.el).find('.wpmf-pdfemb-viewer').length) {
                jQuery(this.el).find('.wpmf-pdfemb-viewer').pdfEmbedder();
            }
            return this;
        }
    });
})();
