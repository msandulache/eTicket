require('modal-video');
const ModalVideo = require("modal-video/lib/core");

new ModalVideo('.js-modal-btn');

const Seatchart = require('seatchart/dist/seatchart.min');


var element = document.getElementById('container');
var options = {
    map: {
        rows: 7,
        columns: 7,
        seatTypes: {
            default: {
                label: 'Economy',
                cssClass: 'economy',
                price: 10,
            },
        },
    },
};

var sc = new Seatchart(element, options);

