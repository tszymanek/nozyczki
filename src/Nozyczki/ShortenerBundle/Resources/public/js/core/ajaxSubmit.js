jQuery(document).ready(function () {
    var aliasInput = "#link_aliases_0_alias";
    $(aliasInput).focusout(function () {
        var alias = ($(this).val());
        $.ajax({
            url: Routing.generate("ajax_alias_exists"),
            data: { "alias" : alias },
            success: function (data) {
                if(data.aliasExists) {
                    $(aliasInput).parent().parent().removeClass("has-error").addClass("has-feedback has-success");
                    $(aliasInput).parent().siblings().removeClass("fui-cross").addClass("fui-check");
                } else {
                    $(aliasInput).parent().parent().removeClass("has-success").addClass("has-feedback has-error");
                    $(aliasInput).parent().siblings().last().removeClass("fui-check").addClass("fui-cross");
                }
            }
        });
    })
});