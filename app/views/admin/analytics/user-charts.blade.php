@include('admin.header')
@yield('content')

    <div id="wrapper">

        @include('admin.navigation')
        @yield('content')

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">{{ Lang::get('navigation.admin_analytics_users_charts') }}</h1>
                </div>
                {{-- /.col-lg-12 --}}
            </div>
            {{-- /.row --}}
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            用户总数及来源趋势图
                        </div>
                        {{-- /.panel-heading --}}
                        <div class="panel-body">
                            <div class="flot-chart">
                                <div class="flot-chart-content" id="flot-line-chart-1"></div>
                            </div>
                        </div>
                        {{-- /.panel-body --}}
                    </div>
                    {{-- /.panel --}}
                </div>
                {{-- /.col-lg-12 --}}
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            用户日活动状况趋势图
                        </div>
                        {{-- /.panel-heading --}}
                        <div class="panel-body">
                            <div class="flot-chart">
                                <div class="flot-chart-content" id="flot-line-chart-2"></div>
                            </div>
                        </div>
                        {{-- /.panel-body --}}
                    </div>
                    {{-- /.panel --}}
                </div>
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            用户周活动状况趋势图
                        </div>
                        {{-- /.panel-heading --}}
                        <div class="panel-body">
                            <div class="flot-chart">
                                <div class="flot-chart-content" id="flot-line-chart-3"></div>
                            </div>
                        </div>
                        {{-- /.panel-body --}}
                    </div>
                    {{-- /.panel --}}
                </div>
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            用户月活动状况趋势图
                        </div>
                        {{-- /.panel-heading --}}
                        <div class="panel-body">
                            <div class="flot-chart">
                                <div class="flot-chart-content" id="flot-line-chart-4"></div>
                            </div>
                        </div>
                        {{-- /.panel-body --}}
                    </div>
                    {{-- /.panel --}}
                </div>
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            完善资料用户占比状况趋势图
                        </div>
                        {{-- /.panel-heading --}}
                        <div class="panel-body">
                            <div class="flot-chart">
                                <div class="flot-chart-content" id="flot-line-chart-5"></div>
                            </div>
                        </div>
                        {{-- /.panel-body --}}
                    </div>
                    {{-- /.panel --}}
                </div>
                {{-- /.col-lg-12 --}}
            </div>
            {{-- /.row --}}
        </div>
        {{-- /#page-wrapper --}}

    </div>
    {{-- /#wrapper --}}

    {{-- jQuery Version 1.11.0 --}}
    {{ HTML::script('assets/js/jquery-1.11.1/jquery.min.js') }}

    {{-- Bootstrap Core JavaScript --}}
    {{ HTML::script('assets/bootstrap-3.3.0/js/bootstrap.min.js') }}

    {{-- Metis Menu Plugin JavaScript --}}
    {{ HTML::script('assets/js/admin/plugins/metisMenu/metisMenu.min.js') }}

    {{-- HTML::script('assets/js/chartjs/Chart.min.js') --}}

    {{-- Flot Charts JavaScript --}}
    {{ HTML::script('assets/js/admin/plugins/flot/excanvas.min.js') }}
    {{ HTML::script('assets/js/admin/plugins/flot/jquery.flot.js') }}
    {{ HTML::script('assets/js/admin/plugins/flot/jquery.flot.pie.js') }}
    {{ HTML::script('assets/js/admin/plugins/flot/jquery.flot.resize.js') }}
    {{ HTML::script('assets/js/admin/plugins/flot/jquery.flot.tooltip.min.js') }}
    {{-- HTML::script('assets/js/admin/plugins/flot/flot-data.js') --}}

    <script>
    {{-- User Basic Analytics Section --}}
    $(function() {
        var offset = 0;
        plot();

        function plot() {
            var obj = {{ $userBasicAnalytics }};

            seriesData = [];

            for (var prop in obj) {
                seriesData.push({label: prop, data:$.map(obj[prop], function(i,j){
                     return [[new Date(i[0],i[1]-1, i[2]-1).getTime(), i[3]]];
                })});
            }

            var options = {
                series: {
                    shadowSize: 4, {{-- Line shadow --}}
                    lines: {
                        show: true
                    },
                    points: {
                        show: true
                    }
                },
                grid: {
                    hoverable: true {{-- IMPORTANT! this is needed for tooltip to work --}}
                },
                xaxis: { mode: "time", timeformat: "%y-%m-%d" },
                tooltip: true,
                tooltipOpts: {
                    content: "截止 %x.1 %s： %y.4",
                    shifts: {
                        x: -60,
                        y: 25
                    }
                }
            };

            var plotObj = $.plot($("#flot-line-chart-1"), seriesData, options);
        }
    });

    {{-- User Daily Active Analytics Section --}}

    $(function() {
        var offset = 0;
        plot();

        function plot() {
            var obj = {{ $dailyActiveUserAnalytics }};

            seriesData = [];

            for (var prop in obj) {
                seriesData.push({label: prop, data:$.map(obj[prop], function(i,j){
                     return [[new Date(i[0],i[1]-1, i[2]-1).getTime(), i[3]]];
                })});
            }

            var options = {
                series: {
                    shadowSize: 4, {{-- Line shadow --}}
                    lines: {
                        show: true
                    },
                    points: {
                        show: true
                    }
                },
                grid: {
                    hoverable: true {{-- IMPORTANT! this is needed for tooltip to work --}}
                },
                xaxis: { mode: "time", timeformat: "%y-%m-%d" },
                tooltip: true,
                tooltipOpts: {
                    content: "%x.1 %s： %y.4",
                    shifts: {
                        x: -60,
                        y: 25
                    }
                }
            };

            var plotObj = $.plot($("#flot-line-chart-2"), seriesData, options);
        }
    });

    {{-- User Weekly Active Analytics Section --}}

    $(function() {
        var offset = 0;
        plot();

        function plot() {
            var obj = {{ $weeklyActiveUserAnalytics }};

            seriesData = [];

            for (var prop in obj) {
                seriesData.push({label: prop, data:$.map(obj[prop], function(i,j){
                     return [[new Date(i[0],i[1]-1, i[2]-1).getTime(), i[3]]];
                })});
            }

            var options = {
                series: {
                    shadowSize: 4, {{-- Line shadow --}}
                    lines: {
                        show: true
                    },
                    points: {
                        show: true
                    }
                },
                grid: {
                    hoverable: true {{-- IMPORTANT! this is needed for tooltip to work --}}
                },
                xaxis: { mode: "time", timeformat: "%y-%m-%d" },
                tooltip: true,
                tooltipOpts: {
                    content: "%x.1 %s： %y.4",
                    shifts: {
                        x: -60,
                        y: 25
                    }
                }
            };

            var plotObj = $.plot($("#flot-line-chart-3"), seriesData, options);
        }
    });

    {{-- User Monthly Active Analytics Section --}}

    $(function() {
        var offset = 0;
        plot();

        function plot() {
            var obj = {{ $monthlyActiveUserAnalytics }};

            seriesData = [];

            for (var prop in obj) {
                seriesData.push({label: prop, data:$.map(obj[prop], function(i,j){
                     return [[new Date(i[0],i[1]-1, i[2]-1).getTime(), i[3]]];
                })});
            }

            var options = {
                series: {
                    shadowSize: 4, {{-- Line shadow --}}
                    lines: {
                        show: true
                    },
                    points: {
                        show: true
                    }
                },
                grid: {
                    hoverable: true {{-- IMPORTANT! this is needed for tooltip to work --}}
                },
                xaxis: { mode: "time", timeformat: "%y-%m-%d" },
                tooltip: true,
                tooltipOpts: {
                    content: "%x.1 %s： %y.4",
                    shifts: {
                        x: -60,
                        y: 25
                    }
                }
            };

            var plotObj = $.plot($("#flot-line-chart-4"), seriesData, options);
        }
    });

    {{-- User Profile Complete Ratio Analytics Section --}}

    $(function() {
        var offset = 0;
        plot();

        function plot() {
            var obj = {{ $completeProfileUserRatioAnalytics }};

            seriesData = [];

            for (var prop in obj) {
                seriesData.push({label: prop, data:$.map(obj[prop], function(i,j){
                     return [[new Date(i[0],i[1]-1, i[2]-1).getTime(), i[3]]];
                })});
            }

            var options = {
                series: {
                    shadowSize: 4, {{-- Line shadow --}}
                    lines: {
                        show: true
                    },
                    points: {
                        show: true
                    }
                },
                grid: {
                    hoverable: true {{-- IMPORTANT! this is needed for tooltip to work --}}
                },
                xaxis: { mode: "time", timeformat: "%y-%m-%d" },
                tooltip: true,
                tooltipOpts: {
                    content: "%x.1 %s： %y.4 %",
                    shifts: {
                        x: -60,
                        y: 25
                    }
                }
            };

            var plotObj = $.plot($("#flot-line-chart-5"), seriesData, options);
        }
    });
    </script>
    {{-- Custom Theme JavaScript --}}
    {{ HTML::script('assets/js/admin/admin.js') }}

</body>

</html>
