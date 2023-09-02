import {useState, useEffect} from 'react'
import { Column, G2 } from '@ant-design/plots';
import useAnalytics from './useAnalytics';
import { blue, cyan, gold, green, lime, magenta, orange, purple, red } from '@ant-design/colors';

const BarStackChart = () => {

    const {BookingSeatAnalyticsByDateAndStatus} = useAnalytics();

    const { registerTheme } = G2;
    registerTheme('custom-theme', {
      colors10: [cyan[3], red[2], blue[5], blue[6], '#BE408C', '#BE408C'],
  
      // /** 20色板 */
      // colors20: [red[6], green[6], orange[6], '#E85285', '#BE408C', '#BE408C', '#942D93'],
    });

    const config = {
      data: BookingSeatAnalyticsByDateAndStatus,
      isGroup: true,
      xField: 'xAxis',
      yField: 'yAxis',
      seriesField: 'type',
      legend: {
          position: 'top-left',
          title: {
              text: "Date VS Bookings with respect to Booking Statuses",
              spacing: 16
          }
      },
      label: {
        position: 'middle',
        // style: {
        //   fill: '#FFFFFF',
        //   opacity: 0.6,
        // },
        // layout: [
        //   // 柱形图数据标签位置自动调整
        //   {
        //     type: 'interval-adjust-position',
        //   }, // 数据标签防遮挡
        //   {
        //     type: 'interval-hide-overlap',
        //   }, // 数据标签文颜色自动调整
        //   {
        //     type: 'adjust-color',
        //   },
        // ],
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
      theme: 'custom-theme',
    };
  
    return <Column {...config} />;
}

export default BarStackChart