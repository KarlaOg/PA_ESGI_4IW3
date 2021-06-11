import './styles/app.scss';
import List from 'list.js';
import 'alpinejs';


/**
 * Influenceurs
*/

import logoInf1 from './images/influenceurs/cyprien.jpg';
import logoInf2 from './images/influenceurs/enjoy-phoenix.jpg';
import logoInf3 from './images/influenceurs/inoxtag.jpg';
import logoInf4 from './images/influenceurs/lena-situation.jpg';
import logoInf5 from './images/influenceurs/mister-v.jpg';
import logoInf6 from './images/influenceurs/natoo.jpg';
import logoInf7 from './images/influenceurs/squeezie.jpg';
import logoInf8 from './images/influenceurs/tibo_inshape.jpg';

let htmlInf1 = `<img src="${logoInf1}" alt="logo influenceur">`;
let htmlInf2 = `<img src="${logoInf2}" alt="logo influenceur">`;
let htmlInf3 = `<img src="${logoInf3}" alt="logo influenceur">`;
let htmlInf4 = `<img src="${logoInf4}" alt="logo influenceur">`;
let htmlInf5 = `<img src="${logoInf5}" alt="logo influenceur">`;
let htmlInf6 = `<img src="${logoInf6}" alt="logo influenceur">`;
let htmlInf7 = `<img src="${logoInf7}" alt="logo influenceur">`;
let htmlInf8 = `<img src="${logoInf8}" alt="logo influenceur">`;



/**
 * Catégories
*/

import logoC1 from './images/categories/alimentation.jpg';
import logoC2 from './images/categories/beauty.jpg';
import logoC3 from './images/categories/deco.jpg';
import logoC4 from './images/categories/gaming.jpg';
import logoC5 from './images/categories/instagramer.jpg';
import logoC6 from './images/categories/musique.jpg';
import logoC7 from './images/categories/maquillage.jpg';

let htmlC1 = `<img src="${logoC1}" alt="logo categorie">`;
let htmlC2 = `<img src="${logoC2}" alt="logo categorie">`;
let htmlC3 = `<img src="${logoC3}" alt="logo categorie">`;
let htmlC4 = `<img src="${logoC4}" alt="logo categorie">`;
let htmlC5 = `<img src="${logoC5}" alt="logo categorie">`;
let htmlC6 = `<img src="${logoC6}" alt="logo categorie">`;
let htmlC7 = `<img src="${logoC7}" alt="logo categorie">`;


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
