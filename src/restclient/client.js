var RESTClient = JSClass({
    create: function(baseUrl, toHide, toShow) {
        this.baseUrl  = baseUrl;
        this.toHide   = toHide;
        this.toShow   = toShow;
    },
    get: function(routeUrl, getParameters) {
        var getUrl = this.baseUrl;
        if(routeUrl.length > 1) {
            getUrl = getUrl + '/' + routeUrl;
        }

        var getRequest = $.get(
            getUrl,
            getParameters,
            function(response) {
              alert( "success" );
            })
            .done(function(response) {
              alert( "second success" );
            })
            .fail(function(response) {
              alert( "error" );
            })
            .always(function(response) {
              alert( "finished" );
        });
    },
    post: function(routeUrl, postParameters) {
        var postUrl = this.baseUrl;
        if(routeUrl.length > 1) {
            postUrl = postUrl + '/' + routeUrl;
        }
        var hideElement = this.toHide;
        var showElement = this.toShow;

        var postRequest = $.post(
            postUrl,
            postParameters,
            function(response) {
                var response =  {status:true, data: response};
                var renderer = new Renderer(hideElement, showElement, response);
                renderer.determineAction();
            })
            .fail(function(response) {
                return {status:false, data: response}
            });
    }
});
