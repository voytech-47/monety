function moveCoin(value) {
    if (confirm("⚠️Na pewno chcesz przenieś tę monetę do albumu "+value+"?")) {
        document.getElementById('form').submit()
    } else {
        return
    }
}

function changeBrightness(value) {
    if (document.getElementById("img-magnifier-glass-top") != null) {
        document.getElementById("img-magnifier-glass-top").style.filter = "brightness("+document.getElementById("brightness_input").value+"%) contrast(" + document.getElementById("contrast_input").value + "%)";
    }
    if (document.getElementById("img-magnifier-glass-bot") != null) {
        document.getElementById("img-magnifier-glass-bot").style.filter = "brightness("+document.getElementById("brightness_input").value+"%) contrast(" + document.getElementById("contrast_input").value + "%)";
    }
    document.getElementById("img-top").style.filter = "brightness(" + value + "%) contrast(" + document.getElementById("contrast_input").value + "%)"
    document.getElementById("img-bot").style.filter = "brightness(" + value + "%) contrast(" + document.getElementById("contrast_input").value + "%)"
    document.getElementById("brightness_value").innerHTML = value + "%"
}

function changeContrast(value) {
    if (document.getElementById("img-magnifier-glass-top") != null) {
        document.getElementById("img-magnifier-glass-top").style.filter = "brightness("+document.getElementById("brightness_input").value+"%) contrast(" + document.getElementById("contrast_input").value + "%)";
    }
    if (document.getElementById("img-magnifier-glass-bot") != null) {
        document.getElementById("img-magnifier-glass-bot").style.filter = "brightness("+document.getElementById("brightness_input").value+"%) contrast(" + document.getElementById("contrast_input").value + "%)";
    }
    document.getElementById("img-top").style.filter = "contrast(" + value + "%) brightness(" + document.getElementById("brightness_input").value + "%)"
    document.getElementById("img-bot").style.filter = "contrast(" + value + "%) brightness(" + document.getElementById("brightness_input").value + "%)"
    document.getElementById("contrast_value").innerHTML = value + "%"
}

function changeMagnifyTop(value) {
    document.getElementById('magnify-value-top').innerHTML = value+"%"
    var img = document.getElementById('img-top')
    document.getElementById('img-magnifier-glass-top').style.backgroundSize = (img.width * value) + "px " + (img.height * value) + "px";
}

function changeMagnifyBot(value) {
    document.getElementById('magnify-value-bot').innerHTML = value+"%"
    var img = document.getElementById('img-bot')
    document.getElementById('img-magnifier-glass-bot').style.backgroundSize = (img.width * value) + "px " + (img.height * value) + "px";
}

function magnify(imgPOS, imgID) {
    var img, glass, w, h, bw, zoom;
    if (imgPOS == 'img-top') {
        zoom = document.getElementById('strength-top').value
    } else {
        zoom = document.getElementById('strength-bot').value
    }
    img = document.getElementById(imgID);
    /*create magnifier glass:*/
    glass = document.createElement("DIV");
    glassID = "img-magnifier-glass-"+imgPOS;
    glass.setAttribute("id", glassID);
    /*insert magnifier glass:*/
    img.parentElement.insertBefore(glass, img);
    /*set background properties for the magnifier glass:*/
    glass.style.backgroundImage = "url('" + img.src + "')";
    glass.style.filter = "brightness("+document.getElementById("brightness_input").value+"%) contrast(" + document.getElementById("contrast_input").value + "%)";
    glass.style.backgroundRepeat = "no-repeat";
    glass.style.backgroundSize = (img.width * zoom) + "px " + (img.height * zoom) + "px";
    glass.style.zIndex = 999
    bw = 3;
    w = glass.offsetWidth / 2;
    h = glass.offsetHeight / 2;
    /*execute a function when someone moves the magnifier glass over the image:*/
    glass.addEventListener("mousemove", moveMagnifier);
    img.addEventListener("mousemove", moveMagnifier);
    /*and also for touch screens:*/
    glass.addEventListener("touchmove", moveMagnifier);
    img.addEventListener("touchmove", moveMagnifier);
    glass.imgPOS = imgPOS
    img.imgPOS = imgPOS
    function moveMagnifier(e) {
        var pos, x, y, zoom;
        console.log(e.currentTarget.imgPOS)
        if (e.currentTarget.imgPOS == 'top') {
            zoom = document.getElementById('strength-top').value
        } else {
            zoom = document.getElementById('strength-bot').value
        }
        /*prevent any other actions that may occur when moving over the image*/
        glass.style.backgroundSize = (img.width * zoom) + "px " + (img.height * zoom) + "px";
        e.preventDefault();
        /*get the cursor's x and y positions:*/
        pos = getCursorPos(e);
        x = pos.x;
        y = pos.y;
        /*prevent the magnifier glass from being positioned outside the image:*/
        if (x > img.width - (w / zoom)) { x = img.width - (w / zoom); }
        if (x < w / zoom) { x = w / zoom; }
        if (y > img.height - (h / zoom)) { y = img.height - (h / zoom); }
        if (y < h / zoom) { y = h / zoom; }
        /*set the position of the magnifier glass:*/
        glass.style.left = (x - w) + "px";
        glass.style.top = (y - h) + "px";
        /*display what the magnifier glass "sees":*/
        glass.style.backgroundPosition = "-" + ((x * zoom) - w + bw) + "px -" + ((y * zoom) - h + bw) + "px";
    }
    function getCursorPos(e) {
        var a, x = 300, y = 0;
        e = e || window.event;
        /*get the x and y positions of the image:*/
        a = img.getBoundingClientRect();
        /*calculate the cursor's x and y coordinates, relative to the image:*/
        x = e.pageX - a.left;
        y = e.pageY - a.top;
        /*consider any page scrolling:*/
        x = x - window.pageXOffset;
        y = y - window.pageYOffset;
        return { x: x, y: y };
    }
}