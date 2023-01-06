function changeBrightness(value) {
    document.getElementById("img-top").style.filter = "brightness("+value+"%) contrast("+document.getElementById("contrast_input").value+"%)"
    document.getElementById("img-bot").style.filter = "brightness("+value+"%) contrast("+document.getElementById("contrast_input").value+"%)"
    document.getElementById("brightness_value").innerHTML = value + "%"
}

function changeContrast(value) {
    document.getElementById("img-top").style.filter = "contrast("+value+"%) brightness("+document.getElementById("brightness_input").value+"%)"
    document.getElementById("img-bot").style.filter = "contrast("+value+"%) brightness("+document.getElementById("brightness_input").value+"%)"
    document.getElementById("contrast_value").innerHTML = value + "%"
}