function addProduct(){

    let form = document.getElementById("addForm");
    let data = new FormData(form);
    data.append("action", "add");

    let xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            document.getElementById("msg").innerHTML = this.responseText;
            location.reload();
        }
    };

    xhttp.open("POST", "../Controller/adminValidation.php", true);
    xhttp.send(data);
}

function toggleStock(id){

    let xhttp = new XMLHttpRequest();

    xhttp.open("POST", "../Controller/adminValidation.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("toggle=1&id="+id);

    location.reload();
}

function updateProduct(){

    let form = document.getElementById("editForm");
    let data = new FormData(form);
    data.append("action", "update");

    let xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            alert(this.responseText);
            location.reload();
        }
    };

    xhttp.open("POST", "../Controller/adminValidation.php", true);
    xhttp.send(data);
}