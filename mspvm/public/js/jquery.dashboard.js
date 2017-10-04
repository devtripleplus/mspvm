/**
* Theme: Moltran Admin Template
* Author: Coderthemes
* Module/App: Dashboard
*/


!function($) {
    "use strict";

    var Dashboard = function() {
        this.$body = $("body")
        this.$realData = []
    };

    //creates Pie Chart
    Dashboard.prototype.createPieGraph = function(selector, labels, datas, colors) {
        var data = [{
            label: labels[0],
            data: datas[0]
        }, {
            label: labels[1],
            data: datas[1]
        }, {
            label: labels[2],
            data: datas[2]
        }];
        var options = {
            series: {
                pie: {
                    show: true
                }
            },
            legend: {
                show: false
            },
            grid: {
                hoverable: true,
                clickable: true
            },
            colors: colors,
            tooltip: true,
            tooltipOpts: {
                defaultTheme: false
            }
        };

        $.plot($(selector), data, options);
    },

    

        //initializing various charts and components
        Dashboard.prototype.init = function() {
          //plot graph data
          var uploads = [[0, 9], [1, 8], [2, 5], [3, 8], [4, 5], [5, 14], [6, 10]];
          var downloads = [[0, 5], [1, 12], [2,4], [3, 3], [4, 12], [5, 11], [6, 14]];
          var plabels = ["Visits", "Pages/Visit"];
          var pcolors = ['#317eeb', '#29b6f6'];
          var borderColor = '#fff';
          var bgColor = '#fff';
          this.createPlotGraph("#website-stats", uploads, downloads, plabels, pcolors, borderColor, bgColor);

            //Pie graph data
            var pielabels = ["Series 1","Series 2","Series 3"];
            var datas = [20,30, 20];
            var colors = ["rgba(30, 136, 229, 0.7)", "rgba(41, 182, 246, 0.7)", "rgba(126, 87, 194, 0.7)"];
            this.createPieGraph("#pie-chart #pie-chart-container", pielabels , datas, colors);

        },

    //init Dashboard
    $.Dashboard = new Dashboard, $.Dashboard.Constructor = Dashboard

    $(document).ready(function () {
        (".has-form-popup").on('click', function () {
            alert('ayyy!');
        });
    });
    
}(window.jQuery),

//initializing Dashboard
function($) {
    "use strict";
    $.Dashboard.init()
}(window.jQuery);


