import './styles/app.scss';
import List from 'list.js';
import 'alpinejs';

window.onload = () => {
  var options = {
    valueNames: ['lastname', 'firstname', 'email', 'name', 'role'],
  };
  var optionsOffer = {
    valueNames: ['name', 'description', 'field', 'name', 'brand'],
  };
  var optionsBrand = {
    valueNames: ['name', 'username', 'description'],
  };
  var optionsInfluencer = {
    valueNames: ['name', 'username', 'description'],
  };

;

  var userList = new List('user-list', options);
  var offerList = new List('offer-list-user', optionsOffer);
  var brandList = new List('list-brand', optionsBrand);
  var influencerList = new List('list-influencer', optionsInfluencer);

};
