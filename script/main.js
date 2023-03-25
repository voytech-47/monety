// document.getElementById('zdjecia').addEventListener("click", countFiles)

window.onload = function() {
    document.querySelectorAll('.img-top').forEach(element => {
        console.log(element.nextElementSibling.clientHeight)
        if (element.clientHeight <= element.nextElementSibling.clientHeight)
            element.parentNode.parentNode.style.height = element.nextElementSibling.clientHeight + "px"
        if (element.clientWidth <= element.nextElementSibling.clientWidth)
            element.parentNode.parentNode.style.width = element.nextElementSibling.clientWidth + "px"
    })
    document.getElementById('panels-row').style.display = 'none';
}

function changeView(id) {
    const view1 = document.getElementById('panels')
    const view2 = document.getElementById('panels-row')
    const parent = document.getElementById(id)
    if (view1.style.display == 'none') {
        view1.style.display = 'flex'
        view2.style.display = 'none'
        parent.setAttribute('id', 'view-list')
    } else {
        view1.style.display = 'none'
        view2.style.display = 'flex'
        parent.setAttribute('id', 'view-grid')
    }
}

function fadeOut(href) {
    // document.body.style = ''
    document.body.style = "animation: fadeOut ease 0.1s; animation-iteration-count: 1; animation-fill-mode: backwards;"
    document.body.style.opacity = 0
    window.location.replace(href);
}

function usun(mode) {
    if (mode) {
        if (confirm("⚠️Na pewno chcesz usunąć tę monetę? Tej operacji NIE DA SIĘ COFNĄĆ")) {
            document.getElementById('deleteCheck').checked = true
            document.getElementById('form').submit()
        } else {
            document.getElementById('deleteCheck').checked = false
            return
        }
    } else {
        if (confirm("⚠️Na pewno chcesz usunąć ten album? Tej operacji NIE DA SIĘ COFNĄĆ")) {
            document.getElementById('deleteCheck').checked = true
            document.getElementById('form').submit()
        } else {
            document.getElementById('deleteCheck').checked = false
            return
        }
    }
}

function copyToClipboard() {
    console.log(document.getElementById('toCopy').innerHTML)
    navigator.clipboard.writeText(document.getElementById('toCopy').innerHTML)
    startAnimation()
}

function startAnimation() {
    const box = document.getElementById("popup")
    box.style.cssText += "animation: popUp linear 1.5s;"
    setTimeout(() => {
        box.style.animation = "";
        box.style.zIndex = 1;
    }, 1501);
}

isFileValid = false
isNameValid = false
function checkValidation(input, mode) {
    if (mode == 'f') {
        if (input.files.length <= 5 && input.files.length > 1) {
            input.style.color = 'black'
            isFileValid = true
        } else {
            input.style.color = 'red'
            isFileValid = false
        }
    } else {
        if (input.checkValidity()) {
            isNameValid = true
        } else {
            isNameValid = false
        }
    }
    if (isNameValid && isFileValid) {
        document.getElementById('submit').disabled = false
    } else {
        document.getElementById('submit').disabled = true
    }
}