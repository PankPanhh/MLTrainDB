<!DOCTYPE html>
<html>
<head>
    <title>Jenkins Job Log</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>
<h1>Job: {{ $job->job_name }}</h1>
<p>Status: {{ $job->status }}</p>
<p>Triggered at: {{ $job->triggered_at }}</p>

<pre id="log">{{ $job->log }}</pre>

<script>
let start = {{ strlen($job->log) }};
function fetchLog() {
    axios.get("{{ route('jenkins.log.realtime') }}", { params: { job: "{{ $job->job_name }}", start } })
        .then(res => {
            document.getElementById('log').innerText += res.data.text;
            start = res.data.size;
            if(res.data.more) setTimeout(fetchLog, 2000);
        })
        .catch(err => console.error(err));
}
fetchLog();
</script>
</body>
</html>
