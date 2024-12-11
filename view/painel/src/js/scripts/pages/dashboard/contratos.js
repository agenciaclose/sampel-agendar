(function () {

  let labelColor, headingColor, borderColor;

  labelColor = config.colors_dark.textMuted;
  headingColor = config.colors_dark.headingColor;
  borderColor = config.colors_dark.borderColor;

  function radialBarChart(color, value, show) {
    const radialBarChartOpt = {
      chart: {
        height: show == 'true' ? 58 : 48,
        width: show == 'true' ? 58 : 38,
        type: 'radialBar'
      },
      plotOptions: {
        radialBar: {
          hollow: {
            size: show == 'true' ? '50%' : '25%'
          },
          dataLabels: {
            show: show == 'true' ? true : false,
            value: {
              offsetY: -10,
              fontSize: '15px',
              fontWeight: 500,
              fontFamily: 'Public Sans',
              color: headingColor
            }
          },
          track: {
            background: config.colors_label.secondary
          }
        }
      },
      stroke: {
        lineCap: 'round'
      },
      colors: [color],
      grid: {
        padding: {
          top: show == 'true' ? -12 : -15,
          bottom: show == 'true' ? -17 : -15,
          left: show == 'true' ? -17 : -5,
          right: -15
        }
      },
      series: [value],
      labels: show == 'true' ? [''] : ['Progress']
    };
    return radialBarChartOpt;
  }

  const chartProgressList = document.querySelectorAll('.chart-progress');
  if (chartProgressList) {
    chartProgressList.forEach(function (chartProgressEl) {
      const color = config.colors[chartProgressEl.dataset.color],
        series = chartProgressEl.dataset.series;
      const progress_variant = chartProgressEl.dataset.progress_variant;
      const optionsBundle = radialBarChart(color, series, progress_variant);
      const chart = new ApexCharts(chartProgressEl, optionsBundle);
      chart.render();
    });
  }

})();

  let dias = $('#revenueGrowth').attr('data-dias').split(',');
  let dias_qtd = $('#revenueGrowth').attr('data-qtd').split(',').map(Number);

  // --------------------------------------------------------------------
  const revenueGrowthEl = document.querySelector('#revenueGrowth'),
    revenueGrowthConfig = {
      chart: {
        height: 170,
        type: 'bar',
        parentHeightOffset: -20,
        toolbar: {
          show: false
        }
      },
      plotOptions: {
        bar: {
          barHeight: '100%',
          columnWidth: '50%',
          startingShape: 'rounded',
          endingShape: 'rounded',
          borderRadius: 4,
          distributed: true,
          dataLabels: {
            position: 'top'
          }
        }
      },
      tooltip: {
        enabled: false
      },
      grid: {
        show: false,
        padding: {
          top: -20,
          bottom: -12,
          left: -10,
          right: 0
        }
      },
      colors: [
        '#05618e',
        '#05618e',
        '#05618e',
        '#05618e',
        '#05618e',
        '#05618e',
        '#05618e'
      ],
      dataLabels: {
        enabled: true,
        offsetY: 0
      },
      series: [
        {
          name: 'Pedidos',
          data: dias_qtd
        }
      ],
      legend: {
        show: false
      },
      xaxis: {
        categories: dias,
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        },
        labels: {
          style: {
            colors: '#1194dc',
            fontSize: '13px',
            fontFamily: 'Public Sans'
          }
        }
      },
      yaxis: {
        labels: {
          show: false
        }
      },
      states: {
        hover: {
          filter: {
            type: 'none'
          }
        }
      },
      responsive: [
        {
          breakpoint: 1471,
          options: {
            plotOptions: {
              bar: {
                columnWidth: '50%'
              }
            }
          }
        },
        {
          breakpoint: 1350,
          options: {
            plotOptions: {
              bar: {
                columnWidth: '57%'
              }
            }
          }
        },
        {
          breakpoint: 1032,
          options: {
            plotOptions: {
              bar: {
                columnWidth: '60%'
              }
            }
          }
        },
        {
          breakpoint: 992,
          options: {
            plotOptions: {
              bar: {
                columnWidth: '40%',
                borderRadius: 8
              }
            }
          }
        },
        {
          breakpoint: 855,
          options: {
            plotOptions: {
              bar: {
                columnWidth: '50%',
                borderRadius: 6
              }
            }
          }
        },
        {
          breakpoint: 440,
          options: {
            plotOptions: {
              bar: {
                columnWidth: '40%'
              }
            }
          }
        },
        {
          breakpoint: 381,
          options: {
            plotOptions: {
              bar: {
                columnWidth: '45%'
              }
            }
          }
        }
      ]
    };
  if (typeof revenueGrowthEl !== undefined && revenueGrowthEl !== null) {
    const revenueGrowth = new ApexCharts(revenueGrowthEl, revenueGrowthConfig);
    revenueGrowth.render();
  }

  // PEDIDOS POR MESES
  let meses = $('#weeklyEarningReports').attr('data-meses').split(',');
  let meses_qtd = $('#weeklyEarningReports').attr('data-qtd').split(',').map(Number);
  
  const weeklyEarningReportsEl = document.querySelector('#weeklyEarningReports'),
  weeklyEarningReportsConfig = {
    chart: {
      height: 200,
      parentHeightOffset: -20,
      type: 'bar',
      toolbar: {
        show: false
      }
    },
    plotOptions: {
      bar: {
        barHeight: '100%',
        columnWidth: '50%',
        startingShape: 'rounded',
        endingShape: 'rounded',
        borderRadius: 4,
        distributed: true,
        dataLabels: {
          position: 'top'
        }
      }
    },
    grid: {
      show: false,
      padding: {
        top: -30,
        bottom: 0,
        left: 0,
        right: 0
      }
    },
    colors: [
      '#05618e',
      '#05618e',
      '#05618e',
      '#05618e',
      '#05618e',
      '#05618e',
      '#05618e',
      '#05618e',
      '#05618e',
      '#05618e',
      '#05618e',
      '#05618e'
    ],
    dataLabels: {
      enabled: true,
      offsetY: 0
    },
    series: [
      {
        name: 'Pedidos',
        data: meses_qtd
      }
    ],
    legend: {
      show: false
    },
    xaxis: {
      categories: meses,
      axisBorder: {
        show: false
      },
      axisTicks: {
        show: false
      },
      labels: {
        style: {
          colors: '#1194dc',
          fontSize: '13px',
          fontFamily: 'Public Sans'
        }
      }
    },
    yaxis: {
      labels: {
        show: false
      }
    },
    tooltip: {
      enabled: false
    },
    responsive: [
      {
        breakpoint: 1025,
        options: {
          chart: {
            height: 199
          }
        }
      }
    ]
  };
  
  if (typeof weeklyEarningReportsEl !== undefined && weeklyEarningReportsEl !== null) {
    const weeklyEarningReports = new ApexCharts(weeklyEarningReportsEl, weeklyEarningReportsConfig);
    weeklyEarningReports.render();
  }

  $(document).ready(function() {
    $('#curva_abc').DataTable({
        "paging": true,
        "searching": false,
        "lengthChange": false,
        "info": false,
        "ordering": false,
        "pageLength": 6,
        "language": {
            "paginate": {
                "next": '',
                "previous": ''
            }
        }
    });
    $('#pedidos_equipe').DataTable({
      "paging": true,
      "searching": false,
      "lengthChange": false,
      "info": false,
      "ordering": false,
      "pageLength": 8,
      "language": {
          "paginate": {
              "next": '',
              "previous": ''
          }
      }
    });
    $('#pedidos_estados').DataTable({
      "paging": true,
      "searching": false,
      "lengthChange": false,
      "info": false,
      "ordering": false,
      "pageLength": 5,
      "language": {
          "paginate": {
              "next": '',
              "previous": ''
          }
      }
    });
  });

