import Image from "next/image";
import Link from "next/link";
import Button from "../../../components/Ui/Buttons/Button";
import Form from "../../../components/Ui/Fields/Form";
import Input from "../../../components/Ui/Fields/Input";
import { Select } from "../../../components/Ui/Fields/Select";

interface IFormProductInterface {
  form?: ProductData;
}

interface ProductData {
  name?: string;
  category?: number;
  stock?: number;
  initial_price?: number;
  selling_price?: number;
  type?: number;
  unit?: number;
}

const FormProduct = (props: IFormProductInterface) => {
  return (
    <Form
      className="space-y-8"
      initialValue={{
        ...props.form,
      }}
      onSubmit={() => console.log("ok")}
    >
      {() => (
        <>
          <div className="flex justify-between">
            <div className="w-28 h-28 bg-transparent border-[10px] border-lakasir-primary rounded-[20px] flex justify-center items-center cursor-pointer">
              <Image src={"/assets/icons/Image.svg"} width={"50%"} height={"50%"}/>
            </div>
            <Input
              name={"email"}
              type={"text"}
              label={
                <>
                  Name<span className="text-red-500">*</span>
                </>
              }
            />
          </div>
          <Select
            name={"category"}
            label={
              <>
                Category<span className="text-red-500">*</span>
              </>
            }
            className="rounded-r-none border-r-0"
            append={
              <Link href={"/menu/category"} className="block">
                <a className="bg-gray-100 border-2 border-gray-200 rounded-r-lg w-1/5 flex justify-center items-center cursor-pointer">
                  <Image src="/assets/icons/Plus.svg" width={30} height={30}/>
                </a>
              </Link>
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
  );
};

export default FormProduct;
