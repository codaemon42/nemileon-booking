import {useState} from 'react'
import dayjs from 'dayjs'
import { Badge, Calendar, DatePicker, Divider, Radio } from 'antd';
import WeekPicker from './WeekPicker';
import './BoxCalendar.scss';

const BoxCalendar = ({isFrontend = true, onSelect = (value) => {}}) => {

  const [type, setType] = useState('week');
  
  const handleDateSelect = (calendarType, value) => {
      // console.log({data})
      // console.log(data.$d.toISOString())
      onSelect(value.$d.toISOString().split("T")[0])


  }

  const getListData = (value) => {
    let listData;
    switch (value.date()) {
      case 8:
        listData = [
          { type: 'error', content: 'This is warning event.' },
        ];
        break;
      case 10:
        listData = [
          { type: 'warning', content: 'This is warning event.' },
        ];
        break;
      case 15:
        listData = [
          { type: 'success', content: 'This is very long usual event。。....' },
        ];
        break;
      default:
    }
    return listData || [{ type: 'error', content: 'This is warning event.' }];
  };

  const dateCellRender = (value) => {
    const listData = getListData(value);
    return (
      <ul className="events">
        {listData.map((item) => (
          <li key={item.content} onClick={()=> console.log(`date selected`, {value})}>
            {/* <Badge status={item.type} /> */}
            <div >{value.format("DD")}</div>
          </li>
        ))}
      </ul>
    );
  };

  // return <Calendar mode="month" cellRender={cellRender} onSelect={handleDateSelect} />;
  return (
      <>
        {
          !isFrontend
          ?
          <Divider style={{borderBlockStart: '0 rgba(5, 5, 5, 0)'}}>
            <Radio.Group value={type} onChange={(e) => setType(e.target.value)}>
              <Radio.Button value="date">DATE</Radio.Button>
              <Radio.Button value="week">WEEK</Radio.Button>
              <Radio.Button value="month">Month</Radio.Button>
            </Radio.Group>
          </Divider>
          : <></>
        }
        {
          isFrontend
          ? <Calendar className='onsbks-round-box' fullscreen={false} mode='month' dateFullCellRender={dateCellRender}  onSelect={(dateObj) => handleDateSelect('random', dateObj)} /> :
          type === 'date'
          ? <Calendar className='onsbks-round-box' fullscreen={true} mode='month' onSelect={(dateObj) => handleDateSelect('date', dateObj)} /> : 
          type === 'month'
          ? <Calendar className='onsbks-round-box' fullscreen={true} mode='year' onSelect={(dateObj) => handleDateSelect('month', dateObj)} />
          : <WeekPicker  />

        }
      </>
  );
};

export default BoxCalendar

