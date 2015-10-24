var Renderer = JSClass({
    create: function(toHide, toShow, response) {
        this.status 	= response.status;
				this.color 		= response.data.color;
				this.message 	= response.data.message;
				this.responseData = response.data.params;
				this.toHide		= toHide;
				this.toShow		= toShow;
    },
		determineAction: function() {
				if(this.status) {
						this.renderShortenedURL();
				} else {
						this.renderErrorMessage();
				}
		},
		renderShortenedURL: function() {
				var jumboTron =
				'<div class="panel panel-default panel-shortened">'
					+'<h1>Nice m8!</h1>'
					+'<p>'+this.message+'.</p>'
					+'<p>Given link alias is:</p>'
					+'<input class="form-control shortened-input-readonly" id="focusedInput" type="text" readonly="true" value="'+this.responseData.short_url+'">'
					+'<p><a class="btn btn-primary btn-lg" href="'+this.responseData.short_url+'" role="button">Go there!</a></p>'
				+'</div>';

				$(this.toHide).hide();
				$(this.toShow).append(jumboTron);
				$(this.toShow).show();

				return true;
		}
});
