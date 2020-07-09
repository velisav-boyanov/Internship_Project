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
<h1>Enter user parameters.</h1>
<form action="index.php?target=player&action=add" method="post">
    <div class="form-group">
        <label>Health(Max 4):</label>
        <input type="text" name = "Health" class="form-control" required>
    <div class="form-group">
        <label>Starting X:</label>
        <input type="text" name = "X" class="form-control" required>
    <div class="form-group">
    <button type="submit" class="btn btn-primary"> Start </button>
</form>
</body>
</html>