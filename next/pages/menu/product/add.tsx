import { NextPage } from "next";
import Layout from "../../../components/Ui/Layout";
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
