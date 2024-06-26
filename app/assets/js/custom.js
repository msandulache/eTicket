require('modal-video');
const ModalVideo = require("modal-video/lib/core");

new ModalVideo('.js-modal-btn');

const Seatchart = require('seatchart/dist/seatchart.min');

import $ from 'jquery';

var seatContainer = document.getElementById("seats-container");
if (seatContainer) {
    var options = {
        map: {
            rows: 10,
            columns: 10,
            seatTypes: {
                default: {
                    label: 'Normal',
                    cssClass: 'economy',
                    price: 15,
                },
                first: {
                    label: 'VIP',
                    cssClass: 'first-class',
                    price: 25,
                    seatRows: [0, 1, 2]
                },
                reduced: {
                    label: 'Redus',
                    cssClass: 'reduced',
                    price: 10,
                    seatRows: [7, 8, 9],
                    currencySign: 'Lei'
                },
            },
            disabledSeats: [
                {row: 0, col: 0},
                {row: 0, col: 9},
            ],
            reservedSeats: [
                {row: 0, col: 3},
                {row: 0, col: 4},
            ],
            selectedSeats: [{row: 0, col: 5}, {row: 0, col: 6}],
            rowSpacers: [3, 7],
            columnSpacers: [5],
            cart: {
                submitLabel: 'sdf'
            }
        },
        cart:{
            submitLabel: 'Cumpara',
            currency: 'Lei '
        }
    };

    var sc = new Seatchart(seatContainer, options);
    sc.addEventListener('submit', function handleSubmit() {
        $.ajax({
            url: '/checkout',
            type: 'POST',
            dataType: 'json',
            async: true,
            data: {
                cart: sc.getCart(),
                cartTotal: sc.getCartTotal(),
                movie: $('#movie').text()
            },
            success: function (data, status) {
                window.document.location = '/stripe/823464?booking_ref=' + data;
            },
            error: function (xhr, textStatus, errorThrown) {
                alert('Checkout failed:' + errorThrown);

                alert(xhr.status);
                alert(errorThrown);
            }
        });
    });

}

