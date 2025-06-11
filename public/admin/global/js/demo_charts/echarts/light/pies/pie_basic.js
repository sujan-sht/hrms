/* ------------------------------------------------------------------------------
 *
 *  # Echarts - Basic pie example
 *
 *  Demo JS code for basic pie chart [light theme]
 *
 * ---------------------------------------------------------------------------- */


// Setup module
// ------------------------------

var EchartsPieBasicLight = function() {


    //
    // Setup module components
    //

    // Basic pie chart
    var _scatterPieBasicLightExample = function() {
        if (typeof echarts == 'undefined') {
            console.warn('Warning - echarts.min.js is not loaded.');
            return;
        }

        // Define element
        var pie_basic_element = document.getElementById('pie_basic');
        var legendTitles = [];
        var datas = JSON.parse($('#legendValues').val());
        // var legendTitles = ['Bidhee', 'Aspark', 'Falcon'];
        // var datas2 = [
        //     {value: 2, name: 'Technical Head'},
        //     {value: 10, name: 'Laravel Developer'},
        //     {value: 5, name: 'Implementation Officer'}
        // ];

        if (pie_basic_element) {

            // Initialize chart
            var pie_basic = echarts.init(pie_basic_element);

            //
            // Chart config
            //

            // Options
            pie_basic.setOption({

                // Colors
                color: [
                    '#2ec7c9','#b6a2de','#5ab1ef','#ffb980','#d87a80',
                    '#8d98b3','#e5cf0d','#97b552','#95706d','#dc69aa',
                    '#07a2a4','#9a7fd1','#588dd5','#f5994e','#c05050',
                    '#59678c','#c9ab00','#7eb00a','#6f5553','#c14089'
                ],

                // Global text styles
                textStyle: {
                    fontFamily: 'Lexend Deca',
                    fontSize: 13
                },

                // Add title
                title: {
                    text: 'Employee Distribution',
                    subtext: 'Employee Based On Organization',
                    left: 'center',
                    textStyle: {
                        fontSize: 22,
                        fontWeight: 500
                    },
                    subtextStyle: {
                        fontSize: 12
                    }
                },

                // Add tooltip
                tooltip: {
                    trigger: 'item',
                    backgroundColor: 'rgba(0,0,0,0.75)',
                    padding: [10, 15],
                    textStyle: {
                        fontSize: 13,
                        fontFamily: 'Roboto, sans-serif'
                    },
                    formatter: "{a} <br/>{b}: {c} ({d}%)"
                },

                // Add legend
                legend: {
                    orient: 'vertical',
                    top: 'center',
                    left: 0,
                    data: legendTitles,
                    itemHeight: 8,
                    itemWidth: 8
                },

                // Add series
                series: [{
                    name: 'Organizations',
                    type: 'pie',
                    radius: '70%',
                    center: ['50%', '57.5%'],
                    itemStyle: {
                        normal: {
                            borderWidth: 1,
                            borderColor: '#fff'
                        }
                    },
                    data: datas
                }]
            });
        }

        // Resize function
        var triggerChartResize = function() {
            pie_basic_element && pie_basic.resize();
            pie_basic_element2 && pie_basic2.resize();
        };

        var pie_basic_element2 = document.getElementById('levelChart');

        if (pie_basic_element2) {

            // Initialize chart
            var pie_basic2 = echarts.init(pie_basic_element2);
            var dataValues = JSON.parse($('#levelChart').attr('data-value'));
            // var dataValues = [
            //     {value: 2, name: 'Technical Head'},
            //     {value: 10, name: 'Laravel Developer'},
            //     {value: 5, name: 'Implementation Officer'}
            // ];

            // Options
            pie_basic2.setOption({

                // Colors
                color: [
                    '#2ec7c9','#b6a2de','#5ab1ef','#ffb980','#d87a80',
                    '#8d98b3','#e5cf0d','#97b552','#95706d','#dc69aa',
                    '#07a2a4','#9a7fd1','#588dd5','#f5994e','#c05050',
                    '#59678c','#c9ab00','#7eb00a','#6f5553','#c14089'
                ],

                // Global text styles
                textStyle: {
                    fontFamily: 'Lexend Deca',
                    fontSize: 13
                },

                // Add title
                title: {
                    text: 'Employee Distribution',
                    subtext: 'Employee Based On Level',
                    left: 'center',
                    textStyle: {
                        fontSize: 22,
                        fontWeight: 500
                    },
                    subtextStyle: {
                        fontSize: 12
                    }
                },

                // Add tooltip
                tooltip: {
                    trigger: 'item',
                    backgroundColor: 'rgba(0,0,0,0.75)',
                    padding: [10, 15],
                    textStyle: {
                        fontSize: 13,
                        fontFamily: 'Roboto, sans-serif'
                    },
                    formatter: "{a} <br/>{b}: {c} ({d}%)"
                },

                // Add legend
                legend: {
                    orient: 'vertical',
                    top: 'center',
                    left: 0,
                    data: legendTitles,
                    itemHeight: 8,
                    itemWidth: 8
                },

                // Add series
                series: [{
                    name: 'Levels',
                    type: 'pie',
                    radius: '70%',
                    center: ['50%', '57.5%'],
                    itemStyle: {
                        normal: {
                            borderWidth: 1,
                            borderColor: '#fff'
                        }
                    },
                    data: dataValues
                }]
            });
        }

        // var triggerChartResize = function() {
        //     pie_basic_element2 && pie_basic2.resize();
        // };

        // On sidebar width change
        var sidebarToggle = document.querySelectorAll('.sidebar-control');
        if (sidebarToggle) {
            sidebarToggle.forEach(function(togglers) {
                togglers.addEventListener('click', triggerChartResize);
            });
        }

        // On window resize
        var resizeCharts;
        window.addEventListener('resize', function() {
            clearTimeout(resizeCharts);
            resizeCharts = setTimeout(function () {
                triggerChartResize();
            }, 200);
        });
    };


    //
    // Return objects assigned to module
    //

    return {
        init: function() {
            _scatterPieBasicLightExample();
        }
    }
}();


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function() {
    EchartsPieBasicLight.init();
});
