import {useState, useEffect} from 'react'
import { Column, G2 } from '@ant-design/plots';
import useAnalytics from './useAnalytics';
import { blue, cyan, gold, green, lime, magenta, orange, purple, red } from '@ant-design/colors';

const BarChart = () => {

    const {BookingSeatAnalyticsByDate} = useAnalytics();

    const { registerTheme } = G2;
    registerTheme('custom-theme', {
      colors10: [cyan[3], red[2], blue[5], blue[6], '#BE408C', '#BE408C'],
  
      // /** 20色板 */
      // colors20: [red[6], green[6], orange[6], '#E85285', '#BE408C', '#BE408C', '#942D93'],
    });

    const config = {
      data: BookingSeatAnalyticsByDate,
      xField: 'xAxis',
      yField: 'yAxis',
      seriesField: 'mockType',
      isGroup: true,
      legend: {
        position: 'top-left',
        title: {
            text: "Date VS Bookings",
            spacing: 16
        }
      },
      label: {
        position: 'middle',
      },
      xAxis: {
        label: {
          autoRotate: false,
          autoHide: true,
        },      
      },
      slider: {
        start: 0,
        end: 1,
      },
      meta: {
        xAxis: {
          alias: 'Date',
        },
        yAxis: {
          alias: 'Seats',
        },
      },
      // theme: 'custom-theme',
    };
  
    return <Column {...config} />;
}

export default BarChart