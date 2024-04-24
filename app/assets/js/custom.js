require('modal-video');
const ModalVideo = require("modal-video/lib/core");

new ModalVideo('.js-modal-btn');

const Seatchart = require('seatchart/dist/seatchart.min');


var seatContainer = document.getElementById("seats-container");
if(seatContainer) {
    console.log('este');
    var options = {
        map: {
            rows: 10,
            columns: 10,
            seatTypes: {
                default: {
                    label: 'Economy',
                    cssClass: 'economy',
                    price: 15,
                },
                first: {
                    label: 'First Class',
                    cssClass: 'first-class',
                    price: 25,
                    seatRows: [0, 1, 2],
                },
                reduced: {
                    label: 'Reduced',
                    cssClass: 'reduced',
                    price: 10,
                    seatRows: [7, 8, 9],
                },
            },
            disabledSeats: [
                { row: 0, col: 0 },
                { row: 0, col: 9 },
            ],
            reservedSeats: [
                { row: 0, col: 3 },
                { row: 0, col: 4 },
            ],
            selectedSeats: [{ row: 0, col: 5 }, { row: 0, col: 6 }],
            rowSpacers: [3, 7],
            columnSpacers: [5],
        },
    };

    var sc = new Seatchart(seatContainer, options);
    sc.addEventListener('submit', function handleSubmit(e) {
        alert('Total: ' + e.total + '€');
    });
}

