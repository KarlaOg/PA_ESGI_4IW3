import List from 'list.js';

$(document).ready(function () {
  var options = {
    valueNames: ['lastname', 'firstname', 'email', 'roles'],
  };

  var offerList = new List('offer-list', options);
});
