
function countdown(id, time){

    t = time;
    m = Math.floor(t / 60) % 60;
    s = t % 60;
    m = (m < 10) ? "0"+m : m;
    s = (s < 10) ? "0"+s : s;
    strZeit = m + ":" + s;

    if(time > 0){
        window.setTimeout('countdown(\''+id+'\','+ --time+')',1000);
    }
    else {
        location.reload();
    }
    document.getElementById(id).innerHTML = strZeit;
}

