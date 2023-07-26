import { useState, useEffect } from 'react';
import { Badge, Col, FloatButton, Input, Row, Slider } from 'antd';
import { CommentOutlined, CustomerServiceOutlined, EditOutlined, EllipsisOutlined, MinusOutlined, PlusOutlined } from '@ant-design/icons';

import { Slot } from './types/Slot.type';
import { SlotCol } from './types/SlotCol.type';
import { SlotRow } from './types/SlotRow.type';
import EditInput from '../form-feilds/EditInput';
import SlotElementEditorDrawer from './SlotElementEditorDrawer';
import { SlotTemplateType } from './types/SlotTemplateType.type';


const gutters = {};
const vgutters = {};
const colCounts = {};
const rowCounts = {};
[8, 16, 24, 32, 40, 48, 56, 64].forEach((value, i) => {
  gutters[i] = value;
});
[8, 16, 24, 32, 40, 48].forEach((value, i) => {
  vgutters[i] = value;
});
[1, 2, 3, 5, 7, 11].forEach((value, i) => {
  colCounts[i] = value;
});

[1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20].forEach((value, i) => {
    rowCounts[i] = value;
});

const SlotBuilder = ({initialTemplate = new SlotTemplateType(), onSlotChange, onNameChange}) => {

  const [gutterKey, setGutterKey] = useState(1);
  const [vgutterKey, setVgutterKey] = useState(1);
  const [colCountKey, setColCountKey] = useState(2);
  const [rowCountKey, setRowCountKey] = useState(1);

  const [slot, setSlot] = useState(initialTemplate.template);
  const [Name, setName] = useState(initialTemplate.name)

  const [openSlotElDrawer, setopenSlotElDrawer] = useState(false);


  useEffect(() => {
    console.log({initialTemplate})
    if(initialTemplate.template.rows.length <= 0){
      initializer();
    }
  }, [])

  useEffect(() => {
    onSlotChange(slot);
  }, [slot])

  useEffect(() => {
    onNameChange(Name);
  }, [Name])

  const initializer = () => {

      const newSlot = new Slot({
          gutter: 12,
          vGutter: 12,
          product_id: '1',
          allowedBookingPerPerson: 4,
          total: 0,
          rows: SlotRow.List([
              {
                  header: '9 - 10 AM',
                  description: 'some description',
                  showToolTip: false,
                  cols: SlotCol.List([
                      {
                          product_id: '1',
                          content: 'Content',
                          show: true,
                          available_slots: 2,
                          checked: false,
                          booked: 0,
                          expires_in: null
                      },
                      {
                          product_id: '1',
                          content: 'Content',
                          show: true,
                          available_slots: 3,
                          checked: false,
                          booked: 0,
                          expires_in: null
                      },
                      {
                          product_id: '1',
                          content: 'Content',
                          show: true,
                          available_slots: 3,
                          checked: false,
                          booked: 0,
                          expires_in: null
                      }
                  ])
              },
              {
                  header: '10 - 12 AM',
                  description: 'some description',
                  showToolTip: false,
                  cols: SlotCol.List([
                      {
                          product_id: '1',
                          content: 'Content',
                          show: true,
                          available_slots: 5,
                          checked: false,
                          booked: 0,
                          expires_in: null
                      },
                      {
                          product_id: '1',
                          content: 'Content',
                          show: true,
                          available_slots: 2,
                          checked: false,
                          booked: 0,
                          expires_in: null
                      },
                      {
                          product_id: '1',
                          content: 'Content',
                          show: true,
                          available_slots: 3,
                          checked: false,
                          booked: 0,
                          expires_in: null
                      }
                  ])
              }
          ])
      });

      setSlot(newSlot);
  }


  const setSlotColClass = (addtionalClass = '', colData = new SlotCol()) => {
    let className = addtionalClass;
    if(!colData.show) className += ' hidden-slot ';
    if(!colData.available_slots) className += ' not-available-slot ';
    return className;
  }

  const handleBooking = (rowInd, colInd, value) => {
    const newSlot = {...slot};
    if(value > 0){ // increased
        if(newSlot.rows[rowInd].cols[colInd].booked < newSlot.rows[rowInd].cols[colInd].available_slots && newSlot.total < newSlot.allowedBookingPerPerson){
            newSlot.rows[rowInd].cols[colInd].booked += 1;
            newSlot.rows[rowInd].cols[colInd].expires_in = Date.now();
            newSlot.total += 1;
        }else if(newSlot.total === newSlot.allowedBookingPerPerson){
            message.warning(`You have reached maximum booking`);
        } else {
            message.warning(`No more seats available for booking`);
        }
    } else { // decreased
        newSlot.rows[rowInd].cols[colInd].booked -= 1;
        if(newSlot.rows[rowInd].cols[colInd].booked < 0){
            newSlot.rows[rowInd].cols[colInd].booked = 0;
            newSlot.rows[rowInd].cols[colInd].expires_in = null;
            newSlot.total -= 1;
        }
    }
    setSlot(newSlot);
  }

  const changeRowCount = (position) => {
    setRowCountKey(position);
    const newSlot = {...slot};
    if(newSlot.rows.length > 0){
      
      if(rowCounts[position] > newSlot.rows.length){ // increased
        for(let i = newSlot.rows.length; i<rowCounts[position]; i++){
          const newRow = new SlotRow({
            header: '9 - 10 AM',
            description: `${i}`,
            showToolTip: false,
            cols: []
          });

          for(let j = 0; j < newSlot.rows[0].cols.length; j++){
            const newCol = new SlotCol({
              product_id: `${i}__${rowCounts[position]}`,
              content: 'Content',
              show: true,
              available_slots: 2,
              checked: false,
              booked: 0,
              expires_in: null
            });
            newRow.cols.push(newCol);
          }
          
          newSlot.rows.push(newRow);
        }
      } else if(rowCounts[position] < newSlot.rows.length){ // descreased
        newSlot.rows.splice(rowCounts[position]);
      } else {
        // do nothing here...
      }
    }

    setSlot(newSlot);
  }

  const changeColCount = (position) => {
    setColCountKey(position);
    const newCol = new SlotCol({
        product_id: '1',
        content: 'Content',
        show: true,
        available_slots: 5,
        checked: false,
        booked: 0,
        expires_in: null
    });

    const newSlot = {...slot};
    newSlot.rows.forEach((row, index) => {
      if(colCounts[position] > newSlot.rows[index].cols.length){ // increased
        for(let i = newSlot.rows[index].cols.length; i < colCounts[position]; i++ ){
          newSlot.rows[index].cols.push(newCol);
        }
      } else if(colCounts[position] < newSlot.rows[index].cols.length){ // decreased
        newSlot.rows[index].cols.splice(colCounts[position]);
      } else {
        // do nothing here...
      }
    })
    console.log({newSlot, position, colCounts: colCounts[position]})
    setSlot(newSlot);

  }

  const changeGutter = (position) => {
      setGutterKey(position);
      const newSlot = {...slot};
      newSlot.gutter = gutters[position];
      setSlot(newSlot);
  }

  const changeVGutter = (position) => {
    setVgutterKey(position);
    const newSlot = {...slot};
    newSlot.vGutter = vgutters[position];
    setSlot(newSlot);
  }

  const changeHeader = (value, rowInd) => {
    const newSlot = {...slot};
      newSlot.rows[rowInd].header = value;
      setSlot(newSlot);
      console.log({newSlot})
  }

  const slotElementChangeHandler = (formValues, rowInd, colInd) => {
    console.log({formValues, rowInd, colInd});
    const newSlot = {...slot};
    if(formValues.show_all){
      newSlot.rows.forEach(row => {
        row.cols.forEach(col => {
          col.show = formValues.show;
        })
      })
    } else{
      newSlot.rows[rowInd].cols[colInd].show = formValues.show;
    }

    if(formValues.content_all){
      newSlot.rows.forEach(row => {
        row.cols.forEach(col => {
          col.content = formValues.content;
        })
      })
    } else{
      newSlot.rows[rowInd].cols[colInd].content = formValues.content;
    }

    if(formValues.available_slots_all){
      newSlot.rows.forEach(row => {
        row.cols.forEach(col => {
          col.available_slots = formValues.available_slots;
        })
      })
    } else{
      newSlot.rows[rowInd].cols[colInd].available_slots = formValues.available_slots;
    }
    

    console.log({newSlot})
    setSlot(newSlot);
  }

  const onNameInputChange = (event) => {
    onNameChange(event.target.value);
    setName(event.target.value)
  }

  return (
    <>
      <Row >
        <Col span={8} xs={24} xl={8} >
          <span>Name of the template: </span>
          <div
            style={{
              width: '90%',
              marginTop: 5,
              marginBottom: 15,
            }}
          >
            <Input
              placeholder='Enter the Name'
              onChange={onNameInputChange}
              value={Name}
            />
          </div>
          <span>Horizontal Gutter (px): </span>
          <div
            style={{
              width: '90%',
            }}
          >
            <Slider
              min={0}
              max={Object.keys(gutters).length - 1}
              value={gutterKey}
              onChange={changeGutter}
              marks={gutters}
              step={null}
              tooltip={{
                formatter: (value) => gutters[value],
              }}
            />
          </div>
          <span>Vertical Gutter (px): </span>
          <div
            style={{
              width: '90%',
            }}
          >
            <Slider
              min={0}
              max={Object.keys(vgutters).length - 1}
              value={vgutterKey}
              onChange={changeVGutter}
              marks={vgutters}
              step={null}
              tooltip={{
                formatter: (value) => vgutters[value],
              }}
            />
          </div>
          <span>Column Count:</span>
          <div
            style={{
              width: '90%',
              marginBottom: 48,
            }}
          >
            <Slider
              min={0}
              max={Object.keys(colCounts).length - 1}
              value={colCountKey}
              onChange={changeColCount}
              marks={colCounts}
              step={null}
              tooltip={{
                formatter: (value) => colCounts[value],
              }}
            />
          </div>

          <span>Row Count:</span>
          <div
            style={{
              width: '90%',
              marginBottom: 48,
            }}
          >
            <Slider
              min={0}
              max={Object.keys(rowCounts).length - 1}
              value={rowCountKey}
              onChange={changeRowCount}
              marks={rowCounts}
              step={null}
              tooltip={{
                formatter: (value) => rowCounts[value],
              }}
            />
          </div>
        </Col>
        <Col span={16} xs={22} xl={16} >
          <Row gutter={[slot.gutter, slot.vGutter]} >
                {slot.rows.map((rowData, rowInd) => (
                    <>
                        <Col key={`${rowInd}`} span={24 / (rowData.cols.length+1)} >
                            <div className={'playground_header'} >
                              {/* <Input defaultValue={rowData.header} onChange={(event)=>changeHeader(event, rowInd)} /> */}
                              <EditInput defaultValue={rowData.header} onChangeText={(value)=>changeHeader(value, rowInd)} />
                            </div>
                        </Col>
                        {
                        rowData.cols.map((colData, colInd) =>(
                            <Col key={`${rowInd}__${colInd}`} span={24 / (rowData.cols.length+1)} >
                                <Badge className='onsbks_counter' showZero={false} count={colData.booked}>
                                    <div className={setSlotColClass('playground_div', colData)} >
                                        <div >
                                          <div>

                                            {colData.content}
                                            <div >Seats : {colData.available_slots}</div>
                                            {/* <div >Show : {colData.show ? 'yes' : 'no'}</div> */}
                                            {/* <SlotElementEditorDrawer open={openSlotElDrawer} /> */}
                                          </div>
                                        </div>
                                        
                                        {
                                            colData.show && colData.available_slots ?
                                            <SlotElementEditorDrawer 
                                              key={`${rowInd}___${colInd}__${colData.available_slots}__${colData.show}`}
                                              slotCol={colData} 
                                              style={{
                                                right: 0,
                                                top: 0,
                                                position: 'absolute',
                                              }}
                                              onFinish={(formValues)=>slotElementChangeHandler(formValues, rowInd, colInd)}
                                            />
                                                // <FloatButton 
                                                //     onClick={()=>setopenSlotElDrawer(true)} 
                                                //     icon={<EditOutlined />}
                                                //     // style={{marginBottom: '0'}}
                                                //     type="primary"
                                                //     style={{
                                                //       right: 4,
                                                //       bottom: 0,
                                                //       position: 'absolute',
                                                //     }}
                                                //     shape="square"
                                                // />
                                                // <></>
                                            : <></>
                                        }
                                    </div>
                                </Badge>
                            </Col>
                        ))
                        }
                    </>
                ))}
            </Row>
        </Col>
      </Row>
    </>
  );
};
export default SlotBuilder;

  // const [gutterKey, setGutterKey] = useState(1);
  // const [vgutterKey, setVgutterKey] = useState(1);
  // const [colCountKey, setColCountKey] = useState(2);
  // const [rowCountKey, setRowCountKey] = useState(2);
  // const cols = [];
  // const rows = [];
  // const colCount = colCounts[colCountKey];
  // const rowCount = rowCounts[rowCountKey];



  // let colCode = '';
  // for (let i = 0; i < colCount; i++) {
  //   cols.push(
  //     <Col key={i.toString()} span={24 / colCount}>
  //       <div class='playground_div'>Column</div>
  //     </Col>,
  //   );
  //   colCode += `  <Col span={${24 / colCount}} />\n`;
  // }

  // for (let i = 0; i < rowCount; i++) {
  //   rows.push(
  //       <>
  //       {cols}
  //     </>,
  //   );
  // }



  // {console.log({gutters, vgutters, rows, cols})}
  // <Row gutter={[gutters[gutterKey], vgutters[vgutterKey]]}>
  //   {rows.map((_, rowInd) => (
  //     <>
  //       <Col key={`${rowInd}`} span={24 / (colCount+1)} >
  //           <div className={'playground_header'} >header</div>
  //       </Col>
  //       {cols.map((__, colInd) =>(
  //           <Col key={`${rowInd}__${colInd}`} className={`${rowInd}__${colInd}`} span={24 / (colCount+1)} onClick={()=> console.log({rowInd, colInd})}>
  //               <div class='playground_div'>Column</div>
  //           </Col>
  //       ))}
  //     </>
  //   ))}
  // </Row>,

  // const newSlot = 
