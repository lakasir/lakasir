import { Button } from "@/ui/Buttons";
import { Form, Input, Select } from "@/ui/Fields";
import { FilePicker } from "@/ui/Fields/File";
import Image from "next/image";
import Link from "next/link";

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
          <FilePicker name="image" label={"Upload Image"} />
          <Input
            name={"name"}
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
            className="rounded-r-none border-r-0"
            append={
              <Link href={"/menu/category"} className="block">
                <a className="bg-gray-100 border-2 border-gray-200 rounded-r-lg w-1/5 flex justify-center items-center cursor-pointer">
                  <Image src="/assets/icons/Plus.svg" width={30} height={30} />
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
