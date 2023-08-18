import { Product } from "../components/products/Product.type";
import { ProductTemplateType } from "../components/products/ProductTemplate.type";
import { http, prepareUrl } from "./http";
import { HttpResponseData } from "./HttpResponseData";

class ProductApi {


    static async getProducts(){
        const products = await http.get(prepareUrl('/products'));
        return new ProductResponseData(products.data);
    }

    static async getProductTemplates(productId){
        const productTemplateRes = await http.get(prepareUrl(`/products/templates?product_id=${productId}`));
        return new ProductTemplateResponseData(productTemplateRes.data);
    }


    static async saveProductTemplates(data = new ProductTemplateType()){
        const productTemplateRes = await http.post(prepareUrl(`/products/template`), {...data});
        return new ProductTemplateResponseData(productTemplateRes.data);
    }
}


class ProductResponseData extends HttpResponseData{
    constructor(data=null){
        super(data);
        this.result = Product.List(this.result);
    }
}

class ProductTemplateResponseData extends HttpResponseData{
    constructor(data=null){
        super(data);
        this.result = ProductTemplateType.List(this.result);
    }
}

export { ProductApi }