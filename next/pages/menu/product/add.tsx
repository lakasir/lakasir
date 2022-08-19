import { NextPage } from "next";
import Button from "../../../components/Ui/Buttons/Button";
import Form from "../../../components/Ui/Fields/Form";
import Input from "../../../components/Ui/Fields/Input";
import Select from "../../../components/Ui/Fields/Select";
import Layout from "../../../components/Ui/Layout";

const AddProduct: NextPage = () => {
  return (
    <Layout title="Add Product" back={true}>
      <div className="py-3">
        <Form
          className="space-y-8"
          initialValue={{
            name: "",
            category: "",
            stock: "",
            initial_price: "",
            selling_price: "",
            type: "",
            unit: ""
          }}
          onSubmit={() => console.log("ok")}
        >
          {() => (
            <>
              <Input
                name={"email"}
                type={"text"}
                label={
                  <>
                    Name<span className="text-red-500">*</span>
                  </>
                }
              />
              <Select
                name={"category"}
                label={
                  <>
                    Category<span className="text-red-500">*</span>
                  </>
                }
              />
              <Input
                name={"stock"}
                type={"number"}
                label={
                  <>
                    Stock<span className="text-red-500">*</span>
                  </>
                }
              />
              <Input
                name={"initial_price"}
                type={"number"}
                label={
                  <>
                    Initial Price<span className="text-red-500">*</span>
                  </>
                }
              />
              <Input
                name={"selling_price"}
                type={"number"}
                label={
                  <>
                    Selling Price<span className="text-red-500">*</span>
                  </>
                }
              />
              <Select
                name={"type"}
                label={
                  <>
                    Type<span className="text-red-500">*</span>
                  </>
                }
              />
              <Input
                name={"unit"}
                type={"text"}
                label={
                  <>
                    Unit<span className="text-red-500">*</span>
                  </>
                }
              />
              <Button className="w-full py-4">Save</Button>
            </>
          )}
        </Form>
      </div>
    </Layout>
  );
};

export default AddProduct;
