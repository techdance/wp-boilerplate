/* global fusionAppConfig, FusionPageBuilderViewManager, imagesLoaded */
/* jshint -W098 */
/* eslint no-unused-vars: 0 */
var FusionPageBuilder = FusionPageBuilder || {};

(function () {
    /**
     * run masonry layout
     */
    function wpmfAvadaInitSlider($container, params) {
        var columns = parseInt(params.columns);
        var gutterwidth = params.gutterwidth;
        var autoplay = 0;
        if (jQuery().wpmfflexslider) {
            imagesLoaded($container, function () {
                var container_width = $container.width();
                var columns_width = (container_width - (columns - 1) * gutterwidth) / columns;
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
                $container.css('max-width', (container_width) + 'px');
                setTimeout(function () {
                    $container.find('.wpmf-gallery-item').css({
                        'width': columns_width + 'px',
                        'max-width': columns_width + 'px'
                    });
                }, 120);
            });
        }
    }

    function wpmfAvadaInitMasonry($container) {
        var $grid = $container.isotope({
            itemSelector: '.wpmf-gallery-item',
            percentPosition: true,
            layoutMode: 'packery',
            resizable: true,
            initLayout: true
        });

        // layout Isotope after each image loads
        $grid.find('.wpmf-gallery-item').imagesLoaded().progress( function() {
            setTimeout(function () {
                $grid.isotope('layout');
                $grid.find('.wpmf-gallery-item').addClass('masonry-brick');
            },200);
        });
    }

    jQuery(document).ready(function ($) {
        FusionPageBuilder.wpmf_avada_pdf_embed = FusionPageBuilder.ElementView.extend({
            onRender: function () {
                this.afterPatch();
            },

            afterPatch: function() {
                var container = this.$el;
                var element_type = this.model.attributes.element_type;
                var params = this.model.attributes.params;
                if (element_type === 'wpmf_avada_pdf_embed' && params.url !== '' && params.embed === 'on') {
                    if (container.find('.wpmf-pdfemb-viewer').length) {
                        container.find('.wpmf-pdfemb-viewer').pdfEmbedder();
                    }
                }
            }
        });

        FusionPageBuilder.wpmf_fusion_gallery = FusionPageBuilder.ElementView.extend({
            onRender: function () {
                this.afterPatch();
            },

            beforePatch: function() {
                var container = this.$el;
                var masonry_container = container.find('.wpmf-gallerys');
                masonry_container.remove();
            },

            afterPatch: function() {
                var container = this.$el;
                var params = this.model.attributes.params;
                if (params.items !== '' || (params.gallery_folders === 'yes' && parseInt(params.gallery_folder_id) !== 0)) {
                    var masonry_container = container.find('.gallery-masonry');
                    if (masonry_container.length) {
                        if (masonry_container.find('.wpmf-gallery-item').length) {
                            wpmfAvadaInitMasonry(masonry_container);
                        }
                    }

                    var slider_container = container.find('.wpmfflexslider_life');
                    if (slider_container.length) {
                        wpmfAvadaInitSlider(slider_container, params);
                    }
                }
            }
        });
    });
}(jQuery));
