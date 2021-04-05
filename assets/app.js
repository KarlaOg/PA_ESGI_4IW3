import './styles/app.css';
import $ from 'jquery';
import List from 'list.js';

$(document).ready(function () {
  var options = {
    valueNames: ['lastname', 'firstname', 'email', 'roles'],
  };

  var userList = new List('user-list', options);
});
