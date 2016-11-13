<!DOCTYPE html>
<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
    <div class="container col-md-3">
        <form action="login.php" method="POST">
          <div class="form-group">
            <label>Login id:</label>
            <input type="text" name="login"class="form-control">
          </div>
          <div class="form-group">
            <label for="pwd">Password:</label>
            <input type="password" name="password" class="form-control" id="pwd">
          </div>
          <button type="submit" class="btn btn-default">Login</button>
        </form>
        <button type="reg" class="btn btn-primary" onclick="location.href = './signup.html';">Register</button>
</div>
