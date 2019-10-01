<?php

$cookietime = time() + 2592000;

if (isset($_POST['login'])) {
    $key = $user->authByPass($_POST['login'], $_POST['password'], $navision, $formErrors);
    if ($key != 0) {
        $terminalId = (int)$_POST['terminal'];
        $_SESSION['login'] = $_POST['login'];
        $_SESSION['key'] = $key;
        $_SESSION['terminal'] = $terminalId;
        setcookie('login', $_POST['login'], $cookietime, '/');
        setcookie('key', $key, $cookietime, '/');
        setcookie('terminal', $terminalId, $cookietime, '/');
        $db->query("
			DELETE FROM `sessions` WHERE `terminal` = '{$terminalId}';
			INSERT INTO `sessions` (`user`, `terminal`) VALUES ('{$user->id}', '{$terminalId}');
		");
        header('Location: /');
    } else {
        if (isset($_SESSION['terminal'])) {
            $db->query("DELETE FROM `sessions` WHERE `terminal` = '" . $_SESSION['terminal'] . "';");
        }
        session_destroy();
        setcookie('user', '', $cookietime, '/');
        setcookie('key', '', $cookietime, '/');
    }
} else {
    if (isset($_SESSION['terminal'])) {
        $db->query("DELETE FROM `sessions` WHERE `terminal` = '" . $_SESSION['terminal'] . "';");
    }
    unset($_SESSION['login'], $_SESSION['key'], $_SESSION['terminal']);
    session_write_close();
    session_destroy();
    setcookie('user', '', $cookietime, '/');
    setcookie('key', '', $cookietime, '/');
    if (isset($_GET['page']) && $_GET['page'] == 'logout')
        header('Location: /');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>Авто-Контроль: Авторизация</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
        body {
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
        }

        .form-signin {
            max-width: 700px;
            padding: 19px 29px 29px;
            margin: 0 auto 20px;
            background-color: #fff;
            border: 1px solid #e5e5e5;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
            -webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
            -moz-box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
            box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
        }

        .form-signin .form-signin-heading,
        .form-signin .checkbox {
            margin-bottom: 10px;
        }

        .form-signin input[type="text"],
        .form-signin input[type="password"] {
            font-size: 16px;
            height: auto;
            margin-bottom: 15px;
            padding: 7px 9px;
        }

    </style>
    <link href="/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="/js/html5shiv.js"></script>
    <![endif]-->

    <link rel="shortcut icon" href="/img/favicon.png">
</head>

<body>
<div class="container">
    <form class="form-signin" method="POST" action="/" autocomplete="off">
        <div style="float: left; clear: left; width: 350px;">
            <?php
            foreach ($formErrors as $errorString) {
                echo '<span class="label label-important">' . $errorString . '</span><br>';
            }
            ?>
            <h2 class="form-signin-heading">Авто-Контроль: Авторизация</h2>
            <input id="login" autocomplete="off" name="login" type="text" class="input-block-level"
                   placeholder="Логин" required autofocus>
            <input id="password" autocomplete="off" name="password" type="password" class="input-block-level"
                   placeholder="Пароль" required>
            <label for="terminal">Выбор терминала:</label>
            <select name="terminal" id="terminalSelect" onchange="onTerminalChange()">
                <?php
                $saveTerminal = (int)($_COOKIE['terminal'] ?? 0);
                $terminalRows = $db->assoc('SELECT * FROM `terminals`');
                usort($terminalRows, static function ($a, $b) {
                    return $a['name'] <=> $b['name'];
                });
                foreach ($terminalRows as $terminalRow) {
                    $terminalRowId = (int)$terminalRow['id'];
                    echo '<option value="' . $terminalRowId . '"';
                    if ($terminalRowId === $saveTerminal) {
                        echo ' selected';
                    }
                    echo '>' . $terminalRow['name'] . '</option>';
                }
                ?>
            </select>
            <button class="btn btn-large btn-primary" type="submit">Войти</button>
        </div>
        <div style="float: right; clear: right; width: 350px; text-align: center;">
            <img src="" id="terminalImg" style="max-height: 400px;">
        </div>
        <div style="clear: both;">
            &nbsp;
        </div>
    </form>
</div> <!-- /container -->
<script>
    function onTerminalChange() {
        let id = document.getElementById('terminalSelect').value;
        let img = document.getElementById('terminalImg');
        img.src = `/img/terminal/terminal-${id}.png`;
    }

    onTerminalChange();
</script>

</body>
</html>