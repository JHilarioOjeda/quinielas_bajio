<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista Final – {{ $quiniela->title }}</title>
    @vite(['resources/css/app.css'])
    <style>
        /* Cabeceras verticales de partidos */
        th.col-rotate {
            padding: 0;
            height: 96px;
            vertical-align: bottom;
            width: 28px;
        }
        .rotate-inner {
            writing-mode: vertical-rl;
            text-orientation: mixed;
            transform: rotate(180deg);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block;
            height: 96px;
            font-size: 8px;
            font-weight: 700;
            padding: 4px 2px;
            line-height: 1.4;
        }
        /* Celdas de acierto / fallo */
        td.hit-cell  { background-color: #d1fae5 !important; color: #065f46; font-weight: 700; }
        td.miss-cell { color: #9ca3af; }
        td.pend-cell { color: #d1d5db; }
        /* Filas ganadoras */
        tr.winner-row td                { background-color: #fefce8 !important; }
        tr.winner-row td.pts-cell       { background-color: #fde047 !important; color: #166534; font-weight: 900; }
        tr.winner-row td.hit-cell       { background-color: #bbf7d0 !important; }
        /* Excluidas por diferenciador */
        tr.excluded-row                 { opacity: 0.45; }
        /* Filas alternas */
        tr.row-odd  td                  { background-color: #ffffff; }
        tr.row-even td                  { background-color: #f0fdf4; }
        /* Impresión */
        @media print {
            .no-print { display: none !important; }
            body      { background: white !important; }
            .page-card { box-shadow: none !important; border-radius: 0 !important; max-width: 100% !important; }
            @page { size: landscape; margin: 5mm; }
        }
    </style>
</head>
<body class="bg-primarycolor min-h-screen p-3 font-sans">

@php
    $totalTickets = count($rows);
    $totalRecaudo = $quiniela->tickets->sum('amount_paid');
    $maxPoints    = $rows[0]['points'] ?? 0;
    $winners      = collect($rows)->where('points', $maxPoints)->count();
    $secondPoints = collect($rows)->where('points', '<', $maxPoints)->max('points');
@endphp

<!-- Barra de acciones (sólo pantalla) -->
<div class="no-print flex items-center gap-3 max-w-6xl mx-auto mb-3 px-1">
    <button
        onclick="window.print()"
        class="inline-flex items-center gap-2 bg-secondarycolor hover:bg-secondaryhovercolor text-white text-xs font-semibold px-4 py-2 rounded-lg shadow transition">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
            <path fill-rule="evenodd" d="M7.875 1.5C6.839 1.5 6 2.34 6 3.375v2.99c-.426.053-.851.11-1.274.174-1.454.218-2.476 1.483-2.476 2.917v6.294a3 3 0 0 0 3 3h.27l-.155 1.705A1.875 1.875 0 0 0 7.232 22.5h9.536a1.875 1.875 0 0 0 1.867-2.045l-.155-1.705h.27a3 3 0 0 0 3-3V9.456c0-1.434-1.022-2.7-2.476-2.917A48.716 48.716 0 0 0 18 6.366V3.375c0-1.036-.84-1.875-1.875-1.875h-8.25ZM16.5 6.205v-2.83A.375.375 0 0 0 16.125 3h-8.25a.375.375 0 0 0-.375.375v2.83a49.353 49.353 0 0 1 9 0Zm-.075 8.042c.003.021.006.042.006.064v.75c0 .414-.336.75-.75.75H8.25a.75.75 0 0 1-.75-.75v-.75c0-.022.003-.043.006-.064h8.919Z" clip-rule="evenodd" />
        </svg>
        Imprimir / Guardar PDF
    </button>
    <span class="text-white/70 text-xs">{{ now()->locale('es')->translatedFormat('d \d\e F \d\e Y, H:i') }}</span>
</div>

<!-- Tarjeta principal -->
<div class="page-card max-w-6xl mx-auto bg-white rounded-xl border border-primarycolor/30 shadow-sm overflow-hidden space-y-0">

    <!-- Encabezado -->
    <div class="flex items-center justify-between gap-3 px-4 py-3 border-b border-gray-100">
        <div>
            <p class="text-xs uppercase tracking-wide text-amber-600 font-semibold">Lista Final</p>
            <h1 class="text-lg md:text-2xl font-semibold text-primarycolor leading-tight">
                {{ $quiniela->title }}
            </h1>
            <p class="text-[11px] text-gray-500 mt-0.5">
                Jornada del
                {{ $quiniela->play_start ? \Carbon\Carbon::parse($quiniela->play_start)->locale('es')->translatedFormat('d F Y') : '' }}
                al
                {{ $quiniela->play_end   ? \Carbon\Carbon::parse($quiniela->play_end  )->locale('es')->translatedFormat('d F Y') : '' }}
            </p>
        </div>
        <div class="text-right">
            <img src="/imgs/logos/logoquinielas.png" alt="Logo" class="w-16 md:w-20 object-contain" />
        </div>
    </div>

    <!-- Aviso partido suplente -->
    @if($substituteMatch)
    <div class="bg-amber-50 border-b border-amber-200 px-4 py-2 text-[10px] text-amber-700 font-medium">
        El partido suplente se usará únicamente si algún partido regular es suspendido o para reducir el número de ganadores.
    </div>
    @endif

    <!-- Stats strip -->
    <div class="grid grid-cols-5 divide-x divide-gray-200 border-b border-gray-200 text-center">
        <div class="py-2 px-1">
            <p class="text-[9px] uppercase text-gray-400 font-semibold tracking-wide">Participantes</p>
            <p class="text-base font-bold text-primarycolor">{{ $totalTickets }}</p>
        </div>
        <div class="py-2 px-1">
            <p class="text-[9px] uppercase text-gray-400 font-semibold tracking-wide">Partidos</p>
            <p class="text-base font-bold text-primarycolor">{{ $regularMatches->count() }}</p>
        </div>
        <div class="py-2 px-1">
            <p class="text-[9px] uppercase text-gray-400 font-semibold tracking-wide">Mayor puntaje</p>
            <p class="text-base font-bold text-primarycolor">{{ $maxPoints }}</p>
        </div>
        <div class="py-2 px-1">
            <p class="text-[9px] uppercase text-gray-400 font-semibold tracking-wide">{{ $winners === 1 ? 'Ganador' : 'Ganadores' }}</p>
            <p class="text-base font-bold text-primarycolor">{{ $winners }}</p>
        </div>
        <div class="py-2 px-1">
            <p class="text-[9px] uppercase text-gray-400 font-semibold tracking-wide">Precio/jugada</p>
            <p class="text-base font-bold text-primarycolor">$ {{ number_format($quiniela->price, 0) }}</p>
        </div>
    </div>

    <!-- Bolsa total -->
    <div class="px-4 py-2 border-b border-gray-100 flex items-center gap-2">
        <span class="text-[10px] text-gray-500 uppercase font-semibold tracking-wide">Bolsa total:</span>
        <span class="text-sm font-bold text-primarycolor">$ {{ number_format($totalRecaudo, 0, '.', ',') }}</span>
    </div>

    <!-- Leyenda -->
    <div class="px-4 py-2 border-b border-gray-100 flex flex-wrap gap-x-4 gap-y-1 text-[10px] text-gray-500">
        <span class="flex items-center gap-1">
            <span class="inline-block w-3 h-3 rounded bg-emerald-100 border border-emerald-300"></span> Acierto
        </span>
        <span class="flex items-center gap-1">
            <span class="inline-block w-3 h-3 rounded bg-gray-100 border border-gray-300"></span> Fallo
        </span>
        <span class="flex items-center gap-1">
            <span class="inline-block w-3 h-3 rounded bg-yellow-100 border border-yellow-300"></span> Líder
        </span>
        <span class="flex items-center gap-1">
            <span class="inline-block w-3 h-3 rounded bg-amber-50 border border-amber-200"></span> Resultado pendiente
        </span>
        <span class="flex items-center gap-1">
            <span class="inline-block w-3 h-3 rounded bg-primarycolor"></span> Partido regular &nbsp;
            <span class="inline-block w-3 h-3 rounded bg-amber-500"></span> Partido suplente
        </span>
        <span class="text-gray-400">L = Local &nbsp;|&nbsp; E = Empate &nbsp;|&nbsp; V = Visitante</span>
    </div>

    <!-- Tabla de predicciones -->
    <div class="overflow-x-auto">
        <table class="w-full border-collapse text-[10px]" style="table-layout:fixed;">
            <thead>
                <tr>
                    <!-- # -->
                    <th class="border border-gray-200 text-center bg-primarycolor text-white font-bold py-1 text-[9px]" style="width:26px;">#</th>
                    <!-- Nombre -->
                    <th class="border border-gray-200 text-left bg-primarycolor text-white font-bold px-2 py-1 text-[9px]" style="width:130px;">PARTICIPANTE</th>

                    <!-- Cabeceras partidos regulares -->
                    @foreach($regularMatches as $em)
                        @php
                            $home = $em->matchGame?->homeTeam?->name ?? 'Local';
                            $away = $em->matchGame?->awayTeam?->name ?? 'Visitante';
                        @endphp
                        <th class="col-rotate border border-gray-200">
                            <span class="rotate-inner bg-primarycolor text-white">{{ strtoupper($home) }} vs {{ strtoupper($away) }}</span>
                        </th>
                    @endforeach

                    <!-- Cabecera partido suplente -->
                    @if($substituteMatch)
                        @php
                            $sh = $substituteMatch->matchGame?->homeTeam?->name ?? 'Local';
                            $sa = $substituteMatch->matchGame?->awayTeam?->name ?? 'Visitante';
                        @endphp
                        <th class="col-rotate border border-gray-200">
                            <span class="rotate-inner bg-amber-500 text-white">PS: {{ strtoupper($sh) }} vs {{ strtoupper($sa) }}</span>
                        </th>
                    @endif

                    <!-- PTS -->
                    <th class="border border-gray-200 text-center bg-primarycolor text-white font-bold py-1 text-[9px]" style="width:34px;">PTS</th>
                </tr>

                <!-- Fila resultados oficiales -->
                <tr>
                    <td class="border border-gray-200 text-center bg-amber-50 text-amber-700 font-bold py-1" style="width:26px;">–</td>
                    <td class="border border-gray-200 text-left bg-amber-50 px-2 py-1 text-[9px] font-bold text-amber-700 uppercase tracking-wide" style="width:130px;">Resultados</td>

                    @foreach($regularMatches as $em)
                        @php $res = $matchResults[$em->id] ?? null; @endphp
                        <td class="border border-gray-200 text-center bg-amber-50 font-bold text-amber-700" style="width:28px;">
                            {{ $res ? ($displayMap[$res] ?? $res) : '–' }}
                        </td>
                    @endforeach

                    @if($substituteMatch)
                        @php $subRes = $matchResults[$substituteMatch->id] ?? null; @endphp
                        <td class="border border-gray-200 text-center bg-amber-100 font-bold text-amber-700" style="width:28px;">
                            {{ $subRes ? ($displayMap[$subRes] ?? $subRes) : '–' }}
                        </td>
                    @endif

                    <td class="border border-gray-200 text-center bg-amber-50 text-amber-700 font-bold" style="width:34px;">–</td>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse($rows as $i => $row)
                    @php
                        $isWinner      = $row['points'] === $maxPoints && $maxPoints > 0;
                        $isSecond      = ! $isWinner && $secondPoints !== null && $row['points'] === $secondPoints;
                        $hasSubHit     = $row['sub_hit'] ?? false;
                        $excludedByTie = ($isWinner && $firstTiebreakerApplied  && ! $hasSubHit)
                                      || ($isSecond && $secondTiebreakerApplied && ! $hasSubHit);
                        $markedByTie   = ($isWinner && $firstTiebreakerApplied  && $hasSubHit)
                                      || ($isSecond && $secondTiebreakerApplied && $hasSubHit);
                        $rowClass = $excludedByTie
                            ? 'excluded-row'
                            : (($isWinner && ! $excludedByTie) ? 'winner-row' : ($i % 2 === 0 ? 'row-even' : 'row-odd'));
                    @endphp
                    <tr class="{{ $rowClass }}">
                        <td class="border border-gray-200 text-center text-gray-400 text-[9px]">{{ $i + 1 }}</td>
                        <td class="border border-gray-200 px-2 py-1 text-left {{ ($isWinner || $isSecond) && ! $excludedByTie ? 'font-bold text-gray-900' : 'text-gray-700' }}">
                            {{ strtoupper($row['player_name']) }}
                            @if($markedByTie)
                                <span class="text-[7px] text-amber-600 font-bold"> ★PS</span>
                            @endif
                        </td>

                        @foreach($regularMatches as $em)
                            @php
                                $sels       = $row['predictions'][$em->id] ?? [];
                                $res        = $matchResults[$em->id] ?? null;
                                $resDisplay = $res ? ($displayMap[$res] ?? $res) : null;
                                $isHit      = $resDisplay && in_array($resDisplay, $sels);
                                $cellClass  = $res === null ? 'pend-cell' : ($isHit ? 'hit-cell' : 'miss-cell');
                                $cellText   = implode('/', $sels) ?: '–';
                            @endphp
                            <td class="border border-gray-200 text-center {{ $cellClass }}">{{ $cellText }}</td>
                        @endforeach

                        @if($substituteMatch)
                            @php
                                $subSels    = $row['sub_prediction'] ?? [];
                                $subRes     = $matchResults[$substituteMatch->id] ?? null;
                                $subDisplay = $subRes ? ($displayMap[$subRes] ?? $subRes) : null;
                                $subHit     = $subDisplay && in_array($subDisplay, $subSels);
                                $subClass   = $subRes === null ? 'pend-cell' : ($subHit ? 'hit-cell' : 'miss-cell');
                            @endphp
                            <td class="border border-gray-200 text-center {{ $subClass }}">{{ implode('/', $subSels) ?: '–' }}</td>
                        @endif

                        <td class="border border-gray-200 text-center font-bold text-sm pts-cell">{{ $row['points'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ 2 + $regularMatches->count() + ($substituteMatch ? 1 : 0) + 1 }}"
                            class="py-6 text-center text-gray-400 text-xs italic">
                            No hay participantes registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Reglamento -->
    <div class="px-4 py-4 border-t border-gray-200 space-y-2">
        <h2 class="text-xs uppercase font-semibold tracking-wide text-amber-600">Reglamento</h2>
        <ol class="list-decimal list-inside space-y-0.5 text-[10px] text-gray-600 leading-relaxed">
            <li>El ganador o ganadores es quien tenga la mayor cantidad de aciertos y se reparte en partes iguales.</li>
            <li>Favor de revisar la prelista. En caso de error existe un tiempo determinado para notificarlo y realizar correcciones.</li>
            <li>Todos los partidos solo cuentan el resultado de los 90 minutos más el agregado. No cuentan tiempos extras ni penales.</li>
            <li>Una vez publicada la lista final no se aceptan reclamos o quejas.</li>
            <li>En caso de suspensión de un partido durante el encuentro (y no se posponga antes del último partido de la quiniela), se toma el resultado al momento de la suspensión.</li>
            <li>Para el segundo lugar, en caso de haber más de 20 ganadores, se tomará en cuenta el partido suplente para reducir el número de ganadores.</li>
            <li>Si se mandan quinielas con algún espacio en blanco o la letra no sea legible o no sea L, E, V, se tomará como empate.</li>
            <li>Para segundos lugares se reparte a la segunda mayor cantidad de aciertos con un tope de máximo 20 ganadores. Solo se repartirá premio si como mínimo les corresponden $100.00 pesos a cada uno; de lo contrario el premio se acumula para la siguiente jornada.</li>
        </ol>
    </div>

    <!-- Pie -->
    <div class="bg-primarycolor text-white/80 text-[9px] text-center py-2 rounded-b-xl">
        Quinielas del Bajío &nbsp;·&nbsp; {{ $quiniela->title }} &nbsp;·&nbsp; Generado el {{ now()->locale('es')->translatedFormat('d \d\e F \d\e Y') }}
    </div>

</div>

</body>
</html>
