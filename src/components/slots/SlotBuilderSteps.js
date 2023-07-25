import React, { useState, useEffect } from 'react';
import { Affix, Button, message, Result, Steps, theme } from 'antd';
import BoxCalendar from '../calendar/BoxCalendar';
import SlotBuilder from './SlotBuilder';
import ProductsTable from '../products/ProductsTable';
import { CaretLeftOutlined, CheckOutlined, LeftOutlined, LeftSquareOutlined, RightOutlined } from '@ant-design/icons';
import { Product } from '../products/Product.type';
import { ProductApi } from '../../http/ProductApi';

const SlotBuilderSteps = () => {
  const onSelectProduct = (product, index) => {
    console.log({product})
    setSelectedProduct(product);
    setPInd(index);
  }

  const onSelectDate = (date) => {
    console.log({date});

    setSelectedDate(date);
  }

  const onSlotChange = (slotChanged) => {
    console.log({slotChanged})
    setSlot(slotChanged);
  }

  const onNameChange = (name) => {
    console.log({name})
    setName(name);
  }

  const [products, setProducts] = useState(Product.List([]));
  const [pInd, setPInd] = useState(null);

  useEffect(() => {
      getProducts();
  }, []);

  const getProducts = async () => {
      const productRes = await ProductApi.getProducts();
      console.log({ productRes });
      if (productRes.success) setProducts(productRes.result);
  };


  const steps = [
    // {
    //     title: 'Configure Slots',
    //     content: <SlotBuilder />,
    //   },
    {
      title: 'Choose Product',
      content: <ProductsTable key={1} products={products} selectedIndex={pInd} type='select' buttonText='SELECT' onSelect={onSelectProduct} />,
    },
    {
      title: 'Choose Date',
      content: <BoxCalendar key={2} onSelect={onSelectDate} />,
    },
    {
      title: 'Configure Slots',
      content: <SlotBuilder key={3} onSlotChange={onSlotChange} onNameChange={onNameChange} />,
    },
  ];


  const { token } = theme.useToken();
  const [current, setCurrent] = useState(0);
  const [selectedProduct, setSelectedProduct] = useState(new Product());

  const dateNow = new Date();
  const [selectedDate, setSelectedDate] = useState(dateNow.toISOString().split('T')[0]);

  const [slot, setSlot] = useState(null);
  const [name, setName] = useState('');



  const next = () => {
    setCurrent(current + 1);
  };
  const prev = () => {
    setCurrent(current - 1);
  };
  const items = steps.map((item) => ({
    key: item.title,
    title: item.title,
  }));




  const submitHandler = ({selectedProduct, selectedDate, slot}) => {
    console.log({selectedProduct, selectedDate, slot, name});
    // call api to save the product meta
  }


  return (
    <>
      <div >
        <Steps current={current} items={items} />
      </div>
      <div style={{marginRight: 20}} >{steps[current].content}</div>
      <div
        style={{
          marginTop: 20,
          marginRight: 20,
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
          <Button className='onsbks-success' icon={<CheckOutlined />} type="primary" onClick={() => submitHandler({selectedProduct, selectedDate, slot})}>
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
};
export default SlotBuilderSteps;