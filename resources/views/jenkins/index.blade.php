<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Jenkins Dashboard</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background-color: #ffffff; /* nền trắng */
            color: #000000; /* chữ đen */
            padding: 30px; 
        }
        h1 { 
            color: #007bff; /* xanh dương nổi bật */
            margin-bottom: 20px; 
        }
        .job-list { list-style: none; padding: 0; }
        .job-list li { margin-bottom: 15px; }
        a { 
            text-decoration: none; 
            color: #ffffff; 
            background-color: #007bff; 
            padding: 10px 20px; 
            border-radius: 5px; 
            font-weight: bold;
            transition: background 0.2s;
        }
        a:hover { 
            background-color: #0056b3; 
        }
        footer {
            margin-top: 40px;
            color: #555555;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <h1>Jenkins Jobs Dashboard</h1>
    <ul class="job-list">
        @foreach($jobs as $job)
            <li>
                <a href="{{ route('jenkins.triggerTerminal', ['job' => $job]) }}">{{ $job }}</a>
            </li>
        @endforeach
    </ul>
</body>
</html>
