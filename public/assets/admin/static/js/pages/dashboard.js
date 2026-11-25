const profileVisitElement = document.querySelector("#chart-profile-visit");
const profileVisitLabels = profileVisitElement
  ? JSON.parse(profileVisitElement.dataset.labels || "[]")
  : [];
const profileVisitValues = profileVisitElement
  ? JSON.parse(profileVisitElement.dataset.values || "[]")
  : [];

var optionsProfileVisit = {
  annotations: {
    position: "back",
  },
  dataLabels: {
    enabled: false,
  },
  chart: {
    type: "bar",
    height: 320,
    toolbar: { show: false },
    fontFamily: "inherit",
  },
  fill: {
    opacity: 1,
  },
  plotOptions: {
    bar: {
      borderRadius: 7,
      columnWidth: "46%",
    },
  },
  series: [
    {
      name: "Penjualan",
      data: profileVisitValues,
    },
  ],
  colors: ["#435ebe"],
  xaxis: {
    categories: profileVisitLabels,
    axisBorder: { show: false },
    axisTicks: { show: false },
  },
  yaxis: {
    min: 0,
    labels: {
      formatter: (value) => `Rp ${new Intl.NumberFormat("id-ID", { notation: "compact", maximumFractionDigits: 1 }).format(value)}`,
    },
  },
  tooltip: {
    y: {
      formatter: (value) => `Rp ${new Intl.NumberFormat("id-ID").format(value)}`,
    },
  },
  grid: {
    borderColor: "#eef0f4",
    strokeDashArray: 4,
  },
}
let optionsVisitorsProfile = {
  series: [70, 30],
  labels: ["Pria", "Wanita"],
  colors: ["#435ebe", "#55c6e8"],
  chart: {
    type: "donut",
    width: "100%",
    height: "350px",
  },
  legend: {
    position: "bottom",
  },
  plotOptions: {
    pie: {
      donut: {
        size: "30%",
      },
    },
  },
}

var optionsEurope = {
  series: [
    {
      name: "Penjualan",
      data: [310, 800, 600, 430, 540, 340, 605, 805, 430, 540, 340, 605],
    },
  ],
  chart: {
    height: 80,
    type: "area",
    toolbar: {
      show: false,
    },
  },
  colors: ["#5350e9"],
  stroke: {
    width: 2,
  },
  grid: {
    show: false,
  },
  dataLabels: {
    enabled: false,
  },
  xaxis: {
    type: "datetime",
    categories: [
      "2018-09-19T00:00:00.000Z",
      "2018-09-19T01:30:00.000Z",
      "2018-09-19T02:30:00.000Z",
      "2018-09-19T03:30:00.000Z",
      "2018-09-19T04:30:00.000Z",
      "2018-09-19T05:30:00.000Z",
      "2018-09-19T06:30:00.000Z",
      "2018-09-19T07:30:00.000Z",
      "2018-09-19T08:30:00.000Z",
      "2018-09-19T09:30:00.000Z",
      "2018-09-19T10:30:00.000Z",
      "2018-09-19T11:30:00.000Z",
    ],
    axisBorder: {
      show: false,
    },
    axisTicks: {
      show: false,
    },
    labels: {
      show: false,
    },
  },
  show: false,
  yaxis: {
    labels: {
      show: false,
    },
  },
  tooltip: {
    x: {
      format: "dd/MM/yy HH:mm",
    },
  },
}

let optionsAmerica = {
  ...optionsEurope,
  colors: ["#008b75"],
}
let optionsIndia = {
  ...optionsEurope,
  colors: ["#ffc434"],
}
let optionsIndonesia = {
  ...optionsEurope,
  colors: ["#dc3545"],
}

const renderChart = (selector, options) => {
  const element = document.querySelector(selector)

  if (element) {
    new ApexCharts(element, options).render()
  }
}

renderChart("#chart-indonesia", optionsIndonesia)
renderChart("#chart-america", optionsAmerica)
renderChart("#chart-india", optionsIndia)
renderChart("#chart-europe", optionsEurope)
renderChart("#chart-profile-visit", optionsProfileVisit)
renderChart("#chart-visitors-profile", optionsVisitorsProfile)
