<?php
// includes/visitas.php
// Regras e funções do agendamento de visitas.
// Precisa do includes/auth.php já incluído (usa a função e()).

// Horário de Brasília: "hoje" e "agora" dependem disso
date_default_timezone_set('America/Sao_Paulo');

// Horários oferecidos para visita (manhã e tarde, com pausa para o almoço)
const HORARIOS_VISITA = ['09:00', '10:00', '11:00', '14:00', '15:00', '16:00'];

// Até quantos dias à frente dá para agendar
const DIAS_MAX_AGENDAMENTO = 60;

const STATUS_VISITA = [
    'pendente'   => 'Pendente',
    'confirmada' => 'Confirmada',
    'recusada'   => 'Recusada',
    'cancelada'  => 'Cancelada',
];

/** Confere se a data está no formato AAAA-MM-DD e se existe de verdade (rejeita 2026-02-31). */
function dataValida(string $data): bool
{
    $d = DateTimeImmutable::createFromFormat('!Y-m-d', $data);
    return $d !== false && $d->format('Y-m-d') === $data;
}

/** Já existe visita pendente ou confirmada nesse animal, dia e horário? (recusada e cancelada liberam o horário) */
function horarioOcupado(PDO $pdo, int $animalId, string $data, string $horario): bool
{
    $stmt = $pdo->prepare(
        "SELECT id FROM visitas
        WHERE animal_id = ? AND data_visita = ? AND horario = ?
        AND status IN ('pendente', 'confirmada')"
    );
    $stmt->execute([$animalId, $data, $horario . ':00']);
    return (bool) $stmt->fetch();
}

/** O usuário já tem uma visita futura ativa (pendente ou confirmada) com esse animal? */
function usuarioJaTemVisita(PDO $pdo, int $usuarioId, int $animalId): bool
{
    $stmt = $pdo->prepare(
        "SELECT id FROM visitas
        WHERE usuario_id = ? AND animal_id = ? AND data_visita >= ?
        AND status IN ('pendente', 'confirmada')"
    );
    $stmt->execute([$usuarioId, $animalId, date('Y-m-d')]);
    return (bool) $stmt->fetch();
}

/** 2026-11-20 -> 20/11/2026 */
function formatarData(string $dataSql): string
{
    $d = DateTimeImmutable::createFromFormat('!Y-m-d', $dataSql);
    return $d ? $d->format('d/m/Y') : $dataSql;
}

/** 09:00:00 -> 09:00 */
function formatarHora(string $horaSql): string
{
    return substr($horaSql, 0, 5);
}

/** Etiqueta colorida do status da visita (o texto passa pelo e()). */
function seloVisita(string $status): string
{
    $rotulo = STATUS_VISITA[$status] ?? $status;
    return '<span class="status-visita status-' . e($status) . '">' . e($rotulo) . '</span>';
}