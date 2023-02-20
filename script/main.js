window.onload = function() {
    document.querySelectorAll('.img-top').forEach(element => {
        console.log(element.nextElementSibling.clientHeight)
        if (element.clientHeight <= element.nextElementSibling.clientHeight)
            element.parentNode.parentNode.style.height = element.nextElementSibling.clientHeight + "px"
        if (element.clientWidth <= element.nextElementSibling.clientWidth)
            element.parentNode.parentNode.style.width = element.nextElementSibling.clientWidth + "px"
    })
}

function fadeOut(href) {
    // document.body.style = ''
    document.body.style = "animation: fadeOut ease 0.1s; animation-iteration-count: 1; animation-fill-mode: backwards;"
    document.body.style.opacity = 0
    window.location.replace(href);
}

function usun() {
    if (confirm("⚠️Na pewno chcesz usunąć tę monetę? Tej operacji NIE DA SIĘ COFNĄĆ")) {
        document.getElementById('deleteCheck').checked = true
        document.getElementById('form').submit()
    } else {
        document.getElementById('deleteCheck').checked = false
        return
    }
}