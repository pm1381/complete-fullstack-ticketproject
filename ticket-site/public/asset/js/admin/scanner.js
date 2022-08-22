import('./qr-scanner.min.js').then((module) => {
    const QrScanner = module.default;
    // do something with QrScanner
    let result = "";
    adminAddress = 'http://localhost/project/ghadir/admin/';

    const videoElem = document.getElementById('preview');
    const qrScanner = new QrScanner(
        videoElem,
        result => {
            fetch(adminAddress + 'api/numberCheck/', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({number : result, userSearch: 0})
            })
            .then(response => response.json()).then(jsonData => {
                if (jsonData.error) {
                    Swal.fire({
                        text: jsonData.message,
                        icon: 'error',
                        confirmButtonText: 'باشه'
                    });
                } else {
                    const scannerPage = document.querySelector('.scannerPage');
                    const userResult  = document.getElementById('userResult');
                    const userVerify  = document.querySelector('.userVerification');
                    userResult.innerHTML = "";
                    result = "";
                    let userContainer = 
                    '<div class="col-12 col-md-6 border rounded"' + '>'+
                        '<div class="row">'+
                            '<div class="col-12 text-center">نام</div>'+
                            '<div class="col-12 text-center">' + jsonData.name + '</div>'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-12 col-md-6 border rounded">'+
                        '<div class="row">'+
                            '<div class="col-12 text-center">شماره همراه</div>'+
                            '<div class="col-12 text-center">' + jsonData.mobile + '</div>'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-12 col-md-6 border rounded">'+
                        '<div class="row">'+
                            '<div class="col-12 text-center">تعداد بلیط</div>'+
                            '<div class="col-12 text-center">' + jsonData.ticketCount + '</div>'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-12 col-md-6 border rounded">'+
                        '<div class="row">'+
                            '<div class="col-12 text-center">اجرای شب</div>'+
                            '<div class="col-12 text-center">' + jsonData.theater + '</div>'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-12 border rounded">'+
                        '<div class="row">'+
                            '<div class="col-12 text-center">کد رزرو</div>'+
                            '<div class="col-12 text-center reserveCode">' + jsonData.code + '</div>'+
                        '</div>'+
                    '</div>';
                    userResult.innerHTML = userContainer;
                    userVerify.classList.add('displayFlex');
                }  
            })
            .catch(error => console.log("Error: " + error))
        },
        {returnDetailedScanResult: true}
    );

    const start = document.getElementById('start');
    const stop = document.getElementById('stop');

    start.addEventListener('click', event => {
        qrScanner.start();
    })

    stop.addEventListener('click', event => {
        qrScanner.stop();
    })
    
});
