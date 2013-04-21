var ls = ls || {};
ls.plugin = ls.plugin || {};


ls.plugin.tagextender = (function ($) {

    this.options = {

    };

    this.init = function() {
        ls.hook.inject([ls.blog,'loadInfo'],'\
            params.topic_type=ls.registry.get(\'plugin.tagextender.topic_type\');\
            params.topic_tags_grouped=ls.registry.get(\'plugin.tagextender.topic_tags_grouped\')',
            'loadInfoBefore');

        ls.hook.add('ls_blog_load_info_after',function(idBlog,result) {
            $('#topic_tags_grouped').remove();
            if(!result.sTagGroups) {
                $('#topic_tags').parent().show();
            } else {
                if (ls.registry.get('plugin.tagextender.hide_native_tags')) {
                    $('#topic_tags').parent().hide();
                }
                $('#topic_tags').parent().after(result.sTagGroups);
                ls.autocomplete.add($(".autocomplete-tags-sep"), aRouter['ajax']+'autocompleter/tag/', true);
            }
        });
    };

    jQuery(document).ready(function($){
        this.init();
    }.bind(this));

    return this;
}).call(ls.plugin.tagextender || {},jQuery);