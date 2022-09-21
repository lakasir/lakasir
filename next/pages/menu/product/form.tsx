import { Button } from "@/ui/Buttons";
import { Form, Input, Select } from "@/ui/Fields";
import { DocumentIcon, UploadIcon } from "@heroicons/react/outline";
import Image from "next/image";
import Link from "next/link";
import { useRef } from "react";

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
  const fileInput = useRef(null);
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
          <input
            type="file"
            name="image"
            ref={fileInput}
            onChange={() => {
              if (fileInput.current) {
                const current = fileInput.current as HTMLInputElement;
                console.log(current.files);
              }
            }}
            style={{ display: "none" }}
          />
          <div className="max-h-40 w-full bg-gray-50 flex justify-between gap-x-2">
            <div
              className="w-5/12 h-40 bg-gray-100 rounded-lg flex justify-center items-center border-[4px] border-dotted border-gray-300 cursor-pointer"
              onClick={() => {
                if (fileInput.current) {
                  (fileInput.current as HTMLInputElement).click();
                }
              }}
            >
              <div className="space-y-1">
                <UploadIcon className="w-10 h-10 text-lakasir-primary mx-auto" />
                <p className="text-sm text-gray-400">Upload Image</p>
              </div>
            </div>
            <div className="w-7/12 py-2 overflow-y-scroll">
              {[
                1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18,
                19, 20,
              ].map((item, index) => (
                <div className="flex">
                  <DocumentIcon className="w-10 h-10 text-lakasir-primary mr-1" />
                  <div className="w-full space-y-1">
                    <div className="flex w-11/12">
                      <p className="text-sm font-normal">
                        Photo <span className="font-light">7.5mb</span>
                      </p>
                      <p className="text-sm font-bold ml-auto cursor-pointer">
                        x
                      </p>
                    </div>
                    <div className="h-2 w-11/12 rounded-xl bg-blue-200"></div>
                  </div>
                </div>
              ))}
            </div>
          </div>
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
