<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Active Polls</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../public/css/votes.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Active Polls</h1>
            <button class="create-poll">Create Poll</button>
        </div>
        <div class="polls">
            <div class="poll">
                <div class="poll-header">
                    <img alt="User profile picture" src="https://storage.googleapis.com/a1aa/image/b2Vv6krDftUKL6H1O90cRD6TFT2DEkLAXXmfobXXGBoZ21rTA.jpg" width="40" height="40" />
                    <div class="info">
                        <div class="name">Acme Inc</div>
                        <div class="time">2 days ago</div>
                    </div>
                </div>
                <div class="poll-body">
                    <h2>What's your favorite programming language?</h2>
                    <p>Help us understand your preferences.</p>
                    <ul class="poll-options">
                        <li><span>JavaScript</span><span>42%</span></li>
                        <li><span>Python</span><span>28%</span></li>
                        <li><span>Java</span><span>18%</span></li>
                        <li><span>C#</span><span>12%</span></li>
                    </ul>
                </div>
            </div>
            <!-- Repite la estructura de la encuesta para las demás encuestas -->
        </div>
    </div>
</body>

</html>