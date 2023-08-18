import { Calendar, Col, Radio, Row, Select, Typography } from 'antd';
import dayjs from 'dayjs';
import 'dayjs/locale/zh-cn';
import dayLocaleData from 'dayjs/plugin/localeData';
dayjs.extend(dayLocaleData);

const BoxCalendarCustomHeader = ({ value, type, onChange, onTypeChange }) => {
    let current = value.clone();
    let currentD = value.clone();
    const localeData = value.localeData();

    // week config
    const startD = 0;
    const endD = 7;
    const dayOptions = [];

    const days = [];
    for (let i = startD; i < endD; i++) {
      currentD = currentD.day(i);
      days.push(localeData.weekdaysShort(currentD));
    }
    for (let i = startD; i < endD; i++) {
        dayOptions.push(
            <Select.Option key={i} value={i} className="my-month-item">
                {days[i]}
            </Select.Option>,
        );
    }
    console.log({currentD, localeData, days, dayOptions, type})


    // month config
    const start = 0;
    const end = 12;
    const monthOptions = [];

    const months = [];
    for (let i = 0; i < 12; i++) {
      current = current.month(i);
      months.push(localeData.monthsShort(current));
    }
    for (let i = start; i < end; i++) {
      monthOptions.push(
        <Select.Option key={i} value={i} className="month-item">
          {months[i]}
        </Select.Option>,
      );
    }
    console.log({current, localeData, months, monthOptions})

    const year = value.year();
    const month = value.month();

    //week
    const day = value.day();

    const options = [];
    for (let i = year - 10; i < year + 10; i += 1) {
      options.push(
        <Select.Option key={i} value={i} className="year-item">
          {i}
        </Select.Option>,
      );
    }
    return (
      <div
        style={{
          padding: 8,
        }}
      >
        <Typography.Title level={4}>Custom header</Typography.Title>
        <Row gutter={8}>
          <Col>
            <Radio.Group
              size="small"
              onChange={(e) => onTypeChange(e.target.value)}
              value={type}
            >
              <Radio.Button value="month">Month</Radio.Button>
              <Radio.Button value="year">Year</Radio.Button>
              // week
              <Radio.Button value="day">week</Radio.Button>
            </Radio.Group>
          </Col>
          <Col>
            <Select
              size="small"
              dropdownMatchSelectWidth={false}
              className="my-year-select"
              value={year}
              onChange={(newYear) => {
                const now = value.clone().year(newYear);
                onChange(now);
              }}
            >
              {options}
            </Select>
          </Col>
          <Col>
            <Select
              size="small"
              dropdownMatchSelectWidth={false}
              value={month}
              onChange={(newMonth) => {
                const now = value.clone().month(newMonth);
                onChange(now);
              }}
            >
              {monthOptions}
            </Select>
          </Col>
          <Col>
            <Select
              size="small"
              dropdownMatchSelectWidth={false}
              value={day}
              onChange={(newDay) => {
                const now = value.clone().day(newDay);
                console.log({now})
                onChange(now);
              }}
            >
              {dayOptions}
            </Select>
          </Col>
        </Row>
      </div>
    );
}

export default BoxCalendarCustomHeader