import {useState, useEffect} from 'react'
import useAnalytics from './useAnalytics';
import { blue, cyan, gold, green, lime, magenta, orange, purple, red } from '@ant-design/colors';


import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend,
} from 'chart.js';
import { Bar } from 'react-chartjs-2';

ChartJS.register(
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend
);

export const options = {
  responsive: true,
  plugins: {
    legend: {
      position: 'top',
    },
    title: {
      display: true,
      text: 'Chart.js Bar Chart',
    },
  },
};

const labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July'];

export const data = {
  labels,
  datasets: [
    {
      label: 'Dataset 1',
      data: labels.map(() => Math.random()*10),
      backgroundColor: 'rgba(255, 99, 132, 0.5)',
    },
    {
      label: 'Dataset 2',
      data: labels.map(() => Math.random()*10),
      backgroundColor: 'rgba(53, 162, 235, 0.5)',
    },
  ],
};
const BarChart = () => {

    const {BookingSeatAnalyticsByDate} = useAnalytics();

    // const { registerTheme } = G2;
    // registerTheme('custom-theme', {
    //   colors10: [cyan[3], red[2], blue[5], blue[6], '#BE408C', '#BE408C'],
  
    //   // /** 20色板 */
    //   // colors20: [red[6], green[6], orange[6], '#E85285', '#BE408C', '#BE408C', '#942D93'],
    // });

    // const config = {
    //   data: BookingSeatAnalyticsByDate,
    //   xField: 'xAxis',
    //   yField: 'yAxis',
    //   seriesField: 'mockType',
    //   isGroup: true,
    //   legend: {
    //     position: 'top-left',
    //     title: {
    //         text: "Date VS Bookings",
    //         spacing: 16
    //     }
    //   },
    //   label: {
    //     position: 'middle',
    //   },
    //   xAxis: {
    //     label: {
    //       autoRotate: false,
    //       autoHide: true,
    //     },      
    //   },
    //   slider: {
    //     start: 0,
    //     end: 1,
    //   },
    //   meta: {
    //     xAxis: {
    //       alias: 'Date',
    //     },
    //     yAxis: {
    //       alias: 'Seats',
    //     },
    //   },
    // };
  
    // return <Column {...config} />;
    return <Bar options={options} data={data} />
}

export default BarChart