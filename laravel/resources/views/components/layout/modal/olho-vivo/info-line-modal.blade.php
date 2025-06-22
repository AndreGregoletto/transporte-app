@push('styles')
    <style>
        .modal-custom {
            max-width: 1000px; /* Largura desejada */
            margin: 1.75rem auto; /* Centraliza a modal (padrão do Bootstrap) */
        }
        .modal-custom .chart-line {
            width: 100%;
            height: 300px; /* Ajuste conforme necessário */
        }
        .modal-custom .chart-line canvas {
            max-width: 100%;
            height: auto;
        }
        .no-data-message {
            text-align: center;
            color: #6c757d; /* Cor cinza para mensagem */
            padding: 20px;
            font-size: 1.1rem;
        }
    </style>
@endpush

<div 
    class="modal fade" 
    id="infoLineModal-{{ $cl }}" 
    tabindex="-1" 
    role="dialog" 
    aria-labelledby="infoLineModalLabel-{{ $cl }}" 
    aria-hidden="true"
>
    <div class="modal-dialog modal-custom" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-gray-900" id="infoLineModalLabel-{{ $cl }}"><strong>{{ $lt }}-{{ $tl }} {{ $sl == 1 ? $tp : $ts }}</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    Partindo de: <strong>{{ $sl == 1 ? $ts : $tp }}</strong><br>
                    Destino: <strong>{{ $sl == 1 ? $tp : $ts }}</strong><br>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Frequência</h6>
                    </div>
                    <div class="card-body">
                        @if (empty($frequency))
                            <div class="no-data-message">
                                <x-layout.not-found :msg="'Frequência de ônibus não encontrada.'" :width="210" />
                            </div>
                        @else
                            <div class="chart-line">
                                <canvas id="busFrequencyChart-{{ $cl }}"></canvas>
                            </div>
                            <hr>
                            Gráfico de frequência de ônibus por período.
                        @endif
                    </div>
                </div>

                <div class="card mb-3 border-bottom-primary">
                    <div class="card-body">
                        Parada: <strong>{{ $ts }}</strong><br>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const frequencyData = @json($frequency);

            // Verificar se há dados antes de criar o gráfico
            if (frequencyData.length > 0) {
                // Processar os dados para o gráfico
                const labels = frequencyData.map(item => `${item.start_time.slice(0, 5)}-${item.end_time.slice(0, 5)}`);
                const busCounts = frequencyData.map(item => {
                    // Converter start_time e end_time para segundos
                    const start = item.start_time.split(':').reduce((acc, time, index) => acc + time * Math.pow(60, 2 - index), 0);
                    const end = item.end_time.split(':').reduce((acc, time, index) => acc + time * Math.pow(60, 2 - index), 0);
                    const duration = (end - start) + 1; // Duração do período em segundos + 1 segundo
                    return Math.floor(duration / item.headway_secs); // Número de ônibus
                });

                // Configuração do gráfico
                const ctx = document.getElementById('busFrequencyChart-{{ $cl }}').getContext('2d');
                new Chart(ctx, {
                    type: 'line', // Gráfico de linha
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Número de Ônibus',
                            data: busCounts,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)', // Preenchimento sob a linha
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 2,
                            fill: true, // Preencher área sob a linha
                            tension: 0.4, // Suavizar a curva
                            pointRadius: 5, // Tamanho dos pontos
                            pointBackgroundColor: 'rgba(54, 162, 235, 1)' // Cor dos pontos
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Número de Ônibus'
                                },
                                ticks: {
                                    stepSize: 1 // Apenas números inteiros
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Período'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            }
                        },
                        maintainAspectRatio: false
                    }
                });
            }
        });
    </script>
@endpush