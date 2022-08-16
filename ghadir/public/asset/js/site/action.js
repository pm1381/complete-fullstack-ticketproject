$(document).ready(function () {
    siteAddress = 'http://localhost/project/ghadir/';
    // siteAddress = 'https://ravatheater.ir/';

    $(document).on('click', '#payment', function () {
        var showNight   = $('#shows').val();
        var phoneNumber = $('.mobileInput').val();
        var username = $('.nameInput').val();
        var priceNum    = $('.payable').text();
        priceNum = priceNum.replace(/\,/g,'');
        priceNum = Number(priceNum);
        var ticketCount = $('.ticketCount').val();
        fetch(siteAddress + 'api/buyValidation/', {
            method:'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({ticketCount:ticketCount, userName:username, priceNum:priceNum, phoneNumber:phoneNumber, showNight:showNight})
        }).then(response => response.json()).then(jsonData => {
            if (jsonData.error) {
                Swal.fire({
                    text: jsonData.message,
                    icon: 'error',
                    confirmButtonText: 'باشه'
                });
            } else {
                gopay(phoneNumber, priceNum);
            }
        })
    })

    $(document).on('input', '.ticketCount', function () {
        var count =  $('.ticketCount').val();
        if (count <= 0) {
            $('.payable').text('0');
        } else {
            var finalPrice = eachTicket * count;
            finalPrice = finalPrice.toLocaleString('en-US');
            $('.payable').text(finalPrice);
        }
    })

    function gopay(phoneNumber, priceNum) {
        Swal.fire({
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading() }
        });
        fetch(siteAddress + 'api/payment/', {
            method:'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({phoneNumber:phoneNumber, priceNum:priceNum})
        }).then(response => response.json()).then(jsonData => {
            window.location.href = 'https://nextpay.org/nx/gateway/payment/' + jsonData.link;
            Swal.close();
        })
    }

    $(document).on('click', '#phoneCheck', function () {
        var phoneNumber = $('.mobileInput').val();
        $('.qrCode').empty();
        fetch(siteAddress + 'api/qrValidation/', {
            method:'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({phoneNumber:phoneNumber})
        }).then(response => response.json()).then(jsonData => {
            if (jsonData.error) {
                Swal.fire({
                    text: jsonData.message,
                    icon: 'error',
                    confirmButtonText: 'باشه'
                });
            } else {
                var addedData = '';
                    addedData = addedData + '<h6 style="text-align: center; margin-top:10px;margin-bottom:10px;">بارکد ورود</h6>'
                    addedData = addedData + '<img src="' + jsonData.qrCode + '">';
                $('.qrCode').prepend($(addedData));
            }
        })
    })

    $(document).on('click', '#refund', function (e) {
        var userInput = $("input[name=refundInput]").val();
        fetch(siteAddress + 'api/refundCheck/', {
            method:'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({refundInput: userInput})
        }).then(response => response.json()).then(jsonData => {
            if (jsonData.error) {
                Swal.fire({
                    text: jsonData.message,
                    icon: 'error',
                    confirmButtonText: 'باشه'
                });
            } else {
                $('.refundForm1').hide();
                $('.refundForm2').show();
            }
        })
    })

    $(document).on('click', '#credit-card', function (e) {
        const parent = $(this).closest('.cards');

        var userNumber = parent.find('input[name=refundInput]').val();
        var creditCard = $("input[name=creditCard]").val();
        
        fetch(siteAddress + 'api/addCreditCard/', {
            method:'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({creditCard: creditCard, userNumber: userNumber})
        }).then(response => response.json()).then(jsonData => {
            if (jsonData.error) {
                Swal.fire({
                    text: jsonData.message,
                    icon: 'error',
                    confirmButtonText: 'باشه'
                });
            } else {
                Swal.fire({
                    text: jsonData.message,
                    icon: 'success',
                    confirmButtonText: 'باشه'
                }).then(function (result) {
                    if (result.isConfirmed) {
                        window.location.href = siteAddress;
                        $('.refundForm2').hide();
                        $('.refundForm1').show();
                    }
                })
            }
        })
    })

});