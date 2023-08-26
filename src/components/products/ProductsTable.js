import { Button, Image, Table } from "antd";
import fallbackImage from "./../../assets/images/fallback.png";
import { alphabeticSort } from "./../../helper/alphabeticSort";
import { useState, useEffect } from "react";
import { ProductApi } from "../../http/ProductApi";
import { Product } from "./Product.type";
import { CheckOutlined } from "@ant-design/icons";
import { ProductTemplateType } from "./ProductTemplate.type";

const ProductsTable = ({ loading = false, products, selectedIndex = null, type = "booking", buttonText = "book", onSelect=(record, index)=> {} }) => {

  const [pInd, setPInd] = useState(selectedIndex);
  const [Loading, setLoading] = useState(false);

  useEffect(()=>{
    setLoading(loading);
  }, [loading])


    const nameFilterRender = (data=[]) => {
      let r = []
      data.map(d => {
        r.push({text: d.name, value: d.name})
      })
      return r;
    }
    const columns = [
        {
            title: "Image",
            dataIndex: "imageUrl",
            key: "image",
            render: (imageUrl) => (
                <Image
                    width={100}
                    height={70}
                    wrapperStyle={{ borderRadius: "5px" }}
                    rootClassName="ONSBKS_product_table_img_wrapper"
                    src={imageUrl}
                    fallback={fallbackImage}
                />
            ),
        },
        {
            title: "Name",
            dataIndex: "name",
            key: "name",
            filters: nameFilterRender(products),
            render: (text) => <a>{text}</a>,
            onFilter: (value, record) => record.name.indexOf(value) === 0,
            filterSearch: true,
            sorter: (a, b) => alphabeticSort(a, b, "name"),
        },
        {
            title: "Price",
            dataIndex: "price",
            key: "price",
            sorter: (a, b) => a.price - b.price,
        },
        {
            title: "Short Description",
            dataIndex: "short_description",
            key: "short_description",
        },
        {
          title: "Rating",
          dataIndex: "average_rating",
          key: "average_rating",
          sorter: (a, b) => a.key - b.key,
        },
        {
          title: "Total Rating",
          dataIndex: "review_count",
          key: "review_count",
          sorter: (a, b) => a.key - b.key,
        },
        {
            title: "Action",
            key: "action",
            render: (_, record, index) => (
                <Button key={record.key} className={index === pInd && type === 'select' ? 'onsbks-success' : ''} onClick={() => handleAction(record, index)} type="primary">
                    {index === pInd && type === 'select' ? <CheckOutlined /> : <></>}
                    {buttonText}
                </Button>
            ),
        },
    ];

    const handleAction = (record, index) => {
      if(type === 'booking'){
        // on select send record
        setPInd(index)
        onSelect(record, index);
      // add to cart with cart item meta data added
      }
      else if(type === 'select'){
        setPInd(index)
        onSelect(record, index);
      }
    };

    const onRow = (record, index) => {

      return {
        onClick: (event) => {
          // setPInd(index);
        }
      }
    }
    return (
        <Table
            data-testId="product-table"
            loading={Loading}
            columns={columns}
            dataSource={products}
            pagination={{ pageSize: 2 }}
            onRow={onRow}
        />
    );
};
export default ProductsTable;
