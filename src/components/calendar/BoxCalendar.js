import {useState} from 'react'
import dayjs from 'dayjs'
import { Badge, Calendar, DatePicker, Divider, Progress, Radio } from 'antd';
import WeekPicker from './WeekPicker';
import './BoxCalendar.scss';
import { green, gold, volcano, red, cyan, blue } from '@ant-design/colors';
import { FrownFilled, SmileTwoTone } from '@ant-design/icons';
import useWindowSize from '../../hooks/useWindowSize';
import { ProductTemplateType } from '../products/ProductTemplate.type';
import { useEffect } from 'react'
// dayjs.extend()

const BoxCalendar = ({isFrontend = true, productTemplates = ProductTemplateType.List([]), onSelect = (value) => {}}) => {

  const { size } = useWindowSize();

  const [type, setType] = useState('date');

  const todayDate = dayjs();



  useEffect(() => {
    handleDateSelect('random', todayDate);
  }, [])
  
  
  const handleDateSelectFrontend = (calendarType, value) => {
      // console.log({data})
      // console.log(data.$d.toISOString())
      const date = value.format("YYYY-MM-DD");
      const day = value.format("ddd").toLowerCase()
      let selectedProductTemplate = null;
      productTemplates.forEach((pt, pti )=> {
        if(pt.id){
          const dateOrDay = pt.key.split('_')[1];
          // console.log({dateOrDay, date, day})
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
      // console.log({diabledDate: date, isBefore: value.isBefore(todayDate.add(1, 'day')), isValid})
      return true;
    }
    return false;
  };

  const getPercentData = (value) => {
    // console.log({value, date: value.format("YYYY-MM-DD")})
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
              // console.log({c})
              total += c.available_slots + c.booked;
              booked += c.booked;
            })
          })
          percent = Math.round((booked*100)/total);
          // console.log({total, booked, percent, pti, dateOrDay})
          return percent;
        }
      }
    })
    // console.log({percent2: percent})
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
        // console.log({dateOrDay, date, day})
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
          // <><span style={{ marginRight: 5, width: 14, height: 14, borderRadius: '50%', background: red[4]}}></span> <span>Not Available</span></>
          // <FrownFilled style={{color: red[2], fontSize: 36}} />
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
              // strokeColor={{
              //   '0%':  green[3],
              //   '25%':  green[5],
              //   '50%':  gold[5],
              //   '75%':  volcano[5],
              //   '100%': red[5],
              // }}
              style={{
                fontSize: 10
              }}
            />
        }
      </>
    );
  };

  // return <Calendar mode="month" cellRender={cellRender} onSelect={handleDateSelect} />;
  return (
      <>
        {
          !isFrontend
          ?
          <Divider style={{borderBlockStart: '0 rgba(5, 5, 5, 0)'}}>
            <Radio.Group buttonStyle="solid" value={type} onChange={(e) => setType(e.target.value)}>
              <Radio.Button value="date">DATE</Radio.Button>
              <Radio.Button value="week">WEEK</Radio.Button>
              {/* <Radio.Button value="month">Month</Radio.Button> */}
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

