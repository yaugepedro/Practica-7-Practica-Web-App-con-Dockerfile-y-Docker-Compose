<?php
$host = getenv('DB_HOST');
$nombre = getenv('DB_NAME');
$usuario = getenv('DB_USER');
$clave = getenv('DB_PASSWORD');

$conectado = false;
$mensajes = [];
$version = '';
$error = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$nombre;charset=utf8mb4",
        $usuario,
        $clave,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $version = $pdo->query("SELECT VERSION()")->fetchColumn();
    $mensajes = $pdo->query("SELECT id, texto, creado_en FROM mensajes ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
    $conectado = true;
} catch (PDOException $e) {
    $error = $e->getMessage();
}

$titulo = $mensajes ? $mensajes[0]['texto'] : 'Hola Mundo';

function e($valor) {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hola Mundo | PHP y MySQL</title>
    <style>
        :root {
            --fondo: #0b1120;
            --tarjeta: rgba(30, 41, 59, 0.75);
            --borde: rgba(148, 163, 184, 0.18);
            --texto: #f1f5f9;
            --suave: #94a3b8;
            --acento: #38bdf8;
            --ok: #4ade80;
            --error: #f87171;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            color: var(--texto);
            background:
                radial-gradient(circle at 15% 20%, rgba(56, 189, 248, 0.18), transparent 40%),
                radial-gradient(circle at 85% 80%, rgba(129, 140, 248, 0.18), transparent 40%),
                var(--fondo);
        }

        .tarjeta {
            width: 100%;
            max-width: 720px;
            padding: 2.5rem;
            background: var(--tarjeta);
            border: 1px solid var(--borde);
            border-radius: 20px;
            backdrop-filter: blur(12px);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.45);
        }

        .estado {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.4rem 0.9rem;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 600;
            border: 1px solid var(--borde);
        }

        .estado .punto {
            width: 9px;
            height: 9px;
            border-radius: 50%;
        }

        .estado.ok { color: var(--ok); background: rgba(74, 222, 128, 0.1); }
        .estado.ok .punto { background: var(--ok); animation: pulso 1.8s infinite; }
        .estado.fallo { color: var(--error); background: rgba(248, 113, 113, 0.1); }
        .estado.fallo .punto { background: var(--error); }

        @keyframes pulso {
            0% { box-shadow: 0 0 0 0 rgba(74, 222, 128, 0.6); }
            70% { box-shadow: 0 0 0 9px rgba(74, 222, 128, 0); }
            100% { box-shadow: 0 0 0 0 rgba(74, 222, 128, 0); }
        }

        h1 {
            margin-top: 1.5rem;
            font-size: clamp(2rem, 6vw, 3.2rem);
            line-height: 1.1;
            letter-spacing: -0.03em;
            background: linear-gradient(90deg, #f8fafc, var(--acento));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .subtitulo {
            margin-top: 0.8rem;
            color: var(--suave);
            font-size: 1.05rem;
        }

        .datos {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 0.9rem;
            margin-top: 2rem;
        }

        .dato {
            padding: 1rem 1.1rem;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid var(--borde);
            border-radius: 12px;
        }

        .dato span {
            display: block;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--suave);
        }

        .dato strong {
            display: block;
            margin-top: 0.35rem;
            font-size: 1.05rem;
            word-break: break-word;
        }

        h2 {
            margin-top: 2.2rem;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--suave);
        }

        .tabla {
            margin-top: 0.8rem;
            overflow-x: auto;
            border: 1px solid var(--borde);
            border-radius: 12px;
        }

        table { width: 100%; border-collapse: collapse; font-size: 0.95rem; }
        th, td { padding: 0.8rem 1rem; text-align: left; }
        th {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--suave);
            background: rgba(15, 23, 42, 0.6);
        }
        td { border-top: 1px solid var(--borde); }
        td.id { color: var(--acento); font-weight: 600; width: 60px; }
        td.fecha { color: var(--suave); white-space: nowrap; }

        .detalle-error {
            margin-top: 1.5rem;
            padding: 1rem 1.2rem;
            background: rgba(248, 113, 113, 0.08);
            border: 1px solid rgba(248, 113, 113, 0.3);
            border-radius: 12px;
            color: #fca5a5;
            font-family: ui-monospace, Consolas, monospace;
            font-size: 0.85rem;
            word-break: break-word;
        }

        footer {
            margin-top: 2rem;
            padding-top: 1.2rem;
            border-top: 1px solid var(--borde);
            text-align: center;
            font-size: 0.8rem;
            color: var(--suave);
        }

        @media (max-width: 480px) {
            .tarjeta { padding: 1.6rem; }
        }
    </style>
</head>
<body>
    <main class="tarjeta">
        <?php if ($conectado): ?>
            <div class="estado ok"><span class="punto"></span>Conexi&oacute;n exitosa a MySQL</div>
            <h1><?= e($titulo) ?></h1>
            <p class="subtitulo">Aplicaci&oacute;n PHP conectada a una base de datos MySQL con Docker Compose.</p>

            <div class="datos">
                <div class="dato"><span>Servidor MySQL</span><strong><?= e($version) ?></strong></div>
                <div class="dato"><span>Base de datos</span><strong><?= e($nombre) ?></strong></div>
                <div class="dato"><span>Host</span><strong><?= e($host) ?></strong></div>
                <div class="dato"><span>PHP</span><strong><?= e(PHP_VERSION) ?></strong></div>
            </div>

            <h2>Registros en la tabla mensajes</h2>
            <div class="tabla">
                <table>
                    <thead>
                        <tr><th>ID</th><th>Mensaje</th><th>Creado</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mensajes as $fila): ?>
                            <tr>
                                <td class="id"><?= e($fila['id']) ?></td>
                                <td><?= e($fila['texto']) ?></td>
                                <td class="fecha"><?= e($fila['creado_en']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="estado fallo"><span class="punto"></span>Sin conexi&oacute;n a MySQL</div>
            <h1>Hola Mundo</h1>
            <p class="subtitulo">La aplicaci&oacute;n no pudo conectarse a la base de datos.</p>
            <div class="detalle-error"><?= e($error) ?></div>
        <?php endif; ?>

        <footer>Pr&aacute;ctica 7 &middot; Dockerfile y Docker Compose</footer>
    </main>
</body>
</html>