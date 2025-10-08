<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Jenkins Dashboard</title>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <style>
    body {
        background-color: #ffffff;
        color: #000000;
        font-family: monospace;
        padding: 20px;
    }
    .log-box {
        background: #f4f4f4;
        color: #000;
        padding: 15px;
        height: 400px;
        overflow-y: scroll;
        border: 1px solid #ddd;
        white-space: pre-wrap;
    }
    button {
        padding: 8px 16px;
        background: #000;
        color: #fff;
        border: none;
        cursor: pointer;
        border-radius: 4px;
    }
    button:hover { background: #333; }
  </style>
</head>
<body>

  <h2>🧩 Jenkins Build Dashboard</h2>

  <button onclick="runJob()">Run Job</button>
  <p id="status">Status: <b>Idle</b></p>
  <div class="log-box" id="logBox">Waiting for log...</div>

  <script>
    const job = "MLTrainDB";
    const logBox = document.getElementById("logBox");
    const statusEl = document.getElementById("status");

    async function runJob() {
        logBox.textContent = "Triggering job...";
        await axios.get(`/api/jenkins/run?job=${job}`);
        pollStatus();
    }

    async function pollStatus() {
        const interval = setInterval(async () => {
            const status = await axios.get(`/api/jenkins/status/${job}`);
            const data = status.data;

            statusEl.innerHTML = `Status: <b>${data.result ?? 'RUNNING'}</b>`;

            const logRes = await axios.get(`/api/jenkins/log/${job}`);
            logBox.textContent = logRes.data;

            if (data.result && data.result !== "null") {
                clearInterval(interval);
            }
        }, 3000);
    }
  </script>

</body>
</html>
