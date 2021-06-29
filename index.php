<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
          integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.4.0/dist/chart.min.js"></script>
    <title>Погода в выбранном городе</title>
</head>
<body>
<div class="container-fluid">

    <div class="input-group mb-4 mt-4">
        <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">Адрес:</span>
        </div>
        <input type="text" id="address-input" class="form-control" placeholder="г. Липецк, ЛГТУ"
               aria-describedby="basic-addon1">
        <div class="input-group-append">
            <button type="button" id="btn-send-request" class="input-group-text btn btn-success btn-block">Получить
                погоду
            </button>
        </div>
    </div>

    <div class="text-center" id="max-temp-day">
        Самый теплый день:
    </div>

    <div class="mr-5 ml-5 mt-2">
        <canvas id="chart"></canvas>
    </div>

    <script>
        var arrayMaxIndex = function (array) {
            return array.indexOf(Math.max.apply(null, array));
        };

        let chart = document.getElementById('chart').getContext('2d');
        let massPopChart = new Chart(chart, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Реальное значение',
                    fill: true,
                    data: [],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                    ],
                    borderWidth: 1
                },
                    {
                        label: 'Ощущается как',
                        fill: true,
                        data: [],
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.2)'
                        ],
                        borderWidth: 1
                    }]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Погода в выбранном городе'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) {
                                return value + '℃';
                            }
                        }
                    }
                }
            }
        });

        $(document).on("click", "#btn-send-request", function () {
            const query = $("#address-input").val().trim();
            if (query.length === 0) {
                return false;
            }

            $.ajax({
                url: 'src/handler.php',
                type: 'POST',
                cache: false,
                data: {
                    'address': query
                },
                dataType: 'json',
                beforeSend: function () {
                    $("#btn-send-request").prop('disabled', true);
                },
                success: function (data) {
                    let errorMsg = data.msg;
                    if (errorMsg) {
                        alert(errorMsg);
                    }
                    let weatherData = data.weather;

                    let chartTitle = data.address;
                    let xlabesValues = [];
                    let realTempValues = [];
                    let feelsLikeTempValues = [];

                    weatherData.forEach(function (item) {
                        xlabesValues.push(item.date);
                        realTempValues.push(item.real_temp);
                        feelsLikeTempValues.push(item.feels_like_temp);
                    });

                    let maxIdx = arrayMaxIndex(feelsLikeTempValues);

                    massPopChart.data.labels = xlabesValues;
                    massPopChart.data.datasets[0].data = realTempValues;
                    massPopChart.data.datasets[1].data = feelsLikeTempValues;
                    massPopChart.options.plugins.title.text = 'Погода в ' + chartTitle;

                    let maxTempDay = $("#max-temp-day");
                    let maxTempDayLabel = `Самый теплый день: ${xlabesValues[maxIdx]}`;
                    let maxTempDayRealTemp = `Реальная температура: ${realTempValues[maxIdx]}`;
                    let maxTempDayFeelsTemp = `Ощущается как: ${feelsLikeTempValues[maxIdx]}`;
                    maxTempDay.empty();
                    maxTempDay.append(`<p class='text-center'><strong>${maxTempDayLabel}</strong></p>`);
                    maxTempDay.append(`<p class='text-center'>${maxTempDayRealTemp}</p>`);
                    maxTempDay.append(`<p class='text-center'>${maxTempDayFeelsTemp}</p>`);

                    massPopChart.update();

                    $("#btn-send-request").prop('disabled', false);
                },
                error: function (data) {
                    $("#btn-send-request").prop('disabled', false);
                }
            });
        });
    </script>


</div>
</body>
</html>