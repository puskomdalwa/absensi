@extends('layouts.admin.template')
@section('title', 'Dashboard')
@section('content')
    <div class="row g-6">
        <!-- View sales -->
        <div class="col-xl-{{ $user->role == 'admin' ? '4' : '12' }}">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-7">
                        <div class="card-body text-nowrap">
                            <h5 class="card-title mb-0">Welcome Back {{ $user->name }} 🎉</h5>
                            <p class="mb-2">Let's play typing arabic game</p>
                            <h4 class="text-primary mb-1">
                                {{ @$player ? $player->typingScore->sum('score') . ' score' : '0 Score' }}</h4>
                            <a href="#" class="btn btn-primary">Play Now</a>
                        </div>
                    </div>
                    <div class="col-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img src="{{ asset('admin/assets/img/illustrations/card-advance-sale.png') }}" height="140"
                                alt="view sales" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- View sales -->

        @if ($user->role->akses == 'admin')
            <!-- Statistics -->
            <div class="col-xl-8 col-md-12">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="card-title mb-0">Statistics</h5>
                        <small class="text-muted">Arabic Keyboard</small>
                    </div>
                    <div class="card-body d-flex align-items-end">
                        <div class="w-100">
                            <div class="row gy-3">
                                <div class="col-md-3 col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="badge rounded bg-label-primary me-4 p-2">
                                            <i class="ti ti-folder ti-lg"></i>
                                        </div>
                                        <div class="card-info">
                                            <h5 class="mb-0">{{ @$totalCategory }}</h5>
                                            <small>Category</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="badge rounded bg-label-info me-4 p-2"><i class="ti ti-files ti-lg"></i>
                                        </div>
                                        <div class="card-info">
                                            <h5 class="mb-0">{{ @$totalNews }}</h5>
                                            <small>News</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="badge rounded bg-label-danger me-4 p-2">
                                            <i class="ti ti-user ti-lg"></i>
                                        </div>
                                        <div class="card-info">
                                            <h5 class="mb-0">{{ @$totalPlayer }}</h5>
                                            <small>Players</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="badge rounded bg-label-success me-4 p-2">
                                            <i class="ti ti-star ti-lg"></i>
                                        </div>
                                        <div class="card-info">
                                            <h5 class="mb-0">{{ @$totalUser }}</h5>
                                            <small>Users</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Statistics -->
        @endif


    </div>
@endsection
@push('scripts')
    <script>
        if (isDarkStyle) {
            cardColor = config.colors_dark.cardColor;
            labelColor = config.colors_dark.textMuted;
            legendColor = config.colors_dark.bodyColor;
            headingColor = config.colors_dark.headingColor;
            borderColor = config.colors_dark.borderColor;
        } else {
            cardColor = config.colors.cardColor;
            labelColor = config.colors.textMuted;
            legendColor = config.colors.bodyColor;
            headingColor = config.colors.headingColor;
            borderColor = config.colors.borderColor;
        }

        // Donut Chart Colors
        const chartColors = {
            donut: {
                series1: '#24B364',
                series2: '#53D28C',
                series3: '#7EDDA9',
                series4: '#A9E9C5'
            }
        };
        // Generated Leads Chart
        // --------------------------------------------------------------------
        const generatedLeadsChartEl = document.querySelector('#generatedLeadsChart'),
            generatedLeadsChartConfig = {
                chart: {
                    height: 125,
                    width: 120,
                    parentHeightOffset: 0,
                    type: 'donut'
                },
                labels: ['Electronic', 'Sports', 'Decor', 'Fashion'],
                series: [45, 58, 30, 50],
                colors: [
                    chartColors.donut.series1,
                    chartColors.donut.series2,
                    chartColors.donut.series3,
                    chartColors.donut.series4
                ],
                stroke: {
                    width: 0
                },
                dataLabels: {
                    enabled: false,
                    formatter: function(val, opt) {
                        return parseInt(val) + '%';
                    }
                },
                legend: {
                    show: false
                },
                tooltip: {
                    theme: false
                },
                grid: {
                    padding: {
                        top: 15,
                        right: -20,
                        left: -20
                    }
                },
                states: {
                    hover: {
                        filter: {
                            type: 'none'
                        }
                    }
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                            labels: {
                                show: true,
                                value: {
                                    fontSize: '1.5rem',
                                    fontFamily: 'Public Sans',
                                    color: headingColor,
                                    fontWeight: 500,
                                    offsetY: -15,
                                    formatter: function(val) {
                                        return parseInt(val) + '%';
                                    }
                                },
                                name: {
                                    offsetY: 20,
                                    fontFamily: 'Public Sans'
                                },
                                total: {
                                    show: true,
                                    showAlways: true,
                                    color: config.colors.success,
                                    fontSize: '.8125rem',
                                    label: 'Total',
                                    fontFamily: 'Public Sans',
                                    formatter: function(w) {
                                        return '{{ @$totalPlayer }}';
                                    }
                                }
                            }
                        }
                    }
                },
                responsive: [{
                        breakpoint: 1025,
                        options: {
                            chart: {
                                height: 172,
                                width: 160
                            }
                        }
                    },
                    {
                        breakpoint: 769,
                        options: {
                            chart: {
                                height: 178
                            }
                        }
                    },
                    {
                        breakpoint: 426,
                        options: {
                            chart: {
                                height: 147
                            }
                        }
                    }
                ]
            };
        if (typeof generatedLeadsChartEl !== undefined && generatedLeadsChartEl !== null) {
            const generatedLeadsChart = new ApexCharts(generatedLeadsChartEl, generatedLeadsChartConfig);
            generatedLeadsChart.render();
        }
    </script>

@endpush
