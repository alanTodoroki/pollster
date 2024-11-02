<html>

<head>
    <title>
        Active Polls
    </title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            margin: 20px auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 24px;
            font-weight: bold;
        }

        .create-poll {
            background-color: #000;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .polls {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .poll {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 30%;
            padding: 20px;
            box-sizing: border-box;
        }

        .poll-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .poll-header img {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }

        .poll-header .info {
            font-size: 14px;
        }

        .poll-header .info .name {
            font-weight: bold;
        }

        .poll-header .info .time {
            color: #888;
        }

        .poll-body {
            margin-top: 10px;
        }

        .poll-body h2 {
            font-size: 18px;
            margin: 0 0 10px 0;
        }

        .poll-body p {
            color: #888;
            margin: 0 0 20px 0;
        }

        .poll-options {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .poll-options li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f5f5f5;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>
                Active Polls
            </h1>
            <button class="create-poll">
                Create Poll
            </button>
        </div>
    </div>
    <div class="poll" data-poll-id="<?php echo $poll_id; ?>">
        <div class="poll-header">
            <img alt="User profile picture" height="40" src="<?php echo $user_image; ?>" width="40" />
            <div class="info">
                <div class="name">
                    <?php echo $user_name; ?>
                </div>
                <div class="time">
                    <?php echo $time_ago; ?>
                </div>
            </div>
        </div>
        <div class="poll-body">
            <h2><?php echo $poll_title; ?></h2>
            <p><?php echo $poll_description; ?></p>
            <ul class="poll-options">
                <?php foreach ($options as $option): ?>
                    <li class="poll-option" data-option-id="<?php echo $option['id']; ?>">
                        <div class="progress-bar" style="width: <?php echo $option['percentage']; ?>%"></div>
                        <span class="option-text"><?php echo $option['text']; ?></span>
                        <span class="option-percentage"><?php echo $option['percentage']; ?>%</span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="total-votes">Total votos: <span class="vote-count"><?php echo $total_votes; ?></span></div>
        </div>
    </div>
</body>

</html>