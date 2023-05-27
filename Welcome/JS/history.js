let historyDataStorageUser = {
    Value: null
} 
let CACHE_KEY = "HISTORY_E-PERPUS_MEJAYAN";
function checkForStorage(){
    return typeof(Storage) !== "undefined"
}
function date() {
    const date = new Date();
    const year = date.getFullYear();
    const month = date.getMonth() + 1;
    const day = date.getDate();
    const hours = date.getHours();
    const minutes = date.getMinutes();

    let hari_ini = new Date(date);
    hari_ini.setDate(date.getDate());

    let spcl = {
        normal: hours + ":" + minutes + "<br>" + day + "/" + month + "/" + year,
        khusus: hours + ":" + minutes + "/" + day + "/" + month + "/" + year,
        feedback: hari_ini.getDate()
    }
    return spcl;
  }

// push ke local storage
function putHistory(data,i) {
    if (checkForStorage) {
        let historyData = null;
        if (localStorage.getItem(CACHE_KEY) === null) {
            historyData = [
                [],
                [{value: "Data<br> Kosong"}],
                [{name: "reading", value: "Belum membaca buku"}]
            ];
        localStorage.setItem(CACHE_KEY, JSON.stringify(historyData[i]));
        } else {
            historyData = JSON.parse(localStorage.getItem(CACHE_KEY));
        }

        historyData[i].unshift(data);

        if (historyData[i].length > 25) {
            historyData[i].pop();
        }

        localStorage.setItem(CACHE_KEY, JSON.stringify(historyData));
    }
}
// add masuk
function putData() {
    historyDataStorageUser = {
        value: date().normal
    }
    if(!localStorage.getItem(CACHE_KEY + "(visited)")){
        putHistory(historyDataStorageUser,0);
        localStorage.setItem(CACHE_KEY + "(visited)", true)
    }
}
putData();

// add keluar
window.addEventListener('beforeunload', function(event) {
    historyDataStorageUser = {
        value: date().normal
    }
    if(localStorage.getItem(CACHE_KEY + "(visited)")){
    putHistory(historyDataStorageUser,1);
    }
    localStorage.removeItem(CACHE_KEY + "(visited)");
  });


