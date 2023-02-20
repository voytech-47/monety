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