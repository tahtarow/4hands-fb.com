window.onload=function(){
    url_to_localisation_api = 'https://visit-time.com/localisation/w';
    var script = document.createElement('script');
    script.src = url_to_localisation_api;
    document.getElementsByTagName('body')[0].appendChild(script);
}