import Vue from 'vue';
import BannerPreview from './components/BannerPreview.vue';

window.remplib = window.remplib || {};

(function() {

    'use strict';

    remplib.banner = {

        fromModel: (model) => {
            let banner = {
                name: model['name'] || null,
                targetUrl: model['target_url'] || null,
                transition: model['transition'] || null,
                displayType: model['display_type'] || 'overlay',
                template: model['template'] || null,
                // overlay
                position: model['position'] || null,
                offsetVertical: model['offset_vertical'] || 0,
                offsetHorizontal: model['offset_horizontal'] || 0,
                closeText: model['close_text'] || null,
                closeable: model['closeable'] || null,
                displayDelay: model['display_delay'] || 0,
                closeTimeout: model['close_timeout'] || null,
                // inline
                targetSelector: model['target_selector'] || null,
                variant: model['variant_uuid']
            };

            if (banner.template === 'medium_rectangle') {
                banner.mediumRectangleTemplate = {
                    headerText: model['medium_rectangle_template']['header_text'] || "",
                    mainText: model['medium_rectangle_template']['main_text'] || "",
                    buttonText: model['medium_rectangle_template']['button_text'] || "",
                    width: model['medium_rectangle_template']['width'] || null,
                    height: model['medium_rectangle_template']['height'] || null,
                    backgroundColor: model['medium_rectangle_template']['background_color'] || null,
                    textColor: model['medium_rectangle_template']['text_color'] || null,
                    buttonBackgroundColor: model['medium_rectangle_template']['button_background_color'] || null,
                    buttonTextColor: model['medium_rectangle_template']['button_text_color'] || null,
                }
            }

            if (banner.template === 'overlay_rectangle') {
                banner.overlayRectangleTemplate = {
                    headerText: model['overlay_rectangle_template']['header_text'] || "",
                    mainText: model['overlay_rectangle_template']['main_text'] || "",
                    buttonText: model['overlay_rectangle_template']['button_text'] || "",
                    width: model['overlay_rectangle_template']['width'] || null,
                    height: model['overlay_rectangle_template']['height'] || null,
                    backgroundColor: model['overlay_rectangle_template']['background_color'] || null,
                    textColor: model['overlay_rectangle_template']['text_color'] || null,
                    buttonBackgroundColor: model['overlay_rectangle_template']['button_background_color'] || null,
                    buttonTextColor: model['overlay_rectangle_template']['button_text_color'] || null,
                    imageLink:  model['overlay_rectangle_template']['image_link'] || null,
                }
            }

            if (banner.template === 'bar') {
                banner.barTemplate = {
                    mainText: model['bar_template']['main_text'] || "",
                    buttonText: model['bar_template']['button_text'] || "",
                    backgroundColor: model['bar_template']['background_color'] || null,
                    textColor: model['bar_template']['text_color'] || null,
                    buttonBackgroundColor: model['bar_template']['button_background_color'] || null,
                    buttonTextColor: model['bar_template']['button_text_color'] || null
                }
            }

            if (banner.template === 'collapsible_bar') {
                banner.collapsibleBarTemplate = {
                    mainText: model['collapsible_bar_template']['main_text'] || "",
                    collapseText: model['collapsible_bar_template']['collapse_text'] || "",
                    buttonText: model['collapsible_bar_template']['button_text'] || "",
                    backgroundColor: model['collapsible_bar_template']['background_color'] || null,
                    textColor: model['collapsible_bar_template']['text_color'] || null,
                    buttonBackgroundColor: model['collapsible_bar_template']['button_background_color'] || null,
                    buttonTextColor: model['collapsible_bar_template']['button_text_color'] || null,
                    initialState: model['collapsible_bar_template']['initial_state'] || "expanded"
                }
            }

            if (banner.template === 'html') {
                banner.htmlTemplate = {
                    backgroundColor: model['html_template']['background_color'] || null,
                    textColor: model['html_template']['text_color'] || null,
                    fontSize: model['html_template']['font_size'] || null,
                    textAlign: model['html_template']['text_align'] || null,
                    text: model['html_template']['text'] || null,
                    css: model['html_template']['css'] || null,
                    dimensions: model['html_template']['dimensions'] || null,
                }
            }

            if (banner.template === 'short_message') {
                banner.shortMessageTemplate = {
                    backgroundColor: model['short_message_template']['background_color'] || null,
                    textColor: model['short_message_template']['text_color'] || null,
                    text: model['short_message_template']['text'] || null,
                }
            }

            return banner;
        },

        bindPreview: function(el, banner) {
            return new Vue({
                el: el,
                data: banner,
                render: h => h(BannerPreview, {
                    props: banner
                }),
            })
        },

    }

})();