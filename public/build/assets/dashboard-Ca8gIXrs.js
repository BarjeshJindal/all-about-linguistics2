import { u as ApexCharts } from "./apexcharts.esm-BofaT7g3.js";

// Default fallback colors
const fallbackColors = ["#727cf5", "#0acf97", "#fa5c7c", "#ffbc00"];

// -------------------------------------
// Total Orders Radial Bar Chart
// -------------------------------------
const $totalOrdersChart = $("#total-orders-chart");
const totalOrdersColors = $totalOrdersChart.data("colors")?.split(",") || fallbackColors;

const totalOrdersCompleted = parseInt($totalOrdersChart.data("completed")) || 0;
const totalOrdersTotal = parseInt($totalOrdersChart.data("total")) || 1;
const totalOrdersPercentage = Math.round((totalOrdersCompleted / totalOrdersTotal) * 100);

const totalOrdersOptions = {
    series: [totalOrdersPercentage],
    chart: {
        type: "radialBar",
        height: 81,
        width: 81,
        sparkline: { enabled: false }
    },
    plotOptions: {
        radialBar: {
            offsetY: 0,
            hollow: { margin: 0, size: "50%" },
            dataLabels: {
                name: { show: false },
                value: {
                    offsetY: 5,
                    fontSize: "14px",
                    fontWeight: "600",
                    formatter: function (val) {
                        return val + "%";
                    }
                }
            }
        }
    },
    grid: { padding: { top: -18, bottom: -20, left: -20, right: -20 } },
    colors: totalOrdersColors
};

new ApexCharts(document.querySelector("#total-orders-chart"), totalOrdersOptions).render();


// -------------------------------------
// New Users Radial Bar Chart
// -------------------------------------
const $newUsersChart = $("#new-users-chart");
const newUsersColors = $newUsersChart.data("colors")?.split(",") || fallbackColors;

const newUsersCompleted = parseInt($newUsersChart.data("completed")) || 0;
const newUsersTotal = parseInt($newUsersChart.data("total")) || 1;
const newUsersPercentage = Math.round((newUsersCompleted / newUsersTotal) * 100);

const newUsersOptions = {
    series: [newUsersPercentage],
    chart: {
        type: "radialBar",
        height: 81,
        width: 81,
        sparkline: { enabled: false }
    },
    plotOptions: {
        radialBar: {
            offsetY: 0,
            hollow: { margin: 0, size: "50%" },
            dataLabels: {
                name: { show: false },
                value: {
                    offsetY: 5,
                    fontSize: "14px",
                    fontWeight: "600",
                    formatter: function (val) {
                        return val + "%";
                    }
                }
            }
        }
    },
    grid: { padding: { top: -18, bottom: -20, left: -20, right: -20 } },
    colors: newUsersColors
};

new ApexCharts(document.querySelector("#new-users-chart"), newUsersOptions).render();





// Fallback default colors
const $mockTestChart = $("#mock-test-chart");
const mockTestColors = $mockTestChart.data("colors")?.split(",") || fallbackColors;
const mockTestPercentage = parseFloat($mockTestChart.data("percentage")) || 0;

const mockTestOptions = {
    series: [Math.round(mockTestPercentage)],
    chart: {
        type: "radialBar",
        height: 81,
        width: 81,
        sparkline: { enabled: false }
    },
    plotOptions: {
        radialBar: {
            offsetY: 0,
            hollow: { margin: 0, size: "50%" },
            dataLabels: {
                name: { show: false },
                value: {
                    offsetY: 5,
                    fontSize: "14px",
                    fontWeight: "600",
                    formatter: function (val) {
                        return val + "%";
                    }
                }
            }
        }
    },
    grid: { padding: { top: -18, bottom: -20, left: -20, right: -20 } },
    colors: mockTestColors
};

new ApexCharts(document.querySelector("#mock-test-chart"), mockTestOptions).render();




// -------------------------------------
// Data Visits Donut Chart
// -------------------------------------
let dataVisitsColors = $("#data-visits-chart").data("colors");
defaultColors = ["#5b69bc", "#35b8e0", "#10c469", "#fa5c7c", "#e3eaef"];
if (dataVisitsColors) {
    defaultColors = dataVisitsColors.split(",");
}

let dataVisitsOptions = {
    chart: { height: 277, type: "donut" },
    series: [65, 14, 10, 45],
    legend: {
        show: true,
        position: "bottom",
        horizontalAlign: "center",
        verticalAlign: "middle",
        floating: false,
        fontSize: "14px",
        offsetX: 0,
        offsetY: 7
    },
    labels: ["Direct", "Social", "Marketing", "Affiliates"],
    colors: defaultColors,
    stroke: { show: false }
};

new ApexCharts(document.querySelector("#data-visits-chart"), dataVisitsOptions).render();


// -------------------------------------
// Statistics Line Chart
// -------------------------------------
let statisticsColors = $("#statistics-chart").data("colors");
defaultColors = ["#5b69bc", "#10c469", "#fa5c7c", "#f9c851"];
if (statisticsColors) {
    defaultColors = statisticsColors.split(",");
}

let statisticsOptions = {
    series: [{
        name: "Open Campaign",
        type: "bar",
        data: [89.25, 98.58, 68.74, 108.87, 77.54, 84.03, 51.24]
    }],
    chart: {
        height: 301,
        type: "line",
        toolbar: { show: false }
    },
    stroke: { width: 0, curve: "smooth" },
    plotOptions: {
        bar: {
            columnWidth: "20%",
            barHeight: "70%",
            borderRadius: 5
        }
    },
    xaxis: { categories: ["2019", "2020", "2021", "2022", "2023", "2024", "2025"] },
    colors: defaultColors
};

new ApexCharts(document.querySelector("#statistics-chart"), statisticsOptions).render();


// -------------------------------------
// Revenue Line Chart
// -------------------------------------
let revenueColors = $("#revenue-chart").data("colors");
defaultColors = ["#5b69bc", "#10c469", "#fa5c7c", "#f9c851"];
if (revenueColors) {
    defaultColors = revenueColors.split(",");
}

let revenueOptions = {
    series: [
        { name: "Total Income", data: [82, 85, 70, 90, 75, 78, 65, 50, 72, 60, 80, 70] },
        { name: "Total Expenses", data: [30, 32, 40, 35, 30, 36, 37, 28, 34, 42, 38, 30] }
    ],
    chart: {
        height: 299,
        type: "line",
        zoom: { enabled: false },
        toolbar: { show: false }
    },
    stroke: { width: 3, curve: "straight" },
    dataLabels: { enabled: false },
    xaxis: { categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"] },
    colors: defaultColors,
    tooltip: {
        shared: true,
        y: [{
            formatter: function (val) {
                return "$" + val.toFixed(2) + "k";
            }
        }, {
            formatter: function (val) {
                return "$" + val.toFixed(2) + "k";
            }
        }]
    }
};

new ApexCharts(document.querySelector("#revenue-chart"), revenueOptions).render();
