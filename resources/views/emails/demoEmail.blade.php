<!DOCTYPE html>
<html>
<head>
    <title>Course start date reminder</title>
</head>
<body>

<h2>Dear {{ $userName }} </h2>
<p>Don't forget the course you enrolled ({{ $course->name }}) starts in this date: {{ $course->init_date }}. Happy learning!</p>

</body>
</html>
