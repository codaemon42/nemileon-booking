import { blue, cyan, gold, green, red, volcano } from '@ant-design/colors';
import { Calendar, Divider, Progress, Radio } from 'antd';
import dayjs from 'dayjs';
import { useEffect, useState } from 'react';
import useWindowSize from '../../hooks/useWindowSize';
import { ProductTemplateType } from '../products/ProductTemplate.type';
import './BoxCalendar.scss';
import WeekPicker from './WeekPicker';

const BoxCalendar = ({isFrontend = true, productTemplates = ProductTemplateType.List([]), onSelect = (value) => {}}) => {

  const { size } = useWindowSize();

  const [type, setType] = useState('date');

  const todayDate = dayjs();



  useEffect(() => {
    handleDateSelect('random', todayDate);
  }, [])
  
  
  const handleDateSelectFrontend = (calendarType, value) => {
      const date = value.format("YYYY-MM-DD");
      const day = value.format("ddd").toLowerCase()
      let selectedProductTemplate = null;
      productTemplates.forEach((pt, pti )=> {
        if(pt.id){
          const dateOrDay = pt.key.split('_')[1];
          if(dateOrDay ===  date || dateOrDay.toLowerCase() === day){
            selectedProductTemplate = {...pt};
          }
        }
      })
      onSelect({date: value.format("YYYY-MM-DD"), selectedProductTemplate, calendarType})
  }

  const handleDateSelect = (calendarType, value) => {
    let selectedProductTemplate = null;
    if(calendarType === 'week'){
      onSelect({date: value.join(","), selectedProductTemplate, calendarType})
    } else if( calendarType === 'month' ){
      onSelect({date: value.format("YYYY-MM-DD"), selectedProductTemplate, calendarType})
    } else if( calendarType === 'year'){
      onSelect({date: value.format('MMM'), selectedProductTemplate, calendarType})
    }
    
}

  const checkDisabledDate = (value) => {
    const date = value.format("YYYY-MM-DD");
    const day = value.format("ddd").toLowerCase()
    let isValid = false;
    productTemplates.forEach((pt, pti )=> {
      if(pt.id){
        const dateOrDay = pt.key.split('_')[1];
        if(dateOrDay ===  date || dateOrDay.toLowerCase() === day){
          isValid = true;
        }
      }
    })
    if(value.isBefore(todayDate.subtract(1, 'day')) || !isValid) {
      return true;
    }
    return false;
  };

  const getPercentData = (value) => {
    const date = value.format("YYYY-MM-DD");
    const day = value.format("ddd").toLowerCase()
    let percent = 0;
    productTemplates.forEach((pt, pti )=> {
      if(pt.id){
        const dateOrDay = pt.key.split('_')[1];
        if(dateOrDay ===  date || dateOrDay.toLowerCase() === day){
          // console.log({dateOrDay})
          let total = 0;
          let booked = 0;
          pt.template.rows.forEach(t => {
            t.cols.forEach(c => {
              total += c.available_slots + c.booked;
              booked += c.booked;
            })
          })
          percent = Math.round((booked*100)/total);
          return percent;
        }
      }
    })

    return percent;
  };

  const dateCellRender = (value) => {
    const isDisabled = value.add(1, 'day').isBefore(todayDate);
    const date = value.format("YYYY-MM-DD");
    const day = value.format("ddd").toLowerCase()
    let isValid = false;
    productTemplates.forEach((pt, pti )=> {
      if(pt.id){
        const dateOrDay = pt.key.split('_')[1];

        if(dateOrDay ===  date || dateOrDay.toLowerCase() === day){
          isValid = true;
        }
      }
    })
    const getPercent = () => {
      if(isDisabled){
        return 0;
      } else {
        return getPercentData(value)
      }
    }

    return (
      <>
        {
          isDisabled || !isValid
          ? <></>
          : 
          size === 'lg' || size === 'md' 
          ? <Progress 
              percent={getPercent()} 
              steps={10} 
              showInfo={true}
              size={[5, 30]} 
              strokeColor={[blue[2], cyan[2], green[2], green[3], gold[4], gold[5], volcano[3], volcano[4], red[5], red[6]]} 
              style={{
                fontSize: 10
              }}
            />
          : <Progress 
              percent={getPercent()} 
              // type="circle"
              steps={5} 
              showInfo={false}
              size={[1, 10]} 
              strokeColor={[green[3], green[5], gold[5], volcano[5], red[5]]} 
              style={{
                fontSize: 10
              }}
            />
        }
      </>
    );
  };

  const getDefaultSelectedDate = () => {
    if(productTemplates && productTemplates.length > 0) {
      const dateOrDay = productTemplates[0].key.split('_')[1];
      onSelect({
        date: dateOrDay.format("YYYY-MM-DD"), 
        selectedProductTemplate: {...productTemplates[0], key: dateOrDay.format("YYYY-MM-DD")},
        calendarType: 'random'
      });
    }
  }

  return (
      <>
        {
          !isFrontend
          ?
          <Divider style={{borderBlockStart: '0 rgba(5, 5, 5, 0)'}}>
            <Radio.Group buttonStyle="solid" value={type} onChange={(e) => setType(e.target.value)}>
              <Radio.Button value="date">DATE</Radio.Button>
              <Radio.Button value="week">WEEK</Radio.Button>
            </Radio.Group>
          </Divider>
          : <></>
        }
        {
          isFrontend
          ? <Calendar 
              className='onsbks-round-box' 
              fullscreen={true}
              mode='month'
              dateCellRender={dateCellRender}  
              onSelect={(dateObj) => handleDateSelectFrontend('random', dateObj)}
              disabledDate={(date)=> checkDisabledDate(date)}
            /> :
          type === 'date'
          ? <Calendar className='onsbks-round-box' fullscreen={true} mode='month' onSelect={(dateObj) => handleDateSelect('date', dateObj)} /> : 
          type === 'month'
          ? <Calendar className='onsbks-round-box' fullscreen={true} mode='year' onSelect={(dateObj) => handleDateSelect('month', dateObj)} />
          : <WeekPicker onSelect={(days)=>handleDateSelect('week', days)}  />

        }
      </>
  );
};

export default BoxCalendar

