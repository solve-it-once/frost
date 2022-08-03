/**
 * @file
 * Theme code for using highcharts.
 */

// Set color scheme to a tetrade off the main theme color.
var colors = ["#0266c8", "#f90101", "#f2b50f", "#00933b"];
if (typeof getComputedStyle === 'function') {
  var styles = getComputedStyle(document.documentElement);
  var colorValue = styles.getPropertyValue('--color--main'),
    cvNoHex = colorValue.trim().substr(1);
  var scheme = new ColorScheme;
  scheme.from_hex(cvNoHex)
    .scheme('tetrade');
  colors = scheme.colors();
  colors = colors.map(x => "#" + x);
}

/**
 * Initialize an individual chart, either at load or mutation.
 *
 * @param initType
 */
var highchartsInitializationFunction = function (initType) {
  if (Highcharts && typeof Highcharts.chart === 'function') {
    const thisId = this.getAttribute('id');
    this.querySelector('table').setAttribute('id', thisId + '--table');
    this.insertAdjacentHTML('afterbegin', `
      <div id="${thisId}--chart" class="frost-highcharts"></div>
    `);

    // Config is the single source of truth.
    let chartConfig = {
      chart: {type: 'column'},
      colors: colors,
      title: {
        text: this.dataset.charttitle || undefined
      },
      yAxis: [
        {
          opposite: false,
          title: {
            text: this.dataset.yaxis || 'Units'
          }
        },
      ],
      xAxis: {
        allowDecimals: false
      },
      legend: {
        align: 'center',
        verticalAlign: 'bottom'
      },
      exporting: {
        allowHTML: true,
        buttons: {
          contextButton: {
            menuItems: [
              'downloadPNG',
              'downloadCSV',
            ]
          }
        }
      },
      data: {
        table: thisId + '--table'
      }
    };

    // Allow units to be added via a field later.
    const units = this.dataset.units || '';

    // Conditionally switch from column to user-selected chart type.
    if (this.classList.contains('js-chart-horizontal-bar')) {
      chartConfig.chart.type = 'bar';
      chartConfig.yAxis.allowDecimals = false;
      chartConfig.tooltip = {
        formatter: function () {
          return '<b>' + this.series.name + '</b><br/>' +
            this.point.y + ' ' + units;
        }
      };
    }
    else if (this.classList.contains('js-chart-line')) {
      chartConfig.chart.type = 'line';
      chartConfig.yAxis.allowDecimals = false;
      chartConfig.yAxis.crosshair = {width: 1};
      chartConfig.tooltip = {
        pointFormat: '<span style="color:{series.color}">{series.name}: </span><b>{point.y}</b> ' + units + ' <br/></span>',
        shared: true,
        crosshairs: true
      };
    }
    else if (this.classList.contains('js-chart-pie')) {
      chartConfig.chart.type = 'pie';
      chartConfig.tooltip = {
        pointFormat: '<span style="color:{series.color}"><b>{point.y}</b> ' + units + ' ({point.percentage:.0f}%)<br/></span>'
      };
    }
    else if (this.classList.contains('js-chart-stacked')) {
      chartConfig.chart.type = 'column';
      chartConfig.chart.lineWidth = 3;
      chartConfig.chart.marker = {radius: 0};
      chartConfig.yAxis.stackLabels = {enabled: false};
      chartConfig.plotOptions = {
        column: {
          stacking: 'normal',
          dataLabels: {
            enabled: false
          }
        }
      };
      chartConfig.tooltip = {
        formatter: function () {
          return '<b>' + this.series.name + '</b><br/>' +
            this.point.y + ' ' + units;
        }
      };
    }
    else {
      // Setting for "vertical bar" or column chart (default).
      chartConfig.yAxis.allowDecimals = false;
    }

    // Create the chart.
    let chart = new Highcharts.chart(thisId + '--chart', chartConfig);

    // Hide the table if user option selected.
    if (this.classList.contains('js-chart-replace-table')) {
      this.querySelector('table').classList.add('display--node');
    }
  }
};

// Call each applicable chart class.
utilityInitializer('js-chart-horizontal-bar', 'highchartsInitializationFunction');
utilityInitializer('js-chart-line', 'highchartsInitializationFunction');
utilityInitializer('js-chart-pie', 'highchartsInitializationFunction');
utilityInitializer('js-chart-stacked', 'highchartsInitializationFunction');
utilityInitializer('js-chart-vertical-bar', 'highchartsInitializationFunction');
