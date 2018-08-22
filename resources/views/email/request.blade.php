<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>

<div>
    Hi {{ $name }},
    <br>
    You have a request to be a student advisor {{ $nameStudent }} for the area {{ $nameArea }}!
    <br>
    Please click on the link below to accept:
    <br>

    <a href="{{ url('teacher-accept-student/YES/'.$idNotificable) }}">Confirm my email address </a>

    <br>
    
    Or click on the link below to recuse:

    <br>

    <a href="{{ url('teacher-accept-student/NO/'.$idNotificable) }}">Confirm my email address </a>
    
    <br/>
</div>

</body>
</html>
