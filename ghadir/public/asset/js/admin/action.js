$(document).ready(function () {
    siteAddress  = 'http://localhost/project/ghadir/';
    adminAddress = 'http://localhost/project/ghadir/admin/';
    // siteAddress = 'https://ravatheater.ir/';

    $(document).on('click', '.searchButton', function () {
        var data = $('.searchInput').val();
        fetch(siteAddress + 'admin/api/numberCheck/', {
            method:'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({number:data})
        }).then(response => response.json()).then(jsonData => {
            if (jsonData.error) {
                Swal.fire({
                    text: jsonData.message,
                    icon: 'error',
                    confirmButtonText: 'باشه'
                });
            } else {
                $('.showUser').empty();
                var showUserInside = 
                '<div class="col-12 col-sm-6 border rounded"' + '>'+
                    '<div class="row">'+
                        '<div class="col-12 text-center">نام</div>'+
                        '<div class="col-12 text-center">' + jsonData.name + '</div>'+
                    '</div>'+
                '</div>'+
                '<div class="col-12 col-sm-6 border rounded">'+
                    '<div class="row">'+
                        '<div class="col-12 text-center">شماره همراه</div>'+
                        '<div class="col-12 text-center">' + jsonData.mobile + '</div>'+
                    '</div>'+
                '</div>'+
                '<div class="col-12 col-sm-6 border rounded">'+
                    '<div class="row">'+
                        '<div class="col-12 text-center">تعداد بلیط</div>'+
                        '<div class="col-12 text-center">' + jsonData.ticketCount + '</div>'+
                    '</div>'+
                '</div>'+
                '<div class="col-12 col-sm-6 border rounded">'+
                    '<div class="row">'+
                        '<div class="col-12 text-center">اجرای شب</div>'+
                        '<div class="col-12 text-center">' + jsonData.theater + '</div>'+
                    '</div>'+
                '</div>'+
                '<div class="col-12 border rounded">'+
                    '<div class="row">'+
                        '<div class="col-12 text-center">کد رزرو</div>'+
                        '<div class="col-12 text-center">' + jsonData.code + '</div>'+
                    '</div>'+
                '</div>'
                ;
                $('.showUser').prepend($(showUserInside));
                $('.userVerify').css('display', 'flex');
            }
        })
    })

    $(document).on('click', '.giveReserveCode', event => {
        var id = event.target.getAttribute("user-id")
        formData = new FormData();
        formData.append("id", id);
        $.ajax({
            type: "POST",
            url: adminAddress + 'api/giveReserveCode/',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.error) {
                    Swal.fire({
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'باشه'
                    });
                } else {
                    Swal.fire({
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'باشه'
                    }).then(result => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    })
                }
            }
        });
    })

    $(document).on('click', '.refundDone', event => {
        var cardId = event.target.getAttribute('user-id');
        var target = event.target;
        
        fetch(adminAddress + 'api/refundManage/', {
            method:'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({cardId:cardId})
        }).then(response => response.json()).then(jsonData => {
            Swal.fire({
                text: 'به روز رسانی شد',
                icon: 'success',
                confirmButtonText: 'باشه'
            });
            target.style.backgroundColor = '#198754';
            target.innerHTML  = 'انجام شده'
        })
    })

    $(document).on('click', '.searchUser', event => {
       var number = $('.searchInput').val();
        let formData = new FormData();
        formData.append("number", number);
        formData.append("userSearch", 1);
        $.ajax({
            type: "POST",
            url: adminAddress + 'api/numberCheck/',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.error) {
                    Swal.fire({
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'باشه'
                    });
                } else {
                    if (response.message == 'need reload') {
                        window.location.reload();
                    }
                    $('.record').hide();
                    var foundRecord = '.record-' + response.userId;
                    console.log(foundRecord);
                    $(foundRecord).show();
                }
            }
        });
    })

    $(document).on('click', '.userVerify', function () {
        var data = $('.searchInput').val();
        fetch(siteAddress + 'admin/api/userVerifyByNum/', {
            method:'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({number:data})
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
                    confirmButtonText: 'تایید'
                });
            }
        })
    })

    $(document).on('click', '.userVerification', function () { 
        var reserveCode = $('.reserveCode').html();
        if (reserveCode != null && reserveCode != undefined) {
            fetch(siteAddress + 'admin/api/userVerifyByNum/', {
                method:'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: new URLSearchParams({number:reserveCode})
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
                        confirmButtonText: 'تایید'
                    });
                }
            })  
        }
    }) 
    
    clock();
});