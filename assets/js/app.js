
import '../css/app.scss';
import '@fortawesome/fontawesome-free/js/all.min';
const $ = require('jquery');

require('bootstrap');
console.log('Hello Webpack Encore! Edit me in assets/js/app.js');

let jumbo = $(".jumbotron");

jumbo.hide().fadeIn('slow');

