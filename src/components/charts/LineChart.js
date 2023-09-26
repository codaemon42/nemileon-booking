import { blue, cyan, red } from '@ant-design/colors';
import { Line, G2 } from '@ant-design/plots';
import useAnalytics from './useAnalytics';

const LineChart = () => {

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
      isStack: true,
      xField: 'xAxis',
      yField: 'yAxis',
      seriesField: 'type',
      label: {
        position: 'middle',
        style: {
          fill: '#FFFFFF',
          opacity: 0.6,
        },
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
  
    return <Line {...config} />;
}

export default LineChart