import { Product } from "../components/products/Product.type";
import { http, prepareUrl } from "./http";
import { HttpResponseData } from "./HttpResponseData";

class ProductApi {


    static async getProducts(){
        const products = await http.get(prepareUrl('/products'));
        return new ProductResponseData(products.data);
    }
}


class ProductResponseData extends HttpResponseData{
    constructor(data=null){
        super(data);
        this.result = Product.List(this.result);
    }
}

export { ProductApi }