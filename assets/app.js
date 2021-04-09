import './styles/app.css';
import $ from 'jquery';
import List from 'list.js';
import 'alpinejs';

$(document).ready(function () {
  var options = {
    valueNames: ['lastname', 'firstname', 'email', 'name'],
  };

  var userList = new List('user-list', options);
});