var gallery = $('#curva_abc a.img').simpleLightbox();


  // Support Tracker - Radial Bar Chart
  // --------------------------------------------------------------------
  const supportTrackerEl = document.querySelector('#supportTracker'),
    supportTrackerOptions = {
      series: [85],
      labels: ['Completed Task'],
      chart: {
        height: 360,
        type: 'radialBar'
      },
      plotOptions: {
        radialBar: {
          offsetY: 10,
          startAngle: -140,
          endAngle: 130,
          hollow: {
            size: '65%'
          },
          track: {
            background: cardColor,
            strokeWidth: '100%'
          },
          dataLabels: {
            name: {
              offsetY: -20,
              color: labelColor,
              fontSize: '13px',
              fontWeight: '400',
              fontFamily: 'Public Sans'
            },
            value: {
              offsetY: 10,
              color: headingColor,
              fontSize: '38px',
              fontWeight: '400',
              fontFamily: 'Public Sans'
            }
          }
        }
      },
      colors: [config.colors.primary],
      fill: {
        type: 'gradient',
        gradient: {
          shade: 'dark',
          shadeIntensity: 0.5,
          gradientToColors: [config.colors.primary],
          inverseColors: true,
          opacityFrom: 1,
          opacityTo: 0.6,
          stops: [30, 70, 100]
        }
      },
      stroke: {
        dashArray: 10
      },
      grid: {
        padding: {
          top: -20,
          bottom: 5
        }
      },
      states: {
        hover: {
          filter: {
            type: 'none'
          }
        },
        active: {
          filter: {
            type: 'none'
          }
        }
      },
      responsive: [
        {
          breakpoint: 1025,
          options: {
            chart: {
              height: 330
            }
          }
        },
        {
          breakpoint: 769,
          options: {
            chart: {
              height: 280
            }
          }
        }
      ]
    };
  if (typeof supportTrackerEl !== undefined && supportTrackerEl !== null) {
    const supportTracker = new ApexCharts(supportTrackerEl, supportTrackerOptions);
    supportTracker.render();
  }