$('.line').each(function (index, element) {
        var ctx = element.getContext('2d');
        var data = {
            datasets: [{
                data: [10, 20, 30],
                backgroundColor: [
                    '#3c8dbc',
                    '#f56954',
                    '#f39c12',
                ],
            }],
            labels: [
                'Request',
                'Layanan',
                'Problem'
            ]
        };
        var myDoughnutChart = new Chart(ctx, {
            type: 'line',
            data: data,
            options: {
                maintainAspectRatio: false,
                legend: {
                    position: 'right',
                    labels: {
                        boxWidth: 12
                    }
                }
            }
        });
        

    });
    