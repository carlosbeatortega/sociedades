jQuery(document).ready(function(){
    var profilePicsDiv = document.getElementById('profile_pics');
    var myclick=document.getElementById('amigos');
    $("#amigos").click(function(){
    if (typeof(FB) != 'undefined' && FB != null ) {
         FB.getLoginStatus(function(response) {
          if (!response.authResponse) {
            profilePicsDiv.innerHTML = '<em>You are not connected</em>';
            return;
          }

          FB.api({ method: 'friends.get' }, function(result) {
            Log.info('friends.get response', result);
            var markup = '';
            var numFriends = result ? Math.min(5, result.length) : 0;
            numFriends =  result.length;
            if (numFriends > 0) {
              for (var i=0; i<numFriends; i++) {
                markup += (
                  '<fb:profile-pic size="square" ' +
                                  'uid="' + result[i] + '" ' +
                                  'facebook-logo="true"' +
                  '></fb:profile-pic>'
                );
              }
            }
            profilePicsDiv.innerHTML = markup;
            FB.XFBML.parse(profilePicsDiv);
          });
        });
    }
    });
});


