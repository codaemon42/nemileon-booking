import React, { useState, useEffect } from 'react';
import { Affix, Button, Divider, message, Result, Steps, theme } from 'antd';
import BoxCalendar from '../calendar/BoxCalendar';
import SlotBuilder from './SlotBuilder';
import ProductsTable from '../products/ProductsTable';
import { CaretLeftOutlined, CheckOutlined, LeftOutlined, LeftSquareOutlined, RightOutlined } from '@ant-design/icons';
import { Product } from '../products/Product.type';
import { ProductApi } from '../../http/ProductApi';
import SlotTemplateSelector from './SlotTemplateSelector';
import { SlotTemplateType } from './types/SlotTemplateType.type';
import { ProductTemplateType } from '../products/ProductTemplate.type';

const SlotBuilderSteps = ({stepStyle}) => {
  message.config({top: 50});
  const [LoadingPublish, setLoadingPublish] = useState(false);

  const onSelectProduct = (product, index) => {
    console.log({product})
    setSelectedProduct(product);
    setPInd(index);
  }

  const onSelectDate = (date) => {
    console.log({date});

    setSelectedDate(date);
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


  const [SelectedSlotTemplate, setSelectedSlotTemplate] = useState(new SlotTemplateType());

  const handleSelectSlotTemplate = (selectedSlotTemplate) => {
    console.log({selectedSlotTemplate})
    setSelectedSlotTemplate(selectedSlotTemplate)
  }


  const steps = [
    {
      title: 'Choose Product',
      content: <ProductsTable key={1} products={products} selectedIndex={pInd} type='select' buttonText='SELECT' onSelect={onSelectProduct} />,
    },
    {
      title: 'Choose Date',
      content: <BoxCalendar key={2} isFrontend={false} onSelect={onSelectDate} />,
    },
    {
      title: 'Choose Template',
      content: <SlotTemplateSelector key={3} onSelectSlotTemplate={handleSelectSlotTemplate} />,
    },
  ];


  const { token } = theme.useToken();
  const [current, setCurrent] = useState(0);
  const [selectedProduct, setSelectedProduct] = useState(new Product());

  const [selectedDate, setSelectedDate] = useState(null);


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




  const submitHandler = async () => {
    console.log({selectedProduct, selectedDate, SelectedSlotTemplate});
    // validation
    if(!SelectedSlotTemplate.id) return message.error('please Select a Template')
    if(!selectedProduct.id) return message.error('please Select a product')
    if(!selectedDate) return message.error('please Select a date');

    // process product meta => booking slot template for the particular product
    const newProductTemplate = new ProductTemplateType();
    newProductTemplate.product_id = selectedProduct.id;
    newProductTemplate.key = selectedDate.date;
    newProductTemplate.template = SelectedSlotTemplate.template;
    
    console.log({meta: {...newProductTemplate}})
    // call api to save the product meta
    setLoadingPublish(true);
    const productTemplateRes = await ProductApi.saveProductTemplates(newProductTemplate);
    setLoadingPublish(false);
    if(productTemplateRes.success){
      message.success(productTemplateRes.message)
    } else{
      message.error(productTemplateRes.message);
    }

  }


  return (
    <>
      <div style={stepStyle}>
        <Steps current={current} items={items} />
      </div>
      <Divider orientation='left' plain>
        <h3>{steps[current].title}</h3>
      </Divider>
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
          <Button loading={LoadingPublish} className='onsbks-success' icon={<CheckOutlined />} type="primary" onClick={() => submitHandler()}>
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