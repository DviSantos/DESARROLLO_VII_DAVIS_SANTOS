<?php
session_start();
include 'config.php';
include 'db_connection.php'; 
//
if (isset($_GET['code'])) {
    $token_url = 'https://oauth2.googleapis.com/token';
    $token_data = [
        'code' => $_GET['code'],
        'client_id' => CLIENT_ID,
        'client_secret' => CLIENT_SECRET,
        'redirect_uri' => REDIRECT_URI,
        'grant_type' => 'authorization_code'
    ];

    // Solicita el token de acceso
    $ch = curl_init($token_url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($token_data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $token_info = json_decode($response, true);

    // Obtiene la información del usuario desde Google
    if (isset($token_info['access_token'])) {
        $userInfoUrl = 'https://www.googleapis.com/oauth2/v3/userinfo?access_token=' . $token_info['access_token'];
        $userInfoResponse = file_get_contents($userInfoUrl);
        $userInfo = json_decode($userInfoResponse, true);

        if (isset($userInfo['sub']) && isset($userInfo['email']) && isset($userInfo['name'])) {
            $googleId = $userInfo['sub'];
            $email = $userInfo['email'];
            $name = $userInfo['name'];

            // Verificar si el usuario ya existe en la base de datos usando google_id
            $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE google_id = ?');
            $stmt->execute([$googleId]);
            $user = $stmt->fetch();

            if ($user) {
                // Usuario ya existe en la base de datos, guarda su id en la sesión
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'email' => $email,
                    'name' => $name
                ];
            } else {
                // Usuario nuevo, insértalo en la base de datos
                $stmt = $pdo->prepare('INSERT INTO usuarios (email, nombre, google_id) VALUES (?, ?, ?)');
                $stmt->execute([$email, $name, $googleId]);

                // Guarda el nuevo id de usuario en la sesión
                $_SESSION['user'] = [
                    'id' => $pdo->lastInsertId(),
                    'email' => $email,
                    'name' => $name
                ];
            }

            // Redirige a la página principal o a la funcionalidad que desees
            header('Location: index.php');
            exit;
        } else {
            echo 'Error: No se pudo obtener la información del usuario de Google.';
        }
    } else {
        echo 'Error durante la autenticación con Google.';
    }
}
?>
