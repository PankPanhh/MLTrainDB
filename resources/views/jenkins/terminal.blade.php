<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Jenkins Job Terminal: {{ $jobName }}</title>
    <style>
        body { 
            font-family: monospace; 
            background-color: #ffffff; /* nền trắng */
            color: #000000; /* chữ đen */
            padding: 20px; 
        }
        h2 { color: #007bff; }
        #log { 
            white-space: pre-wrap; 
            max-height: 70vh; 
            overflow-y: auto; 
            border: 1px solid #007bff; 
            padding: 10px; 
            background-color: #f8f9fa; 
        }
        button, a { 
            padding: 8px 15px; 
            font-size: 16px; 
            margin-bottom: 10px; 
            text-decoration: none; 
            border-radius: 5px; 
        }
        button { 
            background-color: #007bff; 
            color: #ffffff; 
            border: none; 
            cursor: pointer;
        }
        button:hover { background-color: #0056b3; }
        a { 
            background-color: #6c757d; 
            color: #ffffff; 
        }
        a:hover { background-color: #5a6268; }
    </style>
</head>
<body>
    <h2>Jenkins Job: {{ $jobName }}</h2>
    <a href="{{ route('home') }}">← Back to Index</a>
    <button onclick="startPolling()">Start Live Log</button>
    <div id="log">{{ $log }}</div>

    <script>
        let logDiv = document.getElementById('log');
        let lastLength = logDiv.textContent.length;
        let interval;

        function startPolling() {
            if(interval) return;
            interval = setInterval(fetchLog, 2000);
        }

        function fetchLog() {
            fetch(window.location.href)
                .then(res => res.text())
                .then(html => {
                    let parser = new DOMParser();
                    let doc = parser.parseFromString(html, 'text/html');
                    let newLog = doc.getElementById('log').textContent;

                    if(newLog.length !== lastLength) {
                        logDiv.textContent = newLog;
                        logDiv.scrollTop = logDiv.scrollHeight;
                        lastLength = newLog.length;
                    }
                })
                .catch(err => console.error(err));
        }
    </script>
</body>
</html>
