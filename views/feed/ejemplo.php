<?php
// feedUser.php

// Iniciar la sesión y conectar con la base de datos
session_start();
require_once '../../config/database.php';

// Verificar si los datos necesarios están presentes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['poll_id']) && isset($_POST['option_id'])) {
    if (!isset($_SESSION['id_usuario'])) {
        die(json_encode(['success' => false]));
    }

    try {
        // Comenzar la transacción
        $conn->begin_transaction();

        // Verificar si el usuario ya votó en esta encuesta
        $stmt = $conn->prepare("SELECT id FROM votos WHERE id_usuario = ? AND id_encuesta = ?");
        $stmt->bind_param("ii", $_SESSION['id_usuario'], $_POST['poll_id']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            throw new Exception("Ya has votado en esta encuesta");
        }

        // Registrar el voto
        $stmt = $conn->prepare("INSERT INTO votos (id_usuario, id_encuesta, id_opcion) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $_SESSION['id_usuario'], $_POST['poll_id'], $_POST['option_id']);
        $stmt->execute();

        // Obtener los nuevos totales
        $stmt = $conn->prepare("
            SELECT 
                o.id,
                COUNT(v.id) as votos,
                (SELECT COUNT(*) FROM votos WHERE id_encuesta = ?) as total
            FROM opciones o
            LEFT JOIN votos v ON o.id = v.id_opcion
            WHERE o.id_encuesta = ?
            GROUP BY o.id
        ");
        $stmt->bind_param("ii", $_POST['poll_id'], $_POST['poll_id']);
        $stmt->execute();
        $result = $stmt->get_result();

        $percentages = [];
        $total_votes = 0;
        while ($row = $result->fetch_assoc()) {
            $total_votes = $row['total'];
            $percentages[$row['id']] = $total_votes > 0 ? round(($row['votos'] / $total_votes) * 100, 1) : 0;
        }

        $conn->commit();

        echo json_encode([
            'success' => true,
            'percentages' => $percentages,
            'total_votes' => $total_votes
        ]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feed de Encuestas</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* CSS */
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
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f5f5f5;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            cursor: pointer;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .progress-bar {
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            background-color: rgba(0, 123, 255, 0.2);
            transition: width 0.3s ease;
            z-index: 1;
        }

        .option-text,
        .option-percentage {
            position: relative;
            z-index: 2;
        }

        .poll-options li.selected {
            background-color: rgba(0, 123, 255, 0.1);
            border: 1px solid rgba(0, 123, 255, 0.3);
        }

        .poll-options li:hover {
            background-color: #eaeaea;
        }

        .poll-options.voted li:hover {
            background-color: #f5f5f5;
            cursor: default;
        }

        .total-votes {
            margin-top: 15px;
            font-size: 14px;
            color: #888;
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="poll" data-poll-id="<?php echo $poll_id; ?>">
            <div class="poll-header">
                <img alt="User profile picture" height="40" src="<?php echo $user_image; ?>" width="40" />
                <div class="info">
                    <div class="name"><?php echo $user_name; ?></div>
                    <div class="time"><?php echo $time_ago; ?></div>
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
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.poll-option').click(function() {
                if ($(this).hasClass('voted')) {
                    return; // Evitar votos múltiples
                }

                const $poll = $(this).closest('.poll');
                const pollId = $poll.data('poll-id');
                const optionId = $(this).data('option-id');
                const $allOptions = $poll.find('.poll-option');
                const $totalVotes = $poll.find('.vote-count');

                $.ajax({
                    url: 'feedUser.php',
                    method: 'POST',
                    data: {
                        poll_id: pollId,
                        option_id: optionId
                    },
                    success: function(response) {
                        if (response.success) {
                            $allOptions.each(function() {
                                const optId = $(this).data('option-id');
                                const newPercentage = response.percentages[optId];

                                $(this).find('.progress-bar').css('width', newPercentage + '%');
                                $(this).find('.option-percentage').text(newPercentage + '%');
                            });

                            $totalVotes.text(response.total_votes);
                            $allOptions.removeClass('selected');
                            $(this).addClass('selected voted');
                        }
                    },
                    error: function() {
                        alert('Error al registrar el voto');
                    }
                });
            });
        });
    </script>
</body>

</html>