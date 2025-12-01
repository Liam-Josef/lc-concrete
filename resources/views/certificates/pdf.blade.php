<?php
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificate</title>
    <style>
body { font-family: DejaVu Sans, sans-serif; text-align:center; margin:60px;}
        h2,h3,h4{ margin: 0.4em 0; }
        .muted{ color:#666; }
    </style>
</head>
<body>
    <h2>Certificate of Completion</h2>
    <p>This certifies that</p>
    <h3>{{ $student->first_name }} {{ $student->last_name }}</h3>
<p>has successfully completed</p>
<h4>{{ $lesson->title }}</h4>
<p class="muted">Issued: {{ ($issuedAt ?? now())->format('F j, Y') }}</p>
</body>
</html>
