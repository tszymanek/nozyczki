$(window).load(function(){
  var urlInput      = $('input[name=url]');
  var urlGroup      = $('#urlGroup');
  var aliasMaybe    = $('input[name=alias]');
  var aliasGroup    = $('#aliasGroup');
  var shortenButton = $('button[name=submitShortener]');
  var rebootButton  = $('#rebootApp');
  var aliasEnabled  = false;

  var shorteningForm  = '#shorteningForm';
  var restMessage     = '#restMessage';

  // REST CLIENT
  var REST = new RESTClient('http://nozyczki.dev/api', shorteningForm, restMessage);

  // Toggle alias
    urlInput.focus(function(){
    if(!aliasEnabled) {
      aliasMaybe.slideToggle('fast');
      aliasEnabled = true;
    }
  });

  // Trigger shortening action
  shortenButton.click(function(){
      var urlGiven    = urlInput.val();
      var aliasGiven  = aliasMaybe.val();
      if(!validateURL(urlGiven)) {
          urlGroup.addClass('has-error');
          return false;
      } else {
          urlGroup.removeClass('has-error');
      }

      if(aliasGiven && !validateAlias(aliasGiven)) {
          aliasGroup.addClass('has-error');
          return false;
      } else if(aliasGiven && validateAlias(aliasGiven)) {
          aliasGroup.removeClass('has-error');
      }

      var response = REST.post(
        false,
        {
          'url' : urlGiven,
          'url_short' : aliasGiven
        }
      );
  });
});
