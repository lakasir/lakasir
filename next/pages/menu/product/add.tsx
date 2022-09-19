import { Layout } from "@/ui/Layout";
import { NextPage } from "next";
import FormProduct from "./form";

const AddProduct: NextPage = () => {
  return (
    <Layout title="Add Product" back={true}>
      <div className="py-3">
      <FormProduct />
      </div>
    </Layout>
  );
};

export default AddProduct;
