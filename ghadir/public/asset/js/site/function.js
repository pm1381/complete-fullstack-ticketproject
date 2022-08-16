siteAddress = 'http://localhost/project/ghadir/';
// siteAddress = 'https://ravatheater.ir/';
eachTicket = 20000

function showTheaterDetails(theaterId) {

    fetch(siteAddress + 'api/showTheater/', {
        method:'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: new URLSearchParams({theaterId:theaterId})
    }).then(response => response.json()).then(jsonData => {
        $('.theaterDescription').css('display', 'flex');
        $('.theaterTitle').text("نام سالن : " + jsonData.theaterName);
        $('.desc').text("نشانی : " + jsonData.theaterAddress);
    })
}

function ticketCountChange(count) {
    if (count <= 0) {
        $('.payable').text('0');
    } else {
        var finalPrice = eachTicket * count;
        finalPrice = finalPrice.toLocaleString('en-US');
        $('.payable').text(finalPrice);
    }
}