//   {
//     "gutter": 12,
//     "vGutter": 12,
//     "product_id": "1",
//     "allowedBookingPerPerson": 4,
//     "total": 0,
//     "rows": [
//         {
//             "header": "9 - 10 AM",
//             "description": "some description",
//             "showToolTip": false,
//             "cols": [
//                 {
//                     "product_id": '1',
//                     "content": "Content",
//                     "show": true,
//                     "available_slots": 2,
//                     "checked": false,
//                     "booked": 0,
//                     "expires_in": null
//                 },
//                 {
//                   "product_id": '1',
//                   "content": "Content",
//                   "show": true,
//                   "available_slots": 2,
//                   "checked": false,
//                   "booked": 0,
//                   "expires_in": null
//               },
//               {
//                 "product_id": '1',
//                 "content": "Content",
//                 "show": true,
//                 "available_slots": 2,
//                 "checked": false,
//                 "booked": 0,
//                 "expires_in": null
//             }
//             ]
//         },
//         {
//           "header": "10 - 11 AM",
//           "description": "some description",
//           "showToolTip": false,
//             "cols": [
//               {
//                 "product_id": '1',
//                 "content": "Content",
//                 "show": true,
//                 "available_slots": 2,
//                 "checked": false,
//                 "booked": 0,
//                 "expires_in": null
//               },
//               {
//                 "product_id": '1',
//                 "content": "Content",
//                 "show": true,
//                 "available_slots": 2,
//                 "checked": false,
//                 "booked": 0,
//                 "expires_in": null
//               },
//               {
//                 "product_id": '1',
//                 "content": "Content",
//                 "show": true,
//                 "available_slots": 2,
//                 "checked": false,
//                 "booked": 0,
//                 "expires_in": null
//               }
//             ]
//         }
//     ]
// };