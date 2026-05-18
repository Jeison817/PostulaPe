<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<style>
    body, html {
        height: 100%;
        margin: 0;
        background: url('public/img/fondo_interfaz.jpg') no-repeat center center fixed;
        background-size: cover;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .card-custom {
        background: rgba(255, 255, 255, 0.9); /* blanco semitransparente para ver el fondo */
        border-radius: 15px;
        box-shadow: 0 6px 18px rgba(40, 167, 69, 0.15);
        padding: 30px 25px;
        max-width: 1100px;
        margin: 40px auto;
    }

    h2 {
        color: #28a745;
        font-weight: 700;
        text-align: center;
        margin-bottom: 30px;
        text-shadow: 1px 1px 3px rgba(40,167,69,0.25);
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 10px;
    }

    thead th {
        background-color: #28a745;
        color: white;
        font-weight: 700;
        padding: 12px 15px;
        border-radius: 8px 8px 0 0;
        text-align: center;
    }

    tbody tr {
        background: #ffffff;
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.1);
        border-radius: 10px;
        transition: transform 0.2s ease;
    }
    tbody tr:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 18px rgba(40, 167, 69, 0.3);
    }

    tbody td {
        padding: 15px;
        vertical-align: middle;
        text-align: center;
        color: #2e2e2e;
    }

    tbody td:first-child {
        font-weight: 700;
        color: #19692c;
    }

    .badge {
        font-size: 0.9rem;
        padding: 0.4em 0.8em;
        border-radius: 12px;
        text-transform: capitalize;
        font-weight: 600;
        letter-spacing: 0.02em;
    }

    .btn-outline-info {
        font-weight: 600;
        padding: 5px 12px;
        font-size: 0.9rem;
        border-radius: 8px;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .btn-outline-info:hover {
        background-color: #28a745;
        color: white;
        border-color: #28a745;
    }

    .btn-secondary {
        background-color: #e9f5ec;
        border: 2px solid #28a745;
        color: #28a745;
        font-weight: 600;
        border-radius: 8px;
        padding: 10px 25px;
        transition: background-color 0.3s ease, color 0.3s ease;
        display: inline-block;
        margin-top: 20px;
    }

    .btn-secondary:hover {
        background-color: #28a745;
        color: white;
        border-color: #28a745;
        text-decoration: none;
    }

    /* Responsive */
    @media (max-width: 768px) {
        table, thead, tbody, th, td, tr {
            display: block;
        }
        thead tr {
            display: none;
        }
        tbody tr {
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.15);
            border-radius: 12px;
            padding: 15px;
        }
        tbody td {
            text-align: right;
            padding-left: 50%;
            position: relative;
        }
        tbody td::before {
            content: attr(data-label);
            position: absolute;
            left: 15px;
            top: 15px;
            font-weight: 700;
            color: #19692c;
            text-transform: uppercase;
            font-size: 0.85rem;
        }
    }
</style>

<div>
    <div class="card-custom">
        <h2>Mis Postulaciones</h2>

        <?php if (!empty($postulaciones)): ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Convocatoria</th>
                        <th>Estado</th>
                        <th>CV</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($postulaciones as $index => $p): ?>
                        <?php
                            $estado = $p['Estado'];
                            $badgeClass = match($estado) {
                                'postulado'    => 'badge bg-primary',
                                'en_proceso'   => 'badge bg-warning text-dark',
                                'seleccionado' => 'badge bg-success',
                                'descartado'   => 'badge bg-danger',
                                default        => 'badge bg-secondary'
                            };
                        ?>
                        <tr>
                            <td data-label="#"> <?= $index + 1 ?> </td>
                            <td data-label="Convocatoria">
                                <a href="index.php?controller=postulante&action=detalleConvocatoria&idConvocatoria=<?= $p['IdConvocatoria'] ?>" 
                                class="text-success fw-bold">
                                    <?= htmlspecialchars($p['Convocatoria']) ?>
                                </a>
                            </td>
                            <td data-label="Estado"><span class="<?= $badgeClass ?>"><?= ucfirst($estado) ?></span></td>
                            <td data-label="CV">
                                <?php if (!empty($p['CVPath'])): ?>
                                    <a href="<?= $p['CVPath'] ?>" target="_blank" class="btn btn-sm btn-outline-info">Ver CV</a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td data-label="Fecha"><?= date("d/m/Y H:i", strtotime($p['FechaCreacion'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info text-center">Aún no tienes postulaciones registradas.</div>
        <?php endif; ?>

        <!-- Botón volver al panel -->
        <div class="text-center">
            <a href="index.php?controller=postulante&action=index" class="btn btn-secondary">
                ← Volver al Panel
            </a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
