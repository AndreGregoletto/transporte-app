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

            if (!frequencyData || (!frequencyData.week && !frequencyData.weekend)) {
                console.error('Nenhum dado de frequência disponível para a linha {{ $cl }}');
                return;
            }

            const processData = (data) => {
                if (!data || !Array.isArray(data)) return { labels: [], busCounts: [] };
                return {
                    labels: data.map(item => `${item.start_time.slice(0, 5)}-${item.end_time.slice(0, 5)}`),
                    busCounts: data.map(item => {
                        const start = item.start_time.split(':').reduce((acc, time, index) => acc + time * Math.pow(60, 2 - index), 0);
                        const end = item.end_time.split(':').reduce((acc, time, index) => acc + time * Math.pow(60, 2 - index), 0);
                        const duration = (end - start) + 1;
                        return Math.floor(duration / item.headway_secs);
                    })
                };
            };

            const weekData = processData(frequencyData.week);
            const weekendData = processData(frequencyData.weekend);

            const allLabels = [...new Set([...weekData.labels, ...weekendData.labels])].sort();

            const alignData = (labels, dataLabels, dataCounts) => {
                return labels.map(label => {
                    const index = dataLabels.indexOf(label);
                    return index !== -1 ? dataCounts[index] : 0;
                });
            };

            const weekBusCounts = alignData(allLabels, weekData.labels, weekData.busCounts);
            const weekendBusCounts = alignData(allLabels, weekendData.labels, weekendData.busCounts);

            const today = new Date().getDay(); 
            const isWeekday = today >= 1 && today <= 5; 

            const canvas = document.getElementById('busFrequencyChart-{{ $cl }}');
            if (!canvas) {
                console.error('Canvas não encontrado: busFrequencyChart-{{ $cl }}');
                return;
            }

            const ctx = canvas.getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: allLabels,
                    datasets: [
                        {
                            label: 'Dias Úteis',
                            data: weekBusCounts,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 5,
                            pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                            hidden: !isWeekday
                        },
                        {
                            label: 'Final de Semana',
                            data: weekendBusCounts,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 5,
                            pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                            hidden: isWeekday
                        }
                    ]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Número de Ônibus' },
                            ticks: { stepSize: 1 }
                        },
                        x: { title: { display: true, text: 'Período' } }
                    },
                    plugins: { legend: { display: true, position: 'top' } },
                    maintainAspectRatio: false
                }
            });
        });
    </script>
@endpush