import { Layout } from "@/ui/Layout";
import { NextPage } from "next";
import FormProduct from "../form";

const EditProduct: NextPage = () => {
  return (
    <Layout title="Edit Product" back={true}>
      <div className="py-3">
        <FormProduct />
      </div>
    </Layout>
  );
};

export default EditProduct;
