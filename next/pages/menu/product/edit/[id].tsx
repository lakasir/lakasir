import { useProduct } from "@/hooks/product";
import { IProductResponse } from "@/models/product";
import { Response } from "@/models/response";
import { Layout } from "@/ui/Layout";
import { NextPage } from "next";
import { useRouter } from "next/router";
import { useEffect, useState } from "react";
import FormProduct, { FormProductData } from "../form";

const EditProduct: NextPage = () => {
  const router = useRouter();
  const { getDetailProduct } = useProduct();
  const { id } = router.query;
  const [productData, setProductData] = useState<FormProductData>();
  const loadProduct = async () => {
    const response = await getDetailProduct(Number(id));
    if (response) {
      const responseData = response as Response<IProductResponse>;
      const productData: FormProductData = {
        images: responseData.data.images.map((m) => m.url),
        name: responseData.data.name,
        category: responseData.data.category?.id,
        stock: responseData.data.stock,
        initial_price: responseData.data.initial_price,
        selling_price: responseData.data.selling_price,
        type: responseData.data.type,
        unit: responseData.data.unit,
      };

      setProductData(productData);
    }
  };

  useEffect(() => {
    if (id) {
      loadProduct();
    }
  }, [id]);

  return (
    <Layout title="Edit Product" back={true}>
      <div className="py-3">
        <FormProduct id={id ? +id : undefined} form={productData != undefined ? productData as any : undefined}/>
      </div>
    </Layout>
  );
};

export default EditProduct;
