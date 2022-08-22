siteAddress = 'http://localhost/project/ghadir/';
// siteAddress = 'https://ravatheater.ir/';

function clock() {
    setInterval(() => {
        let date = new Date;
        
        var seconds = date.getSeconds();
        var minutes = date.getMinutes();
        var hours = date.getHours();

        if(seconds < 10) seconds = '0'+seconds;
        if(minutes < 10) minutes = '0'+minutes;
        if(hours < 10) hours = '0'+hours;

        $('.timeHeader').html(hours + ':' + minutes + ':' + seconds);

    }, 1000);
}

function userSortChange(value)
{
    const pageUrl = window.location.href;
    var url = new URL(pageUrl);
    const params = new URLSearchParams(window.location.search)
    if (params.has('sortStyle')) {
        params.set('sortStyle', value);
    } else {
        params.append('sortStyle', value);
    }
    url.search = params.toString();
    window.location.href = url.href;
}