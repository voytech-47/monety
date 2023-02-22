<html lang="pl">
<meta charset='utf-8'>

<body>

    <p id="test"></p>
    <form id='form' action="" method="post">
        <select name="move" id="move" onchange=function move(this.value) {document.getElementById('form').submit(); document.getElementById('test').innerHTML = value}>
            <option value="chuj">chuj</option>
            <option value="asd">asd</option>
        </select>
    </form>
    <script>
        function move(value) {
            document.getElementById('form').submit()
            document.getElementById('test').innerHTML = value
        }
    </script>
</body>