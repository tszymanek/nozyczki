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
				if(this.status == true) {
						this.renderShortenedURL();
				} else {
						this.renderErrorMessage();
				}
		},
		renderShortenedURL: function() {
				var jumboTron =
				'<div class="panel panel-primary panel-shortened">'
						+'<div class="panel-heading">'
										+'<h3 class="panel-title">Nice m8!</h3>'
						+'</div>'
						+'<div class="panel-body">'
									+'<p>'+this.message+'</p>'
									+'<p>Given link alias is:</p>'
									+'<input class="form-control shortened-input-readonly" id="focusedInput" type="text" readonly="true" value="'+this.responseData.short_url+'">'
									+'<p><a class="btn btn-primary btn-lg" href="'+this.responseData.short_url+'" role="button">Go there!</a></p>'
						+'</div>'
					+'</div>';

				$(this.toHide).hide();
				$(this.toShow).append(jumboTron);
				$(this.toShow).show();

				return true;
		},
		renderErrorMessage: function() {
				var jumboTron =
				'<div class="panel panel-danger panel-shortened">'
					+'<div class="panel-heading">'
	                +'<h3 class="panel-title">Ooops!</h3>'
	      	+'</div>'
					+'<div class="panel-body">'
                +this.message
          +'</div>'
				+'</div>';

				$(this.toHide).hide();
				$(this.toShow).append(jumboTron);
				$(this.toShow).show();

				return true;
		}
});
