<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<h2> Enter field parameters.</h2>
<form action="index.php?target=field&action=add" method="post">
                <div class="form-group">
                    <label>Width:</label>
                    <input type="text" name = "Width" class="form-control" required>
                <div class="form-group">
                    <label>Length:</label>
                    <input type="text" name = "Length" class="form-control" required>
                <div class="form-group">
                    <label>End X:</label>
                    <input type="text" name = "End_X" class="form-control" required>
                <div class="form-group">
                    <label>End Y:</label>
                    <input type="text" name = "End_Y" class="form-control" required>
                <div class="form-group">
                    <label>Bombs:</label>
                    <input type="text" name = "Bomb_Intensity" class="form-control" required>
                    <button type="submit" class="btn btn-primary"> Start </button>
            </form>

</body>
</html>