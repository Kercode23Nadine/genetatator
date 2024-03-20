
let button = document.getElementById("copy");

function copyText(){
    let password = document.getElementById("password");
    password.select();
    document.execCommand("copy");
    button.innerText = "Copied"
}

button.addEventListener("click", copyText)


