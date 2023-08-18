import { CheckOutlined, LeftOutlined, RightOutlined } from '@ant-design/icons';
import { Button, Col, Divider, message, Row, Steps } from 'antd';
import { useState, useEffect } from 'react'
import { ProductApi } from '../../http/ProductApi';
import BoxCalendar from '../calendar/BoxCalendar';
import { Product } from '../products/Product.type';
import { ProductTemplateType } from '../products/ProductTemplate.type';
import ProductsTable from '../products/ProductsTable';
import SlotPlotter from './SlotPlotter';
import { SlotTemplateType } from './types/SlotTemplateType.type';
import useWindowSize from '../../hooks/useWindowSize';
import dayjs from 'dayjs';
import { Slot } from './types/Slot.type';
import { BookingApi } from '../../http/BookingApi';

const SlotBooking = ({stepStyle}) => {
  message.config({top: 50});
  const { size } = useWindowSize();

  const [ProductTemplates, setProductTemplates] = useState(ProductTemplateType.List([]));
  const [SelectedProductTemplate, setSelectedProductTemplate] = useState(new ProductTemplateType());
  const [ProductTableLoading, setProductTableLoading] = useState(false)
  

  const onSelectProduct =  async (product, index) => {
    console.log({product})
    setSelectedProduct(product);
    setPInd(index);

    // fetch meta api and set a new use state
    setProductTableLoading(true)
    const productTemplateRes = await ProductApi.getProductTemplates(product.id);
    if(productTemplateRes.success) {
      setProductTemplates(productTemplateRes.result);
    }
    setProductTableLoading(false);
  }

  const onSelectDate = ({date, selectedProductTemplate}) => {
    console.log({date, selectedProductTemplate});

    setSelectedDate(date);
    setSelectedProductTemplate(selectedProductTemplate);
  }

  const [products, setProducts] = useState(Product.List([]));
  const [pInd, setPInd] = useState(null);

  useEffect(() => {
      getProducts();
  }, []);

  const getProducts = async () => {
      setProductTableLoading(true);
      const productRes = await ProductApi.getProducts();
      setProductTableLoading(false);
      console.log({ productRes });
      if (productRes.success) setProducts(productRes.result);
  };


  const steps = [
    {
      title: 'Choose Product',
      content: <ProductsTable key={1} loading={ProductTableLoading} products={products} selectedIndex={pInd} type='select' buttonText='SELECT' onSelect={onSelectProduct} />,
      status: 'process',
      disabled: false
    },
    {
      title: 'Choose Date',
      content: <BoxCalendar onSelect={onSelectDate} productTemplates={ProductTemplates} />,
      status: 'wait',
      disabled: true
    },
    {
      title: 'Start BookingsEntity',
      content: <SlotPlotter key={SelectedProductTemplate?.key || 0} style={{paddingTop: '13px'}} defaultSlot={SelectedProductTemplate?.template || new Slot()} />,
      status: 'wait',
      disabled: true
    }
  ];


  // const { token } = theme.useToken();
  const [current, setCurrent] = useState(0);
  const [selectedProduct, setSelectedProduct] = useState(new Product());

  const [selectedDate, setSelectedDate] = useState(dayjs().format("YYYY-MM-DD"));


  const next = () => {
    if(current === 1 && !SelectedProductTemplate){ // box calendar
      message.warning('Please Select a valid date');
    } else{
      setCurrent(current + 1);
    }
  };
  const prev = () => {
    setCurrent(current - 1);
  };

  const items = steps.map((item, index) => ({
    key: item.title,
    title: item.title,
    disabled: current < index ? true : false,
    status: current > index ? 'finish' : current == index ? 'process' : 'wait'
  }));




  const submitHandler = async () => {
    console.log({selectedProduct, selectedDate, SelectedProductTemplate});
    if(!selectedProduct.id) message.error('please Select a product')
    if(!selectedDate) message.error('please Select a Date')
    if(!SelectedProductTemplate.id) message.error('please Select slots')

    // add to cart the items
    const bookingRes = await BookingApi.createBooking({productId: selectedProduct.id, slots: SelectedProductTemplate.template})
    if(bookingRes.success) message.success(bookingRes.message);
    else message.error(bookingRes.message);
  }


  const onChangeStep = (stepInd) => {
    setCurrent(stepInd);
  }


  return (
    <>
      <div style={stepStyle}>
        <Steps current={current} onChange={onChangeStep} items={items} />
      </div>
      <div style={{marginRight: 0, marginTop: 20}} >
        {steps[current].content} 
      </div>
      <div
        style={{
          marginTop: 20,
          marginRight:  0,
          display: 'flex',
          justifyContent: 'space-between'
        }}
      >
        <Button
            disabled={current === 0}
            icon={<LeftOutlined />}
            onClick={() => prev()}
          >
            PREV
          </Button>
          {current === steps.length-1 
          ? 
          <Button className='onsbks-success' icon={<CheckOutlined />} type="primary" onClick={() => submitHandler()}>
              PUBLISH 
          </Button>
          :
          <Button type="primary" onClick={() => next()}>
              NEXT <RightOutlined />
          </Button>
          }
      </div>
      
    </>
  );
}

export default SlotBooking