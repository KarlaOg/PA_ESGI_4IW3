import './styles/app.css';
import List from 'list.js';
import 'alpinejs';

window.onload = () => {
  var options = {
    valueNames: ['lastname', 'firstname', 'email', 'name', 'role'],
  };

  var userList = new List('user-list', options);
};
